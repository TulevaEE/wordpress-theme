# Claude Code instructions — TulevaEE/wordpress-theme

> For fee updates see **[FEE-UPDATES.md](./FEE-UPDATES.md)**

## Project overview

This repo contains the WordPress theme for tuleva.ee. It deploys automatically:
**push to `master`** → CircleCI runs tests → rsync to production. The destination
is the CircleCI `DEPLOY_TARGET` env var (not in this repo) — the **full** rsync
target `user@host:/absolute/path/.../themes/tuleva/`, not just a host.

Theme templates live in:
`src/wp-content/themes/tuleva/templates/`

---

## Fund document updates (main recurring task)

Tuleva has four funds. Their documents — prospectus, terms, key investor information,
model portfolio, monthly investment report — are updated a few times per year, and each
one is an ACF field on that fund's page. Setting the field is the whole update: no
commit, no deploy.

Field names are identical on every fund page that carries the document, so nothing
downstream branches per fund: fund → page slug, document → field name.

| Fund | Page ID | Slug | Page template |
|---|---|---|---|
| TUK75 (Aktsiate Pensionifond) | 17533 | `tuleva-maailma-aktsiate-pensionifond` | `page_fund-stocks.php` |
| TUK00 (Võlakirjade Pensionifond) | 17537 | `tuleva-maailma-volakirjade-pensionifond` | `page_fund-bonds.php` |
| TUV100 (III Samba Pensionifond) | 20938 | `tuleva-iii-samba-pensionifond` | `page_fund-third.php` |
| TKF100 (Täiendav Kogumisfond) | 35292 | `tuleva-taiendav-kogumisfond-dokumendid` | `page_fund-savings.php` |

`taiendav-kogumisfond` (37325) is TKF100's **landing** page. It renders no documents.

### Which documents a fund has

`helpers/acf/fund-documents.php` holds one catalogue of document definitions and one
scope table saying, per page template, whether each document is required, optional, or
absent. Absent means the document does not exist for that fund: no field in wp-admin, no
key in the REST response, nothing rendered. TKF100 is a UCITS fund and carries a summary
of investor rights and its own NAV procedure; the pension funds carry neither and share
one NAV procedure document instead.

Adding or removing a document for a fund is an edit to the scope table, not to a
template.

### Updating a document

**From wp-admin:** open the fund page, set the field, Update. Live immediately.

**Over the REST API:**

```bash
# 1. upload the PDF (writes upload_results.json with the attachment IDs)
python3 scripts/upload_docs.py /path/to/folder/with/new/pdfs

# 2. point the field at it
curl -u "$WP_USERNAME:$WP_APP_PASSWORD" \
  -H 'Content-Type: application/json' \
  -d '{"acf": {"prospectus_file": <attachment id>}}' \
  https://tuleva.ee/wp-json/wp/v2/pages/17533
```

Local filenames use Estonian characters and spaces; `upload_docs.py` sanitises them
(`õäöü` → `oaou`, spaces → `-`), so `Põhiteave TUK75 - kehtib alates 27.02.2026.pdf`
uploads as `Pohiteave-TUK75-kehtib-alates-27.02.2026.pdf`.

**Keep the effective date in the filename.** For an upcoming document the page reads
`…alates-DD.MM.YYYY` out of the filename to label the row.

Verify the write: `GET /wp-json/wp/v2/pages/{id}` should come back with the new value
under `acf`, and the public page should show it. An `acf` key that is missing or empty in
the response means the write did not land — ACF returns 200 either way.

Required credentials, never hardcoded:

```
WP_USERNAME       WordPress username (email address)
WP_APP_PASSWORD   WordPress Application Password
                  Generate at: https://tuleva.ee/wp-admin → Users → Application Passwords
```

### Documents that are not per-fund

Sustainability, non-consideration of adverse impacts, remuneration policy and the pension
funds' NAV procedure are one document each for all four funds. They are still URL
constants in `helpers/extras.php`, so updating one is a commit and a deploy.

### Fallbacks still in place

Every per-fund document also has a hardcoded URL in its template, which renders while the
field is empty. The fields are not populated yet, so in practice the pages still show the
code URLs and a document change still needs a template edit.

Once a page's fields are set, **editing the literal changes nothing visible** — the field
wins. Update the field. Removing the fallbacks is a step in
[docs/TODO — Fund document automation.md](docs/TODO%20%E2%80%94%20Fund%20document%20automation.md),
along with moving the firm-wide documents onto the options page and replacing
`scripts/update_acf.py` with one manifest-driven publisher.

---

## Deployment

- CircleCI is triggered by every push to `master`
- It rsync-deploys changed files to `DEPLOY_TARGET` (CircleCI env var) — the full
  rsync destination `user@host:/absolute/path/.../themes/tuleva/`, not just a host
- The SSH key is configured in CircleCI, not in this repo

## Local development

See `README.md` for Docker setup instructions.

## Translations

After editing template strings, regenerate translation files:

```bash
./tools/i18n/generate-pot.sh
./tools/i18n/generate-po.sh
./tools/i18n/generate-mo.sh
```
