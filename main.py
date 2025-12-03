import os
import re
import csv
import time
import uuid
import random
import io
from datetime import datetime
from typing import Dict, List, Optional
from urllib.parse import urlparse, parse_qs, urlencode, urlunparse
from pathlib import Path  # <-- NEW

from dotenv import load_dotenv
import pymysql
from fastapi import FastAPI, BackgroundTasks, HTTPException
from fastapi.responses import RedirectResponse, JSONResponse, StreamingResponse
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field

from bs4 import BeautifulSoup
import requests

# -------------------------------------------------
# Load environment variables from .env
#
# This works in two scenarios:
#   1) Local development:
#        - .env file at project root
#   2) Render.com with Secret File:
#        - Secret file named ".env" is available at:
#            - /etc/secrets/.env
#            - and in the project root
#
# We try both locations and DO NOT override existing
# env vars (e.g. ones set directly in Render UI).
# -------------------------------------------------
dotenv_candidates = [
    Path(".env"),
    Path("/etc/secrets/.env"),
]

for dotenv_path in dotenv_candidates:
    if dotenv_path.is_file():
        load_dotenv(dotenv_path=dotenv_path, override=False)

# ---------------- In-memory state ----------------
RUNS: Dict[str, dict] = {}

app = FastAPI(title="Kijiji Scraper API", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=[
        "https://agentbookr.com",
        "https://www.agentbookr.com",
        "http://localhost:3000",
    ],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ---------------- HTTP session ----------------
SESSION = requests.Session()
HEADERS = {
    "User-Agent": (
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
        "AppleWebKit/537.36 (KHTML, like Gecko) "
        "Chrome/123.0.0.0 Safari/537.36"
    ),
    "Accept-Language": "en-CA,en-US;q=0.9,en;q=0.8",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
}

# ---------------- DB helpers ----------------
def get_db_connection():
    """
    Returns a MySQL connection using env vars.

    Designed to work with Laravel-style .env:

      DB_CONNECTION=mysql
      DB_HOST=...
      DB_PORT=3306
      DB_DATABASE=...
      DB_USERNAME=...
      DB_PASSWORD=...

    Values can come from:
      - Local .env (when developing)
      - Render secret file ".env" (loaded above)
      - Environment Variables set directly in Render

    If connection fails (missing envs, DB down, etc.), returns None so the
    scraper still works with in-memory storage only.
    """
    host = os.getenv("DB_HOST", "localhost")
    port = int(os.getenv("DB_PORT", "3306"))

    # Prefer Laravel names, but allow fallbacks
    user = os.getenv("DB_USERNAME") or os.getenv("DB_USER")
    password = os.getenv("DB_PASSWORD")
    name = os.getenv("DB_DATABASE") or os.getenv("DB_NAME")

    # Basic sanity check on required pieces
    if not (user and password and name):
        print(
            "DB connection not configured. "
            "Expected DB_USERNAME, DB_PASSWORD, DB_DATABASE (or DB_NAME)."
        )
        return None

    try:
        conn = pymysql.connect(
            host=host,
            port=port,
            user=user,
            password=password,
            database=name,
            charset="utf8mb4",
            autocommit=True,
        )
        return conn
    except Exception as e:
        # This goes to Render logs; helpful for debugging
        print(f"Error connecting to DB at {host}:{port} / {name}: {e}")
        return None


def db_execute(query: str, params: tuple):
    conn = get_db_connection()
    if not conn:
        return
    try:
        with conn.cursor() as cur:
            cur.execute(query, params)
    finally:
        conn.close()


def db_fetchone(query: str, params: tuple):
    conn = get_db_connection()
    if not conn:
        return None
    try:
        with conn.cursor() as cur:
            cur.execute(query, params)
            return cur.fetchone()
    finally:
        conn.close()


def update_run_status_in_db(
    run_id: str,
    status: Optional[str] = None,
    count: Optional[int] = None,
    set_started: bool = False,
    set_finished: bool = False,
):
    """
    UPDATE scrape_runs row for this run_id.
    Does not INSERT (to avoid user_id NOT NULL issues) – assumes row already exists.
    """
    conn = get_db_connection()
    if not conn:
        return

    try:
        parts = []
        params: List[object] = []

        if status is not None:
            parts.append("status = %s")
            params.append(status)
        if count is not None:
            parts.append("count = %s")
            params.append(count)
        if set_started:
            parts.append("started_at = UTC_TIMESTAMP()")
        if set_finished:
            parts.append("finished_at = UTC_TIMESTAMP()")

        if not parts:
            return

        parts.append("updated_at = UTC_TIMESTAMP()")
        sql = "UPDATE scrape_runs SET " + ", ".join(parts) + " WHERE id = %s"
        params.append(run_id)

        with conn.cursor() as cur:
            cur.execute(sql, tuple(params))
    finally:
        conn.close()


def persist_run_csv_to_db(run_id: str):
    """
    Build CSV for this run from RUNS[run_id]['results'] and store it in
    scrape_runs.csv_data (plus status/count/finished_at).
    Safe no-op if DB is not configured or row doesn't exist.
    """
    run = RUNS.get(run_id)
    if not run:
        return

    results = run.get("results") or []
    status = run.get("status") or "succeeded"
    count = run.get("count") or 0

    conn = get_db_connection()
    if not conn:
        return

    try:
        csv_text = ""
        if results:
            fieldnames = list(results[0].keys())
            buf = io.StringIO()
            writer = csv.DictWriter(buf, fieldnames=fieldnames, quoting=csv.QUOTE_ALL)
            writer.writeheader()
            for row in results:
                writer.writerow(row)
            csv_text = buf.getvalue()

        with conn.cursor() as cur:
            cur.execute(
                """
                UPDATE scrape_runs
                   SET csv_data   = %s,
                       status     = %s,
                       count      = %s,
                       finished_at = COALESCE(finished_at, UTC_TIMESTAMP()),
                       updated_at  = UTC_TIMESTAMP()
                 WHERE id = %s
                """,
                (csv_text, status, count, run_id),
            )
    finally:
        conn.close()


# ---------------- Models ----------------
class ScrapeParams(BaseModel):
    base_url: str
    max_listings: int = Field(ge=1, le=5000, default=50)


class StartResponse(BaseModel):
    run_id: str
    status: str


class RunStatus(BaseModel):
    run_id: str
    status: str
    count: int
    started_at: Optional[datetime] = None
    finished_at: Optional[datetime] = None
    logs: List[str] = []


# ---------------- Utilities ----------------
def log(run_id: str, msg: str):
    RUNS[run_id]["logs"].append(msg)


def fetch_html(run_id: str, url: str, tries: int = 4, base_delay: float = 2.0) -> str:
    """
    Generic HTML fetcher with special handling for HTTP 429.
    - For 429: back off more aggressively, then retry.
    - For other 4xx/5xx: raise after logging.
    """
    last_err = None
    for i in range(tries):
        try:
            resp = SESSION.get(url, headers=HEADERS, timeout=20)
            status = resp.status_code

            if status == 429:
                wait_for = base_delay * (i + 1) * 3  # e.g. 6, 12, 18, 24 sec
                log(
                    run_id,
                    f"fetch_html HTTP 429 on {url} (attempt {i+1}/{tries}), backing off {wait_for:.1f}s",
                )
                time.sleep(wait_for)
                last_err = RuntimeError("HTTP 429")
                continue

            if status >= 400:
                raise RuntimeError(f"HTTP {status}")

            return resp.text

        except Exception as e:
            last_err = e
            log(run_id, f"fetch_html error on {url}: {e}")
            # jitter so we don't look perfectly robotic
            sleep_for = base_delay * (i + 1) + random.uniform(0, 1.5)
            time.sleep(sleep_for)

    # after all tries
    raise last_err if last_err else RuntimeError("fetch failed")


def canonicalize_listing_url(href: str) -> str:
    if not href:
        return ""
    if not href.startswith("http"):
        href = "https://www.kijiji.ca" + href
    parsed = urlparse(href)
    return urlunparse((parsed.scheme, parsed.netloc, parsed.path, "", "", ""))


def get_listing_links_from_html(html: str) -> List[str]:
    soup = BeautifulSoup(html, "html.parser")
    raw_links: List[str] = []

    for a in soup.select('a[data-testid="listing-link"]'):
        href = a.get("href")
        if href:
            raw_links.append(canonicalize_listing_url(href))

    # fallback selectors
    for a in soup.select('a[href*="/v-apartments-condos/"], a[href*="/v-real-estate/"]'):
        href = a.get("href")
        if href:
            raw_links.append(canonicalize_listing_url(href))

    seen = set()
    result: List[str] = []
    for href in raw_links:
        if href and href not in seen:
            seen.add(href)
            result.append(href)
    return result


def find_next_page_url(current_url: str, page_html: str) -> Optional[str]:
    """
    Determine the "next page" URL.

    Priority:
    1. Use rel="next" / explicit pagination links if present in HTML.
    2. Fallback: construct /page-N/ style before the last path segment (cXXlYY...)
       AND set ?page=N in the query string, e.g.:

       /b-apartments-condos/oshawa-durham-region/page-2/c37l1700275...?&page=2&view=list
    """
    soup = BeautifulSoup(page_html, "html.parser")

    # 1) <link rel="next"> if present
    link = soup.find("link", rel=lambda v: v and "next" in v.lower())
    if link and link.get("href"):
        href = link["href"]
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"

    # 2) <a aria-label="Next">
    a = soup.find("a", attrs={"aria-label": re.compile(r"^\s*Next\s*$", re.I)})
    if a and a.get("href"):
        href = a["href"]
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"

    # 3) <a>Next</a> text
    a2 = soup.find("a", string=re.compile(r"^\s*Next\s*$", re.I))
    if a2 and a2.get("href"):
        href = a2.get("href")
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"

    # 4) data-testid pagination button
    btn = soup.select_one('a[data-testid="pagination-next-link"]')
    if btn and btn.get("href"):
        href = btn.get("href")
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"

    # 5) Fallback: manually build /page-N/ + ?page=N
    parsed = urlparse(current_url)
    path = parsed.path
    q = parse_qs(parsed.query)

    # detect current page number from path (/page-N/) or query (?page=N)
    cur_page = 1
    m = re.search(r"/page-(\d+)/", path)
    if m:
        try:
            cur_page = int(m.group(1))
        except Exception:
            cur_page = 1
    elif "page" in q and q["page"]:
        try:
            cur_page = int(q["page"][0])
        except Exception:
            cur_page = 1

    next_page = cur_page + 1

    # split path and inject/replace page-N segment
    parts = path.strip("/").split("/")
    if parts == [""]:
        parts = []

    if any(p.startswith("page-") for p in parts):
        # replace existing page-N in the path
        new_parts = [
            (f"page-{next_page}" if p.startswith("page-") else p) for p in parts
        ]
    else:
        # insert page-N *before* last segment (usually cXXlYY...)
        if len(parts) >= 1:
            insert_at = len(parts) - 1
        else:
            insert_at = 0
        new_parts = parts[:insert_at] + [f"page-{next_page}"] + parts[insert_at:]

    new_path = "/" + "/".join(new_parts)

    # always also keep ?page=N in query
    q["page"] = [str(next_page)]
    new_qs = urlencode({k: v[0] if isinstance(v, list) else v for k, v in q.items()})

    next_url = urlunparse(
        (parsed.scheme, parsed.netloc, new_path, parsed.params, new_qs, parsed.fragment)
    )

    return next_url if next_url != current_url else None


# ---------------- Phone helpers ----------------
NANP_E164 = re.compile(r"^\+1([2-9]\d{2})([2-9]\d{2})(\d{4})$")
PLUS1_IN_DESC = re.compile(
    r"(?<!\d)\+1[\s\-.]?\(?\s*([2-9]\d{2})\s*\)?[\s\-.]?([2-9]\d{2})[\s\-.]?(\d{4})(?!\d)"
)
ANY_TEL = re.compile(r"^tel:", re.I)
ANY_PHONE_TEXT = re.compile(
    r"(?<!\d)(?:\+?1[\s\-.]?)?\(?\s*([2-9]\d{2})\s*\)?[\s\-.]?([2-9]\d{2})[\s\-.]?(\d{4})(?!\d)"
)


def is_valid_nanp(e164: str) -> bool:
    return bool(NANP_E164.fullmatch(e164 or ""))


def to_e164_from_digits(digits: str) -> str:
    digits = re.sub(r"\D+", "", digits or "")
    if len(digits) == 11 and digits.startswith("1"):
        candidate = f"+{digits}"
    elif len(digits) == 10:
        candidate = f"+1{digits}"
    else:
        return ""
    return candidate if is_valid_nanp(candidate) else ""


def normalize_tel_href_or_text(raw: str) -> str:
    if not raw:
        return ""
    if raw.lower().startswith("tel:"):
        raw = raw[4:]
    return to_e164_from_digits(raw)


def find_phone_from_reveal(soup: BeautifulSoup) -> str:
    a = soup.find("a", href=ANY_TEL)
    if a:
        cand = normalize_tel_href_or_text(a.get("href", "")) or normalize_tel_href_or_text(
            a.get_text(strip=True)
        )
        if cand:
            return cand
    txt = soup.get_text(" ", strip=True)
    m = ANY_PHONE_TEXT.search(txt)
    if m:
        return to_e164_from_digits("".join(m.groups()))
    return ""


def find_phone_in_description(text: str) -> str:
    if not text:
        return ""
    m = PLUS1_IN_DESC.search(text)
    if not m:
        return ""
    return to_e164_from_digits("".join(m.groups()))


def extract_description_text(soup: BeautifulSoup) -> str:
    desc = soup.find("div", {"data-testid": "vip-description-wrapper"})
    if not desc:
        return ""
    txt = desc.get_text(" ", strip=True)
    txt = re.sub(r"\bAd\s*ID\s*\d+\b", " ", txt, flags=re.I)
    txt = re.sub(r"\b(Social\s*(Lead)?\s*ID)\s*\d+\b", " ", txt, flags=re.I)
    return txt


# ----------- Seller / meta helpers -----------
PROFILE_HREF_RE = re.compile(r"^/(o-profile|o-kijiji-user|o-[a-z0-9\-]+)/", re.I)


def extract_seller_info(soup: BeautifulSoup) -> tuple[str, str]:
    a = soup.select_one(
        'h3 a[href^="/o-profile/"], '
        'h3 a[href^="/o-kijiji-user/"], '
        'h3 a[href^="/o-"], '
        'h3 .sc-82669b63-0 a.sc-683826d7-2.cPzJWd[href^="/o-"]'
    )
    if not a:
        a = soup.find("a", href=PROFILE_HREF_RE)
    name = (a.get_text(strip=True) if a else "") or "Kijiji User"
    url = ("https://www.kijiji.ca" + a["href"]) if (a and a.get("href")) else ""
    return name, url


AVAILABLE_P_RE = re.compile(r"^\s*Available\b", re.I)


def extract_available_date_from_details(soup: BeautifulSoup) -> str:
    p = soup.find("p", string=AVAILABLE_P_RE)
    if not p:
        return ""
    txt = p.get_text(strip=True)
    m = re.search(r"Available\s*(.*)$", txt, re.I)
    return m.group(1).strip() if m else txt


# ---------------- The scraper ----------------
def run_scrape(run_id: str, params: ScrapeParams):
    RUNS[run_id].update(
        status="running", started_at=datetime.utcnow(), results=[], count=0
    )
    # mark running in DB (if row exists)
    update_run_status_in_db(run_id, status="running", count=0, set_started=True)

    try:
        log(
            run_id,
            f"DEBUG: run_scrape start base_url={params.base_url} max_listings={params.max_listings}",
        )

        realtor_filter = re.compile(
            r"""
              \brealstar\b|
              \bmgmt\b|
              \bon[-\s]?site\s+management\b|
              \bproperty\s*management\b|
              \bmanagement\s+company\b|
              \bproperty\s*manager(s)?\b|
              \brealty\b|
              \brealt(or|ors)\b|
              \breal\s*estate\s+(agent|broker|brokerage|team)\b|
              \b(leasing|rental)\s+agent\b|
              \blisting\s*agent\b|
              \bbroker(age)?\b|
              \brentals?\s+(inc|ltd|corp|company|management|mgmt|realty|group|team)\b|
              \bmls\b|
              \bmultiple\s+listing\s+service\b|
              \bthird\s*party\b|
              \bthird\s*parties\b|
              \bsublease\b|
              \bdeferral\b
            """,
            re.IGNORECASE | re.VERBOSE,
        )

        current_url = params.base_url
        scraped_count = 0
        page = 1

        saved_listing_urls: set[str] = set()

        MAX_PAGES = 150

        while scraped_count < params.max_listings and page <= MAX_PAGES:
            log(run_id, f"Visiting search page {page}: {current_url}")

            # ---- fetch search page with 429-aware handling ----
            try:
                search_html = fetch_html(run_id, current_url)
            except Exception as e:
                msg = str(e) or ""
                log(run_id, f"Failed to fetch search page {page}: {e}")
                if "HTTP 429" in msg:
                    log(
                        run_id,
                        f"Stopping pagination after page {page} due to repeated HTTP 429 rate limiting "
                        f"from Kijiji. Returning partial results.",
                    )
                    break
                else:
                    # unexpected error: bail hard
                    raise

            listing_links = get_listing_links_from_html(search_html)
            if not listing_links:
                log(run_id, "No listing anchors found on this page. Stopping.")
                break

            log(
                run_id,
                f"Found {len(listing_links)} canonical listing URLs on page {page}.",
            )

            page_saved_before = scraped_count
            processed_on_page = 0

            for href in listing_links:
                if scraped_count >= params.max_listings:
                    break

                processed_on_page += 1

                # ---- fetch listing detail page, tolerant of 429 ----
                try:
                    detail_html = fetch_html(run_id, href, tries=3, base_delay=2.5)
                    # be extra polite to avoid more 429s
                    time.sleep(1.5 + random.uniform(0.2, 0.8))
                except Exception as e:
                    log(run_id, f"Skipped {href} — failed to open ({e})")
                    continue

                soup = BeautifulSoup(detail_html, "html.parser")

                description = extract_description_text(soup)
                prospect_name, profile_url = extract_seller_info(soup)

                title_el = soup.select_one("h1[data-testid='vip-title']") or soup.find(
                    "h1"
                )
                title_text = title_el.get_text(" ", strip=True) if title_el else ""

                emails = re.findall(
                    r"[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+",
                    description or "",
                )
                email = emails[0] if emails else ""

                # --- realtor / management filter ---
                filter_text = " ".join(
                    t for t in [description, prospect_name, title_text] if t
                )
                if realtor_filter.search(filter_text):
                    log(run_id, f"Skipped {href} — realtor / management keywords.")
                    continue

                name_l = (prospect_name or "").lower()
                email_l = (email or "").lower()

                if "residential" in name_l or "residential" in email_l:
                    log(run_id, f"Skipped {href} — 'residential' in name/email.")
                    continue

                blocked_brand_bits = (
                    "apartments",
                    "apartment",
                    "rentals",
                    "rental",
                    "residence",
                    "residences",
                )
                if any(b in name_l for b in blocked_brand_bits) or any(
                    b in email_l for b in blocked_brand_bits
                ):
                    log(
                        run_id,
                        f"Skipped {href} — apartments/rentals/residence keyword in seller name/email.",
                    )
                    continue

                if soup.select_one("img[data-testid='profile-logo']"):
                    log(run_id, f"Skipped {href} — corporate profile logo present.")
                    continue

                # --- phone ---
                phone_number = find_phone_from_reveal(soup)
                if not phone_number:
                    phone_number = find_phone_in_description(description)

                if not phone_number:
                    log(
                        run_id,
                        f"Skipped {href} — no valid phone (tel: or +1 / 10-digit number in description).",
                    )
                    continue

                asking_price = ""
                ptag = soup.select_one("p[data-testid='vip-price']")
                if ptag:
                    asking_price = ptag.get_text(strip=True)

                unit_address = ""
                city = ""
                addr_btn = soup.select_one(
                    "div.sc-eb45309b-0.bEMmoW button.sc-c8742e84-0.fukShK"
                )
                if addr_btn:
                    full = addr_btn.get_text(strip=True)
                    unit_address = full
                    parts = [p.strip() for p in full.split(",")]
                    if len(parts) >= 2:
                        city = parts[-2]

                available_date = extract_available_date_from_details(soup)

                # dedupe on SAVE, but still visited
                if href in saved_listing_urls:
                    log(
                        run_id,
                        f"Already saved lead for {href} earlier in this run; skipping duplicate save.",
                    )
                    continue

                row = {
                    "Mobile": phone_number,
                    "Email": email,
                    "City": city,
                    "Prospect Namekijiji": prospect_name,
                    "Phone": phone_number,
                    "Prospect Source": "Kijiji",
                    "Unit Address": unit_address,
                    "Available Date": available_date,
                    "City of Unit": city,
                    "Asking Price": asking_price,
                    "Listing Profile URL": href,
                    "Profile URL": profile_url,
                }
                for k, v in row.items():
                    row[k] = "" if v is None else str(v).replace("\n", " ").strip()

                RUNS[run_id]["results"].append(row)
                saved_listing_urls.add(href)
                scraped_count += 1
                RUNS[run_id]["count"] = scraped_count
                log(run_id, f"Saved {scraped_count}/{params.max_listings}")

            page_saved = scraped_count - page_saved_before
            log(
                run_id,
                f"Page {page} summary: {processed_on_page} listing URLs processed on this page, "
                f"{page_saved} leads saved on this page.",
            )

            # sync count to DB occasionally
            update_run_status_in_db(run_id, count=scraped_count)

            if scraped_count >= params.max_listings:
                log(run_id, "Reached max_listings.")
                break

            next_url = find_next_page_url(current_url, search_html)
            if not next_url:
                log(run_id, "No next page detected. Done.")
                break
            if next_url == current_url:
                log(run_id, "Next URL equals current URL (loop guard). Done.")
                break

            current_url = next_url
            page += 1
            # chill between pages
            time.sleep(3.0 + random.uniform(0.5, 1.5))

        RUNS[run_id]["status"] = "succeeded"
        RUNS[run_id]["finished_at"] = datetime.utcnow()

        # persist final status & CSV to DB
        persist_run_csv_to_db(run_id)

    except Exception as e:
        RUNS[run_id]["status"] = "failed"
        RUNS[run_id]["finished_at"] = datetime.utcnow()
        RUNS[run_id]["logs"].append(f"ERROR: {e}")
        # mark failed in DB
        try:
            update_run_status_in_db(
                run_id,
                status="failed",
                count=RUNS[run_id].get("count", 0),
                set_finished=True,
            )
        except Exception:
            pass


# ---------------- FastAPI endpoints ----------------
@app.post("/scrape", response_model=StartResponse)
def start_scrape(payload: ScrapeParams, bg: BackgroundTasks):
    run_id = str(uuid.uuid4())
    RUNS[run_id] = {
        "status": "queued",
        "params": payload.dict(),
        "results": [],
        "logs": [],
        "count": 0,
        "started_at": None,
        "finished_at": None,
    }

    # if scrape_runs row already exists, keep DB in sync with queued status
    try:
        update_run_status_in_db(run_id, status="queued", count=0)
    except Exception:
        pass

    bg.add_task(run_scrape, run_id, payload)
    return StartResponse(run_id=run_id, status="queued")


@app.get("/", include_in_schema=False)
def root_redirect():
    return RedirectResponse(url="/docs")


@app.get("/health", include_in_schema=False)
def health():
    return JSONResponse({"status": "ok"})


@app.post("/scrapes.start", response_model=StartResponse)
def scrapes_start(payload: ScrapeParams, bg: BackgroundTasks):
    return start_scrape(payload, bg)


@app.get("/runs/{run_id}", response_model=RunStatus)
def get_status(run_id: str):
    run = RUNS.get(run_id)
    if run:
        return RunStatus(
            run_id=run_id,
            status=run["status"],
            count=run["count"],
            started_at=run["started_at"],
            finished_at=run["finished_at"],
            logs=run["logs"][-200:],
        )

    # fallback to DB if process has been restarted
    row = db_fetchone(
        "SELECT status, count, started_at, finished_at FROM scrape_runs WHERE id = %s",
        (run_id,),
    )
    if not row:
        raise HTTPException(404, "run not found")

    status, count, started_at, finished_at = row
    return RunStatus(
        run_id=run_id,
        status=status or "unknown",
        count=count or 0,
        started_at=started_at,
        finished_at=finished_at,
        logs=[],
    )


@app.get("/runs/{run_id}/results")
def get_results(run_id: str):
    run = RUNS.get(run_id)
    if not run:
        raise HTTPException(404, "run not found")
    return {
        "run_id": run_id,
        "status": run["status"],
        "count": run["count"],
        "results": run["results"],
    }


@app.get("/runs/{run_id}/export.csv")
def export_csv(run_id: str):
    run = RUNS.get(run_id)

    # 1) If this process still has results in memory, export them
    if run and run["results"]:
        fieldnames = list(run["results"][0].keys())
        buf = io.StringIO()
        writer = csv.DictWriter(buf, fieldnames=fieldnames, quoting=csv.QUOTE_ALL)
        writer.writeheader()
        for row in run["results"]:
            writer.writerow(row)
        buf.seek(0)
        return StreamingResponse(
            buf,
            media_type="text/csv",
            headers={"Content-Disposition": f'attachment; filename="{run_id}.csv"'},
        )

    # 2) Otherwise, fall back to csv_data stored in DB
    row = db_fetchone("SELECT csv_data FROM scrape_runs WHERE id = %s", (run_id,))
    if not row or not row[0]:
        raise HTTPException(404, "no results")

    csv_text = row[0]
    buf = io.StringIO(csv_text)
    return StreamingResponse(
        buf,
        media_type="text/csv",
        headers={"Content-Disposition": f'attachment; filename="{run_id}.csv"'},
    )
