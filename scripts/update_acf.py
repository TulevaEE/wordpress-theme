"""Update ACF fields for the Tuleva savings fund page (TKF100).

Reads upload_results.json written by upload_docs.py, finds the attachment
IDs for the TKF100 prospectus and key investor info files, then updates
the corresponding ACF fields on the savings fund page via the WP REST API.

Usage:
    python3 update_acf.py [folder]

If folder is omitted, the script's own directory is used.

Required environment variables:
    WP_USERNAME      – WordPress username
    WP_APP_PASSWORD  – WordPress Application Password (spaces allowed)
"""

import os
import sys
import base64
import json
import requests

# ── credentials ───────────────────────────────────────────────────────────────
username = os.environ.get("WP_USERNAME")
app_password = os.environ.get("WP_APP_PASSWORD")

if not username:
    raise SystemExit("ERROR: WP_USERNAME not set")
if not app_password:
    raise SystemExit("ERROR: WP_APP_PASSWORD not set")

token = base64.b64encode(
    f"{username}:{app_password.replace(' ', '')}".encode()
).decode()
auth_header = {
    "Authorization": f"Basic {token}",
    "Content-Type": "application/json",
}

BASE = "https://tuleva.ee/wp-json/wp/v2"

# ── load upload results ───────────────────────────────────────────────────────
folder = os.path.abspath(
    sys.argv[1] if len(sys.argv) > 1 else os.path.dirname(os.path.abspath(__file__))
)
results_path = os.path.join(folder, "upload_results.json")

if not os.path.exists(results_path):
    raise SystemExit(
        f"ERROR: {results_path} not found — run upload_docs.py first"
    )

with open(results_path, encoding="utf-8") as f:
    uploads = json.load(f)

# find TKF100 attachment IDs by matching upload_name prefix
prospectus_id = None
kii_id = None

for entry in uploads:
    name = entry["upload_name"]
    if name.startswith("TKF100-Prospekt"):
        prospectus_id = entry["id"]
    elif name.startswith("Pohiteave-TKF100"):
        kii_id = entry["id"]

if not prospectus_id or not kii_id:
    raise SystemExit(
        "ERROR: Could not find TKF100 attachment IDs in upload_results.json.\n"
        "Make sure upload_docs.py was run with the TKF100 files present."
    )

print(f"TKF100 prospectus  id={prospectus_id}")
print(f"TKF100 key info    id={kii_id}")

# ── find the savings fund page by slug ───────────────────────────────────────
PAGE_SLUG = "tuleva-taiendav-kogumisfond-dokumendid"
print(f"\nLooking up page slug '{PAGE_SLUG}' ...", end=" ", flush=True)

resp = requests.get(f"{BASE}/pages", params={"slug": PAGE_SLUG}, headers=auth_header)
resp.raise_for_status()
pages = resp.json()

if not pages:
    raise SystemExit(f"ERROR: No page found with slug '{PAGE_SLUG}'")

page_id = pages[0]["id"]
print(f"found page id={page_id}")

# ── update ACF fields ─────────────────────────────────────────────────────────
print("\nUpdating ACF fields ...", end=" ", flush=True)

payload = {
    "acf": {
        "prospectus_file": prospectus_id,
        "key_investor_info_file": kii_id,
    }
}

resp = requests.post(
    f"{BASE}/pages/{page_id}",
    headers=auth_header,
    data=json.dumps(payload),
)

if resp.status_code in (200, 201):
    acf = resp.json().get("acf", {})
    print("OK")
    print(f"  prospectus_file      = {acf.get('prospectus_file')}")
    print(f"  key_investor_info_file = {acf.get('key_investor_info_file')}")
else:
    print(f"FAILED (HTTP {resp.status_code})")
    print(resp.text)
