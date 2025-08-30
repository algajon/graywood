import re, csv, time, uuid
from datetime import datetime
from typing import Dict, List, Optional

from fastapi import FastAPI, BackgroundTasks, HTTPException
from pydantic import BaseModel, Field

from splinter import Browser
from bs4 import BeautifulSoup
from selenium.webdriver.chrome.options import Options  # ← NEW

# ---------------- In-memory state (swap to Redis/DB in prod) ----------------
RUNS: Dict[str, dict] = {}

app = FastAPI(title="Kijiji Scraper API", version="0.1.0")

# ---------------- Models ----------------
class ScrapeParams(BaseModel):
    base_url: str
    max_listings: int = Field(ge=1, le=1000, default=50)

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

def wait_for_dom(browser, css_selector="body", timeout=10):
    try:
        browser.is_element_present_by_css(css_selector, wait_time=timeout)
    except Exception:
        pass

def new_browser() -> Browser:
    """Launch Chrome like a human: headless=new, full HD viewport, real UA, anti-automation flags."""
    opts = Options()
    # ---- A) CHANGES ----
    opts.add_argument("--headless=new")
    opts.add_argument("--window-size=1920,1080")
    opts.add_argument("--no-sandbox")
    opts.add_argument("--disable-dev-shm-usage")
    opts.add_argument("--disable-gpu")
    opts.add_argument("--lang=en-US")
    opts.add_argument("--disable-blink-features=AutomationControlled")
    opts.add_argument("--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
                      "AppleWebKit/537.36 (KHTML, like Gecko) "
                      "Chrome/123.0.0.0 Safari/537.36")
    # --------------------
    return Browser('chrome', options=opts)

# ---------------- The scraper (your logic wrapped) ----------------
def run_scrape(run_id: str, params: ScrapeParams):
    RUNS[run_id].update(
        status="running", started_at=datetime.utcnow(), results=[], count=0
    )
    try:
        realtor_filter = re.compile(
            r"MGMT|Property\s?Management|deferral|sublease|free|property\s?manager|realty|mls|third\s?party|third\s?parties",
            re.IGNORECASE,
        )

        browser = new_browser()
        current_url = params.base_url
        scraped_urls = set()
        scraped_count = 0
        page = 1

        def get_listing_links():
            soup = BeautifulSoup(browser.html, 'html.parser')
            links = soup.find_all('a', {'data-testid': 'listing-link'})
            hrefs = []
            for link in links:
                href = link.get('href')
                if href and not href.startswith('http'):
                    href = "https://www.kijiji.ca" + href
                if href and href not in scraped_urls:
                    hrefs.append(href)
            return hrefs

        while scraped_count < params.max_listings:
            log(run_id, f"Page {page}")
            browser.visit(current_url)
            wait_for_dom(browser)
            browser.execute_script("window.scrollTo(0, document.body.scrollHeight * 0.90);")
            wait_for_dom(browser)

            listing_links = get_listing_links()
            if not listing_links:
                log(run_id, "No listings found on page.")
                break

            listings_this_page = 0

            for href in listing_links:
                if scraped_count >= params.max_listings:
                    break
                if href in scraped_urls:
                    continue
                scraped_urls.add(href)

                browser.visit(href)
                wait_for_dom(browser, "h1")

                # Skip profile logo image listings
                if browser.is_element_present_by_css("div.sc-cb215484-4.fUsrdg img[data-testid='profile-logo']", wait_time=0.5):
                    log(run_id, f"Skipped {href} — profile logo image.")
                    continue

                page_html = browser.html
                listing_page = BeautifulSoup(page_html, 'html.parser')
                page_text = listing_page.get_text(" ", strip=True)

                fav_logo_section = listing_page.find('div', class_='sc-b8ce2579-19')
                if fav_logo_section and fav_logo_section.find('img'):
                    log(run_id, f"Skipped {href} — image in logo section.")
                    continue

                desc_div = listing_page.find('div', {'data-testid': 'vip-description-wrapper'})
                description = desc_div.get_text(" ", strip=True) if desc_div else ''
                if realtor_filter.search(description):
                    log(run_id, f"Skipped {href} — realtor keywords.")
                    continue

                prospect_name = "Kijiji User"
                profile_tag = listing_page.select_one("h3.sc-9d9a3b6-0.fAJNmV a.sc-683826d7-2.cPzJWd")
                if profile_tag:
                    name_text = profile_tag.get_text(strip=True)
                    if name_text:
                        prospect_name = name_text

                profile_url = ""
                for a_tag in listing_page.find_all("a", href=True):
                    if re.match(r"^/(o-profile|o-kijiji-user|o-[a-z0-9\-]+)/", a_tag['href']):
                        profile_url = "https://www.kijiji.ca" + a_tag['href']
                        break

                emails = re.findall(r"[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+", page_text)
                email = emails[0] if emails else ""

                # Reveal phone
                phone_number = ""
                try:
                    reveal_button = browser.find_by_xpath("//p[@aria-label='Reveal phone number']")
                    if reveal_button:
                        reveal_button.click()
                        log(run_id, "Clicked Reveal button.")
                        time.sleep(2)  # let tel: render
                except Exception as e:
                    log(run_id, f"Reveal button not found or failed: {e}")

                post_reveal_html = browser.html
                post_reveal_page = BeautifulSoup(post_reveal_html, 'html.parser')
                tel_link = post_reveal_page.find('a', href=re.compile(r'^tel:'))
                if tel_link:
                    phone_number = tel_link.get_text(strip=True)
                else:
                    # (Optional) keep regex fallback – but we will SKIP if still empty
                    phone_matches = re.findall(r"(?:\+?1[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}", page_text)
                    if phone_matches:
                        phone_number = phone_matches[0]

                # ---- NEW: skip listing entirely if no phone ----
                if not phone_number:
                    log(run_id, f"Skipped {href} — no phone number present.")
                    continue

                ad_id = ""
                ad_id_tag = listing_page.select_one("li.sc-eb45309b-0.jqlARl p[aria-current='location']")
                if ad_id_tag:
                    text = ad_id_tag.get_text(strip=True)
                    match = re.search(r"Ad ID\s+(\d+)", text)
                    if match:
                        ad_id = match.group(1)

                asking_price = ""
                price_tag = listing_page.select_one("p[data-testid='vip-price']")
                if price_tag:
                    asking_price = price_tag.get_text(strip=True)

                unit_address = ""
                city = ""
                address_button = listing_page.select_one("div.sc-eb45309b-0.bEMmoW button.sc-c8742e84-0.fukShK")
                if address_button:
                    full_address = address_button.get_text(strip=True)
                    unit_address = full_address
                    parts = [p.strip() for p in full_address.split(',')]
                    if len(parts) >= 2:
                        city = parts[-2]

                available_date = ""
                date_match = re.search(r"(\d{1,2}/\d{1,2}/\d{4})", page_text)
                if date_match:
                    available_date = date_match.group(1)

                row = {
                    "Mobile": phone_number or "",
                    "Email": email or "",
                    "City": city or "",
                    "Social Lead ID": ad_id or "",
                    "Prospect Namekijiji": prospect_name or "",
                    "Phone": phone_number or "",
                    "Prospect Source": "Kijiji",
                    "Assigned to": "User",
                    "Unit Address": unit_address or "",
                    "Available Date": available_date or "",
                    "Ad ID - Listing": ad_id or "",
                    "City of Unit": city or "",
                    "Asking Price": asking_price or "",
                    "Listing Profile URL": href,
                    "Profile URL": profile_url or "",
                }

                # sanitize newlines/None
                for k, v in row.items():
                    row[k] = "" if v is None else str(v).replace("\n", " ").strip()

                RUNS[run_id]["results"].append(row)
                scraped_count += 1
                listings_this_page += 1
                RUNS[run_id]["count"] = scraped_count
                log(run_id, f"Saved {scraped_count}/{params.max_listings}")

            # Try find Next
            if listings_this_page == 0:
                log(run_id, "No new listings scraped on this page.")
                break

            try:
                browser.visit(current_url)
                wait_for_dom(browser)
                browser.execute_script("window.scrollTo(0, document.body.scrollHeight * 0.90);")
                wait_for_dom(browser)
                soup = BeautifulSoup(browser.html, 'html.parser')
                next_link_tag = soup.find('a', string=re.compile(r'Next', re.IGNORECASE))
                if next_link_tag and next_link_tag.get('href'):
                    next_url = next_link_tag['href']
                    current_url = next_url if next_url.startswith('http') else f"https://www.kijiji.ca{next_url}"
                    log(run_id, f"Going to next page: {current_url}")
                    page += 1
                    continue
                else:
                    log(run_id, "No Next button found. Done.")
                    break
            except Exception as e:
                log(run_id, f"Error clicking Next: {e}")
                break

        browser.quit()
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
        "params": payload.model_dump(),
        "results": [],
        "logs": [],
        "count": 0,
        "started_at": None,
        "finished_at": None,
    }
    bg.add_task(run_scrape, run_id, payload)
    return StartResponse(run_id=run_id, status="queued")

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
        logs=run["logs"][-50:],
    )

@app.get("/runs/{run_id}/results")
def get_results(run_id: str):
    run = RUNS.get(run_id)
    if not run:
        raise HTTPException(404, "run not found")
    return {"run_id": run_id, "status": run["status"], "count": run["count"], "results": run["results"]}

@app.get("/runs/{run_id}/export.csv")
def export_csv(run_id: str):
    run = RUNS.get(run_id)
    if not run:
        raise HTTPException(404, "run not found")
    # Simple CSV in-memory
    import io
    if not run["results"]:
        return {"message": "no results"}
    fieldnames = list(run["results"][0].keys())
    buf = io.StringIO()
    writer = csv.DictWriter(buf, fieldnames=fieldnames, quoting=csv.QUOTE_ALL)
    writer.writeheader()
    for row in run["results"]:
        writer.writerow(row)
    from fastapi.responses import StreamingResponse
    buf.seek(0)
    return StreamingResponse(buf, media_type="text/csv", headers={"Content-Disposition": f'attachment; filename="{run_id}.csv"'})
