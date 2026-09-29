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
model portfolio, monthly investment report — plus the CO2 intensity figure published
beside them are each an ACF field on that fund's page. Setting the field is the whole
update: no commit, no deploy. Taking a document off the page is not, while its template
still carries a fallback URL — see *Fallbacks still in place*.

Field names are identical on every fund page that carries the field, so nothing
downstream branches per fund: fund → page slug, document → field name.

| Fund | Page ID | English page ID | Slug | Page template |
|---|---|---|---|---|
| TUK75 (Aktsiate Pensionifond) | 17533 | 17534 | `tuleva-maailma-aktsiate-pensionifond` | `page_fund-stocks.php` |
| TUK00 (Võlakirjade Pensionifond) | 17537 | 17538 | `tuleva-maailma-volakirjade-pensionifond` | `page_fund-bonds.php` |
| TUV100 (III Samba Pensionifond) | 20938 | 21395 | `tuleva-iii-samba-pensionifond` | `page_fund-third.php` |
| TKF100 (Täiendav Kogumisfond) | 35292 | 36156 | `tuleva-taiendav-kogumisfond-dokumendid` | `page_fund-savings.php` |

**Write to the Estonian page only.** The English page is a WPML translation of it and reads
every disclosure from the Estonian page (`tuleva_disclosure_source_post()`), so a value
written once shows in both languages. A value set on an English page is never read; the
fields are also WPML "copy" fields, so saving the Estonian page overwrites them.

`taiendav-kogumisfond` (37325) is TKF100's **landing** page. It renders no documents.

### Which documents a fund has

`helpers/acf/fund-disclosures.php` holds one catalogue of field definitions and one scope
table saying, per page template, whether each is required, optional, or absent. Absent
means it does not exist for that fund: no field in wp-admin, no key in the REST response,
nothing rendered, and no fallback to another fund's value.

- TKF100 is a UCITS fund and carries a summary of investor rights and its own NAV
  procedure; the pension funds carry neither and share one NAV procedure document.
- The pension funds carry `fund_co2_intensity`; TKF100 does not, because no CO2 intensity
  is calculated for it. The markup is there, so publishing one becomes a scope-table edit.

The catalogue covers the CO2 figure alongside the documents because it has the same
problem: published on the fund page, updated on a cadence, and not the same set for every
fund.

Adding or removing a disclosure for a fund is an edit to the scope table, not to a
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

Sustainability, non-consideration of adverse impacts and remuneration policy are one
document each for all four funds, and the three pension funds share one NAV procedure
(TKF100 has its own, as a field). They are still URL constants in `helpers/extras.php`, so
updating one is a commit and a deploy.

### Fallbacks still in place

Most per-fund documents also have a hardcoded URL in their template, which renders while
the field is empty. The pension pages' fields are all empty, so they show the code URLs.
On TKF100 the prospectus, terms, key information document, NAV procedure and upcoming
prospectus have a code URL; the model portfolio, summary of investor rights, investment
report, report archive, upcoming terms and upcoming NAV procedure do not — empty field, no
row.

The field wins over its literal, and an empty field falls back to it. So:

- **To change a document, set the field.** Once it is set, editing the literal changes
  nothing visible.
- **To take a document off the page, remove its literal.** Clearing the field brings the
  literal back, so wp-admin alone cannot do it. This is what promoting an upcoming document
  on its effective date runs into: the current field gets the new file, and the upcoming row
  has to go — a template edit wherever that row has a code URL.
- **A new document goes into the field, not into a new literal.** A literal added now is
  one more row that needs a deploy to retire.
- The three pension pages' `investment_report_file` is written by the monthly report job.
  Leave its literal alone.

Removing the fallbacks, moving the firm-wide documents onto the options page and
replacing `scripts/update_acf.py` with one manifest-driven publisher are steps in
`TODO — Fund page document publishing.md`, in the private tuleva repo under
`work/investeerimistegevus/docs/`.

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
