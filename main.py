import re, csv, time, uuid
from datetime import datetime
from typing import Dict, List, Optional
from urllib.parse import urlparse, parse_qs, urlencode, urlunparse

from fastapi import FastAPI, BackgroundTasks, HTTPException
from fastapi.responses import RedirectResponse, JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field

from bs4 import BeautifulSoup
import requests

# ---------------- In-memory state ----------------
RUNS: Dict[str, dict] = {}

app = FastAPI(title="Kijiji Scraper API", version="0.4.0")

# Allow requests from the main public site so agentbookr.com can call this service directly.
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

def fetch_html(run_id: str, url: str, tries: int = 3, delay: float = 1.0) -> str:
    last_err = None
    for i in range(tries):
        try:
            resp = SESSION.get(url, headers=HEADERS, timeout=15)
            if resp.status_code >= 400:
                raise RuntimeError(f"HTTP {resp.status_code}")
            return resp.text
        except Exception as e:
            last_err = e
            log(run_id, f"fetch_html error on {url}: {e}")
            time.sleep(delay * (i + 1))
    raise last_err if last_err else RuntimeError("fetch failed")

def get_listing_links_from_html(html: str) -> List[str]:
    soup = BeautifulSoup(html, 'html.parser')
    hrefs = set()
    for a in soup.select('a[data-testid="listing-link"]'):
        href = a.get("href")
        if href:
            hrefs.add(href)
    for a in soup.select('a[href*="/v-apartments-condos/"], a[href*="/v-real-estate/"]'):
        href = a.get("href")
        if href:
            hrefs.add(href)
    abs_hrefs = []
    for href in hrefs:
        if not href.startswith("http"):
            href = "https://www.kijiji.ca" + href
        abs_hrefs.append(href)
    return list(dict.fromkeys(abs_hrefs))

def find_next_page_url(current_url: str, page_html: str) -> Optional[str]:
    soup = BeautifulSoup(page_html, 'html.parser')
    link = soup.find("link", rel=lambda v: v and "next" in v.lower())
    if link and link.get("href"):
        href = link["href"]
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"
    a = soup.find("a", attrs={"aria-label": re.compile(r"^\s*Next\s*$", re.I)})
    if a and a.get("href"):
        href = a["href"]
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"
    a2 = soup.find("a", string=re.compile(r"^\s*Next\s*$", re.I))
    if a2 and a2.get("href"):
        href = a2["href"]
        return href if href.startswith("http") else f"https://www.kijiji.ca{href}"
    parsed = urlparse(current_url)
    q = parse_qs(parsed.query)
    cur = 1
    if "page" in q and q["page"]:
        try:
            cur = int(q["page"][0])
        except Exception:
            cur = 1
    q["page"] = [str(cur + 1)]
    new_qs = urlencode({k: v[0] if isinstance(v, list) else v for k, v in q.items()})
    next_url = urlunparse(
        (parsed.scheme, parsed.netloc, parsed.path, parsed.params, new_qs, parsed.fragment)
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
        cand = f"+{digits}"
    elif len(digits) == 10:
        cand = f"+1{digits}"
    else:
        return ""
    return cand if is_valid_nanp(cand) else ""

def normalize_tel_href_or_text(raw: str) -> str:
    if not raw:
        return ""
    if raw.lower().startswith("tel:"):
        raw = raw[4:]
    return to_e164_from_digits(raw)

def find_phone_from_reveal(soup: BeautifulSoup) -> str:
    # Here "reveal" just means any tel: link or visible phone text in HTML.
    a = soup.find('a', href=ANY_TEL)
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

# ----------- Seller (username) helpers -----------
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

# ----------- Availability helpers -----------
AVAILABLE_P_RE = re.compile(r"^\s*Available\b", re.I)

def extract_available_date_from_details(soup: BeautifulSoup) -> str:
    p = soup.find("p", string=AVAILABLE_P_RE)
    if not p:
        return ""
    txt = p.get_text(strip=True)
    m = re.search(r"Available\s*(.*)$", txt, re.I)
    return (m.group(1).strip() if m else txt)

# ---------------- The scraper ----------------
def run_scrape(run_id: str, params: ScrapeParams):
    RUNS[run_id].update(
        status="running", started_at=datetime.utcnow(), results=[], count=0
    )
    try:
        log(
            run_id,
            f"DEBUG: run_scrape start base_url={params.base_url} max_listings={params.max_listings}",
        )

        # Realtor / property-management filter.
        # Applied ONLY to description + seller name + listing title, not whole page HTML.
        realtor_filter = re.compile(
            r"""
              \bmgmt\b|
              \bproperty\s*management\b|
              \bmanagement\s+company\b|
              \bproperty\s*manager(s)?\b|
              \brealty\b|
              \brealt(or|ors)\b|
              \breal\s*estate\s+(agent|broker|brokerage|team)\b|
              \bbroker(age)?\b|
              \bleasing\s*agent\b|
              \blisting\s*agent\b|
              \b(rental|rentals?)\s+agency\b|
              \brentalagency\b|
              \brentals?\s+(inc|ltd|corp|company|management|mgmt|realty|group)\b|
              \bmls\b|
              \bthird\s*party\b|
              \bthird\s*parties\b|
              \bsublease\b|
              \bdeferral\b
            """,
            re.IGNORECASE | re.VERBOSE,
        )

        current_url = params.base_url
        scraped_urls = set()
        scraped_count = 0
        page = 1

        while scraped_count < params.max_listings:
            log(run_id, f"Visiting search page {page}: {current_url}")
            search_html = fetch_html(run_id, current_url)
            listing_links = get_listing_links_from_html(search_html)
            if not listing_links:
                log(run_id, "No listing anchors found on this page.")

            for href in listing_links:
                if scraped_count >= params.max_listings:
                    break
                if href in scraped_urls:
                    continue
                scraped_urls.add(href)

                try:
                    detail_html = fetch_html(run_id, href)
                    # be a tiny bit polite
                    time.sleep(0.7)
                except Exception as e:
                    log(run_id, f"Skipped {href} — failed to open ({e})")
                    continue

                soup = BeautifulSoup(detail_html, "html.parser")

                # Description + seller + title (for realtor filtering)
                description = extract_description_text(soup)
                prospect_name, profile_url = extract_seller_info(soup)
                title_el = soup.select_one("h1[data-testid='vip-title']") or soup.find("h1")
                title_text = title_el.get_text(" ", strip=True) if title_el else ""

                filter_text = " ".join(
                    t for t in [description, prospect_name, title_text] if t
                )

                if realtor_filter.search(filter_text):
                    log(run_id, f"Skipped {href} — realtor / management keywords.")
                    continue

                # PHONE: tel: link or +1 in description / page text
                phone_number = find_phone_from_reveal(soup)
                if not phone_number:
                    phone_number = find_phone_in_description(description)

                if not phone_number:
                    log(
                        run_id,
                        f"Skipped {href} — no valid phone (tel: or +1 in description).",
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

                emails = re.findall(
                    r"[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+",
                    description or "",
                )
                email = emails[0] if emails else ""

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
                scraped_count += 1
                RUNS[run_id]["count"] = scraped_count
                log(run_id, f"Saved {scraped_count}/{params.max_listings}")

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

        RUNS[run_id]["status"] = "succeeded"
        RUNS[run_id]["finished_at"] = datetime.utcnow()

    except Exception as e:
        RUNS[run_id]["status"] = "failed"
        RUNS[run_id]["finished_at"] = datetime.utcnow()
        RUNS[run_id]["logs"].append(f"ERROR: {e}")

# ---------------- FastAPI endpoints ----------------
@app.post("/scrape", response_model=StartResponse)
def start_scrape(payload: ScrapeParams, bg: BackgroundTasks):
    run_id = str(uuid.uuid4())
    RUNS[run_id] = {
        "status": "queued",
        "params": payload.dict(),  # pydantic v1/v2 compatible
        "results": [],
        "logs": [],
        "count": 0,
        "started_at": None,
        "finished_at": None,
    }
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
    if not run:
        raise HTTPException(404, "run not found")
    return RunStatus(
        run_id=run_id,
        status=run["status"],
        count=run["count"],
        started_at=run["started_at"],
        finished_at=run["finished_at"],
        logs=run["logs"][-200:],
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
    if not run:
        raise HTTPException(404, "run not found")
    import io
    if not run["results"]:
        return {"message": "no results"}
    fieldnames = list(RUNS[run_id]["results"][0].keys())
    buf = io.StringIO()
    writer = csv.DictWriter(buf, fieldnames=fieldnames, quoting=csv.QUOTE_ALL)
    writer.writeheader()
    for row in RUNS[run_id]["results"]:
        writer.writerow(row)
    from fastapi.responses import StreamingResponse
    buf.seek(0)
    return StreamingResponse(
        buf,
        media_type="text/csv",
        headers={"Content-Disposition": f'attachment; filename=\"{run_id}.csv\"'},
    )
