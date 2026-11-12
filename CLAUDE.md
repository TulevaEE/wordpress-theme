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

**Gaps.** A required document or figure whose field is empty, and a prospectus, terms or NAV
procedure (current or upcoming) whose filename carries no readable effective date, are a page's
gaps (`helpers/acf/fund-disclosure-gaps.php`). The page's edit screen lists them, and its REST
answer carries them as `tuleva_disclosure_gaps` (null on a page that is not a fund page), which
onboarding-service checks every morning and reports in Slack. A fund page's gaps include those of
the shared documents page that it shows, marked `"page": "shared"`. TKF100's investment report is
optional: it has no monthly report duty.

### Updating a document

**Through agent-gateway (`fund_pages`):** the normal route. A second person approves in
Slack, and the gateway writes the field over REST, reads it back and checks the page.

**From wp-admin:** only an Administrator can change a document field there. For anyone else
the fields are hidden, and their values are listed in the *Documents* box on the edit
screen; restoring a revision does not change them either, and WordPress's own Custom Fields
form cannot reach them (`is_protected_meta`). That includes
`investment_report_file`: the monthly report job writes it over REST, and which funds it
publishes is decided there.

**Who may write is decided by the account, not the route.** A write to a document field,
`delete_field()` and ACF's other deletions included, is taken from an Administrator, from
the upcoming-document switch while its WP-Cron hook (`tuleva_promote_due_documents`) runs,
and over REST from an account holding the capability `tuleva_write_fund_documents` that
signs the request with an application password. Anyone else's is refused on every route,
any other cron run included, whether from a logged-in session or the user's own
application password. An authenticated REST request is answered **403**
`tuleva_fund_document_locked`, naming the fields, and nothing in it is saved; one without a
valid credential gets WordPress's own 401. In wp-admin the value is not taken; a refused
deletion leaves the value and its ACF reference row in place.

The capability comes with the *Fund document writer* role, which may read, upload media and
update pages, private ones too (the shared documents page is private), and holds
`unfiltered_html` like every Editor: without it WordPress runs the stored page body and title
through kses on every update, even one that sets only fields, and strips any iframe,
script, form or SVG in them. So an application password of one of these accounts
can put any HTML or script on tuleva.ee: keep each only in its service's secret store,
never in a chat, a file or a shared sheet, and revoke it on the account's profile the moment
it may have leaked. The theme puts the role back the way it is defined in
`helpers/acf/fund-disclosure-log.php` on every request. Give it only to the accounts
agent-gateway, onboarding-service and the reports' Apps Script write as. Each account's
*Application Passwords* list on its profile shows when it was last used, which tells them
apart.

**Giving an account the capability**, and the order to do it in when this lock first
deploys (a push to `master` deploys it):

1. Before the deploy, if WP-CLI is available on the server:
   `wp user add-cap <login> tuleva_write_fund_documents` for each account. A capability is
   only a name, so this works before the theme knows it, and the account keeps its role.
2. After the deploy, in wp-admin → Users → the account → Role, choose *Fund document writer*
   (the role exists from the first request after the deploy). That takes away everything
   else the account could do as an Editor. Without WP-CLI this is the only step, and from
   the deploy until it is done each of those accounts' writes is answered 403.

If nobody does either, every `fund_pages` write and every monthly investment report
publication is answered 403 and changes nothing on the pages: the gateway reports the
request as refused, onboarding-service's publishing fails, and the Apps Script lists the
funds as failed. The pages keep the documents they had.

**Every write through ACF is logged** on the page (wp-admin, REST, WP-Cron), and so is a
revision restore: who, when, how, and the value before and after, a document with its URL.
An entry is written once WordPress has stored the value, so a write that fails is not
logged. If the log itself cannot be written, the change stays as stored and the PHP error
log gets one line: page, field, the values before and after, and the route. A restore is
logged from the writes it makes to the restored page, so a field it does not write gets no
entry, whatever another request writes to it meanwhile, and WPML's copy to the English
page during it is not logged either. An editor's revision restore leaves the document
fields out of ACF's copy, so it changes none of them.
The *Documents* box shows the last 50 (`helpers/acf/fund-disclosure-log.php`). A direct
post-meta write (WP-CLI, WPML's copy to a translation) is not logged, nor is a preview's or
an autosave's copy, which is written to a revision.

**Over the REST API:** only as an account holding `tuleva_write_fund_documents`, or an
Administrator; an Editor's write is answered 403.

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
`…alates-DD.MM.YYYY` out of the filename to label the row, and switches on that date.

### Upcoming documents switch themselves

From the date in its filename an upcoming prospectus, terms or NAV procedure renders as the
current document and its upcoming row goes (`tuleva_document_versions()`); an hourly WP-Cron
job then moves it into the current field and empties the upcoming one
(`tuleva_promote_due_documents()`). Nothing is done on the day: the switch was approved when
the upcoming document was set. A document is scheduled only in its upcoming field: no template
carries an upcoming URL. The fields go over the template's current URL (`tuleva_fund_document()`),
and a filled field beats it whatever the dates: an upcoming field takes over on its date even if
the template's document is newer, and the job then moves it into the current field, empty or not,
so the page does not change when it runs. That includes a date already past: an upcoming field
holding a document older than the one on the page replaces it at once and is promoted within the
hour. Put only a document not yet in force into an upcoming field. Between two fields, an
upcoming document dated on or before the current one is dropped instead of promoted, and the job
empties its field. The job empties an upcoming field only after the current field reads back as
the promoted document, and only while it still holds the document the job read (one saved there
meanwhile stays).

**Before this deploys**, these upcoming fields on the Estonian pages are filled, through
agent-gateway's `fund_pages` or by an Administrator in wp-admin, with the attachments the URLs
already point at (pick them in the media library; uploading again gives a `-1` name and a new
link). They were the templates' upcoming URLs, after `get_site_url()`, and
the templates no longer carry them, so without the fields the 01.01.2027 and 06.12.2026 documents
never show.
On master a filled upcoming field already wins over the URL (`helpers/acf/fund-disclosures.php:252`,
called from `fund-stocks-details.php:21-22`, `fund-bonds-details.php:21-22` and
`fund-third-details.php:21-22`), so filling them early changes nothing visible. TKF100 had no
upcoming URL; TUV100's came with #151.

| Page | Field | Document |
|---|---|---|
| TUK75, 17533 `tuleva-maailma-aktsiate-pensionifond` | `prospectus_upcoming_file` | `/wp-content/uploads/2026/08/TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf` |
| TUK75, 17533 `tuleva-maailma-aktsiate-pensionifond` | `terms_upcoming_file` | `/wp-content/uploads/2026/08/TUK75-tingimused-kehtivad-alates-01.01.2027-1.pdf` |
| TUK00, 17537 `tuleva-maailma-volakirjade-pensionifond` | `prospectus_upcoming_file` | `/wp-content/uploads/2026/08/TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf` |
| TUK00, 17537 `tuleva-maailma-volakirjade-pensionifond` | `terms_upcoming_file` | `/wp-content/uploads/2026/08/TUK00-tingimused-kehtivad-alates-01.01.2027.pdf` |
| TUV100, 20938 `tuleva-iii-samba-pensionifond` | `prospectus_upcoming_file` | `/wp-content/uploads/2026/11/TUV100-Prospekt-kehtib-alates-06.12.2026.pdf` |
| TUV100, 20938 `tuleva-iii-samba-pensionifond` | `terms_upcoming_file` | `/wp-content/uploads/2026/11/TUV100-tingimused-kehtivad-alates-06.12.2026.pdf` |

Verify the write: `GET /wp-json/wp/v2/pages/{id}` should come back with the new value
under `acf`, and the public page should show it. A 403 `tuleva_fund_document_locked` means
the account signed in but may not set those fields, and nothing was saved; a 401 means the
credential was not accepted. An `acf` key that is missing or
empty in a 200 response means the write did not land: ACF answers 200 when it drops a field
it does not expose over REST.

Required credentials, never hardcoded:

```
WP_USERNAME       WordPress username (email address)
WP_APP_PASSWORD   WordPress Application Password
                  Generate at: https://tuleva.ee/wp-admin → Users → Application Passwords
```

### Documents that are not per-fund

Sustainability, non-consideration of adverse impacts and remuneration policy are one
document each for all four funds, and the three pension funds share one NAV procedure
(TKF100 has its own, as a field). They show on the fund pages only. Their fields are held by one
private page that is never shown, on the template *Fund shared documents*
(`page_fund-shared-documents.php`): `sustainability_risks_file`, `adverse_impacts_file`,
`remuneration_policy_file`, and `nav_procedure_file` plus `nav_procedure_upcoming_file` for the
pension funds' procedure, which switches on its date like a fund's. The helpers in
`helpers/extras.php` read them from there (`tuleva_shared_document_url()`), so a fund page shows a
new one with no deploy. Write it as a fund page is written: the Estonian page, over REST.

Create it as a new page, not as a copy of a fund page: a copy carries that fund's field values,
and a copied TKF100 NAV procedure would show on the pension pages. The theme saves a page on that
template as **Private** whatever status it is given (a draft would not be read), and the template
answers 404 to everyone. The theme finds the page by its template; one page is meant to be on it.
Until it is, and while one of its fields is empty, the URL in `helpers/extras.php` renders
instead; a due upcoming NAV procedure beats that URL whatever the dates, as on a fund page. If
there is more than one page on the template, the edit screens of the fund pages and of the shared
page say so. To change nothing visible when first filling it, pick the attachments the URLs
already point at; uploading the same PDF again gives it a `-1` name and a new link.

### Fallbacks still in place

Most per-fund documents also have a hardcoded URL in their template, which renders while
the field is empty. The pension pages' current fields are empty, so they show the code URLs;
no upcoming document has one.
On TKF100 the prospectus, terms, key information document and NAV procedure have a code
URL; the model portfolio, summary of investor rights, investment report, report archive and
the three upcoming documents do not — empty field, no row.

The field wins over its literal, and an empty field falls back to it. So:

- **To change a document, set the field.** Once it is set, editing the literal changes
  nothing visible.
- **To take a document off the page, remove its literal.** Clearing the field brings the
  literal back, so wp-admin alone cannot do it. Promoting an upcoming document is not this
  case: it switches itself on its date (see *Upcoming documents switch themselves*).
- **A new document goes into the field, not into a new literal.** A literal added now is
  one more row that needs a deploy to retire.
- The three pension pages' `investment_report_file` is written by the monthly report job.
  Leave its literal alone.

Removing the fallbacks and replacing `scripts/update_acf.py` with one manifest-driven publisher are steps in
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

## Building CSS

`css/main.css` is committed as the **expanded** build from `npm run watch:scss`
(`gulp --development`). Never run `gulp production`: it minifies every CSS file in
`css/` (bootstrap, editor, vendor) and turns a small change into a ~60 000-line diff.

For a one-off build without keeping a terminal open: start `npm run watch:scss` in the
background, touch the `.scss` file you changed, wait for `Sourcemaps task finished`,
then stop the watcher. Only `css/main.css` should change (`main.css.map` is not
tracked); anything else in the diff means the wrong build ran.

Inside a nested rule, `& + &` repeats the whole parent selector
(`.page .row + .page .row`) and never matches. Write the sibling rule out instead.

## Landing page calculators

A landing page with a calculator in its hero (company savings, child savings, II pillar
rejoin) is:

- `page_<name>.php` (the template WordPress offers) and `templates/<name>-content.php`
- `js/<name>-calculator.js`: plain JavaScript, no jQuery. Pure functions for the
  arithmetic, a small DOM block that wires the inputs, and a CommonJS export
- `js/<name>-calculator.spec.js` for the arithmetic; DOM wiring goes in
  `js/<name>-calculator.dom.spec.js` with a `@jest-environment jsdom` docblock, which
  renders the markup, `jest.isolateModules`-requires the script and drives the inputs
- an `is_page_template()` entry in `helpers/enqueue.php` with its own version in
  `helpers/enqueue_versions.php`
- `scss/_<name>.scss`, imported in `scss/_styles.scss`, scoped under the page's class

When the same calculator exists in the app (onboarding-client), port its numbers into
the spec so the two cannot drift. The page itself is created in wp-admin (or over REST)
with the template selected, after the theme change is deployed: WordPress rejects a
template it cannot find.

## Translations

After editing template strings, regenerate translation files. Write the Estonian
`msgstr` into `lang/et.po` between the `po` and `mo` steps:

```bash
./tools/i18n/generate-pot.sh
./tools/i18n/generate-po.sh
./tools/i18n/generate-mo.sh
```
