"""Create (or update) a Tuleva WordPress page that renders a theme page template.

A theme template like page_company-savings.php is invisible until a WordPress
page selects it. This does that over the REST API instead of by hand in wp-admin.

Usage:
    python3 publish_page.py --title "Osaühingule kogumine" \
        --slug osauhingule-kogumine \
        --template page_company-savings.php \
        [--image /path/to/share.jpg] [--publish]

Creates the page as a draft by default. Pass --publish to publish immediately.
Re-running with the same --slug updates the existing page instead of making a
duplicate, so it is safe to run twice.

--image uploads the file and sets it as the featured image. The template does
not render a featured image, but Yoast uses it as the og:image fallback, which
is what gives shared links a preview card.

Required environment variables:
    WP_USERNAME      – WordPress username (email address)
    WP_APP_PASSWORD  – WordPress Application Password
                       https://tuleva.ee/wp-admin → Users → Application Passwords

Requires: pip install requests
"""

import argparse
import base64
import mimetypes
import os
import sys

import requests

API_BASE = "https://tuleva.ee/wp-json/wp/v2"


def auth_header():
    username = os.environ.get("WP_USERNAME")
    app_password = os.environ.get("WP_APP_PASSWORD")
    if not username:
        raise SystemExit("ERROR: WP_USERNAME environment variable is not set")
    if not app_password:
        raise SystemExit("ERROR: WP_APP_PASSWORD environment variable is not set")
    token = base64.b64encode(
        f"{username}:{app_password.replace(' ', '')}".encode()
    ).decode()
    return {"Authorization": f"Basic {token}"}


def find_page_by_slug(headers, slug):
    """An existing page with this slug, in any status, or None."""
    resp = requests.get(
        f"{API_BASE}/pages",
        headers=headers,
        params={"slug": slug, "status": "publish,draft,pending,private", "per_page": 1},
        timeout=30,
    )
    resp.raise_for_status()
    results = resp.json()
    return results[0] if results else None


def available_templates(headers):
    """Template filenames the theme offers, read off the pages endpoint schema."""
    resp = requests.options(f"{API_BASE}/pages", headers=headers, timeout=30)
    resp.raise_for_status()
    schema = resp.json()
    endpoints = schema.get("endpoints") or [{}]
    args = endpoints[0].get("args", {})
    return args.get("template", {}).get("enum", [])


def upload_media(headers, path):
    mime_type = mimetypes.guess_type(path)[0] or "application/octet-stream"
    filename = os.path.basename(path)
    with open(path, "rb") as handle:
        resp = requests.post(
            f"{API_BASE}/media",
            headers={
                **headers,
                "Content-Disposition": f"attachment; filename={filename}",
                "Content-Type": mime_type,
            },
            data=handle.read(),
            timeout=120,
        )
    if resp.status_code != 201:
        raise SystemExit(f"ERROR: image upload failed ({resp.status_code}): {resp.text}")
    media = resp.json()
    print(f"  uploaded {filename} -> media ID {media['id']}")
    return media["id"]


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--title", required=True)
    parser.add_argument("--slug", required=True)
    parser.add_argument("--template", required=True, help="e.g. page_company-savings.php")
    parser.add_argument("--image", help="file to upload and set as the featured image")
    parser.add_argument(
        "--publish",
        action="store_true",
        help="publish immediately instead of creating a draft",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="check the template exists and report what would happen, change nothing",
    )
    args = parser.parse_args()

    headers = auth_header()

    # Fail before writing anything if the theme does not offer this template:
    # a page with an unknown template silently falls back to the default one.
    templates = available_templates(headers)
    if templates and args.template not in templates:
        print(f"ERROR: theme offers no template {args.template!r}. Available:")
        for name in templates:
            print(f"  {name}")
        sys.exit(1)
    print(f"template {args.template} is available")

    existing = find_page_by_slug(headers, args.slug)
    if existing:
        print(
            f"page /{args.slug}/ already exists "
            f"(ID {existing['id']}, {existing['status']}) - will update"
        )
    else:
        print(f"page /{args.slug}/ does not exist - will create")

    status = "publish" if args.publish else "draft"

    if args.dry_run:
        print(f"\nDRY RUN - would set title={args.title!r}, status={status}")
        if args.image:
            print(f"DRY RUN - would upload {args.image} and set it as the featured image")
        return

    media_id = upload_media(headers, args.image) if args.image else None

    payload = {
        "title": args.title,
        "slug": args.slug,
        "template": args.template,
        "status": status,
    }
    if media_id:
        payload["featured_media"] = media_id

    if existing:
        resp = requests.post(
            f"{API_BASE}/pages/{existing['id']}", headers=headers, json=payload, timeout=60
        )
        expected = 200
    else:
        # The template renders the whole page; the editor body stays empty.
        payload["content"] = ""
        resp = requests.post(f"{API_BASE}/pages", headers=headers, json=payload, timeout=60)
        expected = 201

    if resp.status_code != expected:
        print(f"ERROR: {resp.status_code}\n{resp.text}")
        sys.exit(1)

    page = resp.json()
    print("\nPage saved.")
    print(f"  ID:       {page['id']}")
    print(f"  Status:   {page['status']}")
    print(f"  Template: {page['template']}")
    print(f"  URL:      {page['link']}")
    print(f"  Edit:     https://tuleva.ee/wp-admin/post.php?post={page['id']}&action=edit")
    if page["status"] != "publish":
        print("\nStill a draft. Re-run with --publish when you are happy with it.")


if __name__ == "__main__":
    main()
