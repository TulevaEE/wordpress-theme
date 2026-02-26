"""Upload all PDFs in a folder to the Tuleva WordPress media library.

Usage:
    python3 upload_docs.py [folder]

If folder is omitted, the script's own directory is used.

Required environment variables:
    WP_USERNAME      – WordPress username (e.g. sten.ehrlich@tuleva.ee)
    WP_APP_PASSWORD  – WordPress Application Password (spaces allowed)

Results are saved to upload_results.json in the same folder, including
attachment IDs needed by update_acf.py.
"""

import os
import sys
import re
import base64
import json
import requests

# ── credentials ──────────────────────────────────────────────────────────────
username = os.environ.get("WP_USERNAME")
app_password = os.environ.get("WP_APP_PASSWORD")

if not username:
    raise SystemExit("ERROR: WP_USERNAME environment variable is not set")
if not app_password:
    raise SystemExit("ERROR: WP_APP_PASSWORD environment variable is not set")

token = base64.b64encode(
    f"{username}:{app_password.replace(' ', '')}".encode()
).decode()
auth_header = {"Authorization": f"Basic {token}"}

# ── folder ────────────────────────────────────────────────────────────────────
folder = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else os.path.dirname(os.path.abspath(__file__)))
pdfs = sorted(f for f in os.listdir(folder) if f.lower().endswith(".pdf"))

if not pdfs:
    raise SystemExit(f"ERROR: No PDF files found in {folder}")

print(f"Found {len(pdfs)} PDF(s) in {folder}\n")

# ── sanitize filename for URL ─────────────────────────────────────────────────
def sanitize(name: str) -> str:
    base = os.path.splitext(name)[0]
    for ch, rep in [("õ","o"),("ä","a"),("ö","o"),("ü","u"),
                    ("Õ","O"),("Ä","A"),("Ö","O"),("Ü","U")]:
        base = base.replace(ch, rep)
    base = base.replace(" ", "-")
    base = re.sub(r"-+", "-", base)          # collapse consecutive dashes
    base = re.sub(r"[^A-Za-z0-9.\-]", "", base)  # strip anything else
    return base + ".pdf"

# ── upload ────────────────────────────────────────────────────────────────────
BASE_URL = "https://tuleva.ee/wp-json/wp/v2/media"
results = []

for local_name in pdfs:
    upload_name = sanitize(local_name)
    path = os.path.join(folder, local_name)
    print(f"Uploading: {upload_name} ...", end=" ", flush=True)
    with open(path, "rb") as f:
        resp = requests.post(
            BASE_URL,
            headers={
                **auth_header,
                "Content-Disposition": f'attachment; filename="{upload_name}"',
                "Content-Type": "application/pdf",
            },
            data=f,
        )
    if resp.status_code == 201:
        data = resp.json()
        print(f"OK  id={data['id']}  {data['source_url']}")
        results.append({
            "local": local_name,
            "upload_name": upload_name,
            "id": data["id"],
            "url": data["source_url"],
        })
    else:
        print(f"FAILED (HTTP {resp.status_code})")
        print(resp.text)

# ── summary ───────────────────────────────────────────────────────────────────
out_path = os.path.join(folder, "upload_results.json")
with open(out_path, "w", encoding="utf-8") as f:
    json.dump(results, f, indent=2, ensure_ascii=False)

print(f"\n--- {len(results)}/{len(pdfs)} uploaded successfully ---")
for r in results:
    print(f"  id={r['id']}  {r['url']}")
print(f"\nSaved to {out_path}")
