# TODO — Fund document automation

Make every document on every fund page updatable without a code change, with one
field vocabulary shared by all four funds and an explicit per-fund declaration of
which documents that fund actually has.

Current process, and why it needs replacing, is in [CLAUDE.md](../CLAUDE.md) §
"Fund document updates".

---

## Target design

### One vocabulary, three states per (fund, document)

Field names are identical on every fund page that carries the document, so no
script, manifest or service branches per fund: fund → page slug, document → field
name. What differs per fund is **whether the field exists at all**.

| State | wp-admin | REST response | Page | Gap check |
|---|---|---|---|---|
| not applicable | no field | key absent | renders nothing | silent |
| optional | field shown | key present | renders when set | silent |
| required | field shown | key present | renders when set | flagged when empty |

The third state is the point. `if ($url)` alone cannot tell "TKF100 has no CO2
figure by design" apart from "TUK75's Põhiteave link silently vanished".

### Applicability rides on page templates

Each fund has its own page template — `page_fund-stocks.php`, `page_fund-bonds.php`,
`page_fund-third.php`, `page_fund-savings.php` — and ACF binds field groups by
template. So "this document exists for these funds" is a location-rule list, with
per-fund granularity, and no `if fund ==` in any template.

With `show_in_rest` on the generated groups, `GET /wp-json/wp/v2/pages/{id}` then
returns exactly the field set that applies to that fund. Applicability becomes
machine-readable, and the publishing script can reject a not-applicable field with
a clear error instead of ACF silently dropping it.

### Applicability matrix

| Field | TUK75 | TUK00 | TUV100 | TKF100 |
|---|---|---|---|---|
| `prospectus_file` | required | required | required | required |
| `terms_file` | required | required | required | required |
| `key_investor_info_file` | required (Põhiteave) | required | required | required (PRIIPs KID) |
| `model_portfolio_file` | required | required | required | required |
| `investment_report_file` | required | required | required | required |
| `prospectus_upcoming_file` | optional | optional | optional | optional |
| `terms_upcoming_file` | optional | optional | optional | optional |
| `nav_procedure_file` | firm-wide option | firm-wide option | firm-wide option | required (own document) |
| `nav_procedure_upcoming_file` | firm-wide option | firm-wide option | firm-wide option | optional |
| `previous_reports_url` | optional (pensionikeskus) | optional | optional | optional |
| `investor_rights_file` | — | — | — | required (UCITS) |
| `fund_co2_intensity` | required | required | required | — (not calculated) |

Pension funds share one NAV procedure document; TKF100 has its own. Pension funds
have no summary of investor rights; TKF100 needs one as a UCITS. CO2 intensity is
not calculated for TKF100 — when it is, flip the cell to `required` in the scope
table, no code change.

### Fund pages

| Fund | Page ID | Slug | Template |
|---|---|---|---|
| TUK75 | 17533 | `tuleva-maailma-aktsiate-pensionifond` | `page_fund-stocks.php` |
| TUK00 | 17537 | `tuleva-maailma-volakirjade-pensionifond` | `page_fund-bonds.php` |
| TUV100 | 20938 | `tuleva-iii-samba-pensionifond` | `page_fund-third.php` |
| TKF100 | 35292 | `tuleva-taiendav-kogumisfond-dokumendid` | `page_fund-savings.php` |

`taiendav-kogumisfond` (37325) is the TKF100 **landing** page on
`page_savings-fund-landing.php`. It renders no documents and carries no document
fields.

---

## Findings this plan has to fix

Each of these is a silent failure — nothing errors, the page just keeps showing the
old value.

- **No ACF field is REST-writable.** No field group in the theme sets
  `show_in_rest`, so ACF drops the `acf` key from any write.
  `GET /wp-json/wp/v2/pages/35292` and `/17533` both return `"acf": []`. Both
  `scripts/update_acf.py` and onboarding-service's `WordPressMediaClient` get
  HTTP 200 and change nothing. TKF100's automated path has never worked.
- **`nav_procedure_upcoming_file` is read but registered nowhere.**
  `fund-savings-details.php` reads it; no ACF group defines it. It always returns
  null, so that row always comes from the hardcoded URL and cannot be set in
  wp-admin.
- **onboarding-service targets the wrong TKF100 page.** `FundReportMapping.TKF100`
  uses slug `taiendav-kogumisfond` → page 37325, which is the landing page. The
  document fields live on 35292.
- **Neither writer verifies.** `updateAcfReportField` treats HTTP 200 as success
  without checking that the field changed.
- **New TKF100 documents keep landing in code.** Upcoming prospectus, terms and NAV
  procedure (#88, #93, c9bcf9b9) were all added as hardcoded template URLs on the
  one fund that is supposed to be ACF-driven — because wiring a new ACF field by
  hand is slower than editing the template. A generated field set removes that
  incentive.
- **Two conventions for the same effective date.** Pension pages hardcode the
  string; TKF100 uses the media library *title* of the attachment. Unify on
  deriving it from the filename — the publishing script controls filenames, titles
  are hand-typed.

---

## Steps

### 1. Field catalogue and per-fund scope

Replaces PR #69, which predates 19 commits of master and moved one field only.

- [ ] `helpers/acf/fund-documents.php` — one catalogue entry per document (field
      name, label, type, return format) and one scope table declaring
      required/optional/absent per page template
- [ ] Generate one ACF field group per template from the scope table, each with
      `show_in_rest => 1`
- [ ] Pin the existing TKF100 field keys (`field_fund_savings_prospectus`, …) in
      the catalogue so stored values survive the move
- [ ] Register `nav_procedure_upcoming_file`
- [ ] Retire `group_fund_savings_documents` in favour of the generated group
- [ ] `tuleva_fund_document_url()` accessor honouring scope: a field that does not
      apply to the current page returns empty, never a fallback from another fund
- [ ] Shared effective-date parser reading `…alates-DD.MM.YYYY` from the filename
- [ ] All four templates read through the accessor; hardcoded URLs stay as
      fallbacks so the PR ships no visible change
- [ ] Tests: every scoped name exists in the catalogue, generated keys are unique
      across templates, the accessor respects scope, the date parser handles the
      filenames in use

### 2. Populate the fields in production

Until this is done, everything still renders from the fallbacks and step 1 has
changed nothing in practice.

- [ ] Set every required field on all four pages from the media library
- [ ] Confirm each page renders the ACF value, not the fallback — temporarily
      clearing a fallback is the only way to be sure
- [ ] Confirm `GET /wp-json/wp/v2/pages/{id}` now returns a populated `acf` object
      for all four

### 3. Firm-wide documents onto the options page

ESG, ESG factors, remuneration and the pension NAV procedure are one document each
for all four funds; per-page fields would turn one update into four.

- [ ] Add them to the existing "Theme Settings" ACF options page
- [ ] `get_esg_document_url()` and the other three read the option and fall back to
      the current constant
- [ ] Decide the automation stance: ACF's REST integration does not cover options
      pages, so these are wp-admin-only unless a small `tuleva/v1/documents` route
      is added. They change once or twice a year — wp-admin is likely enough

### 4. One publishing script

- [ ] `scripts/publish_docs.py` — manifest of page slug → field name → local PDF,
      one command for a whole document release
- [ ] Reuse an existing attachment when the sanitised filename already exists,
      the way `WordPressMediaClient` does, so re-runs are idempotent
- [ ] Validate the manifest against the page's REST `acf` keys and reject a field
      that does not apply to that fund
- [ ] After writing, re-read the field and assert it changed, then fetch the public
      page and assert the new URL renders
- [ ] Delete `scripts/update_acf.py` (hardcoded to two TKF100 fields and a stale
      slug)

### 5. Gap check

- [ ] Admin notice on each fund page edit screen listing required-but-empty
      documents
- [ ] Same check available to the publishing script as a non-zero exit
- [ ] Flag an upcoming document whose effective date has passed — it needs
      promoting to current. Do **not** promote automatically: the current document
      has to be retired in the same move, and guessing that from a filename risks
      displaying the wrong legal document

### 6. Shared render partial

- [ ] One `templates/components/fund-documents.php` driven by the catalogue,
      replacing four hand-maintained `<ul>` lists. The three pension lists are
      byte-identical today, which is what makes "add a document to one fund and
      forget the other three" possible

### 7. Remove the code fallbacks

Only after step 2 is verified on all four pages. A fallback that silently wins is
worse than no fallback: db8a36ca had to add a comment explaining which of two CO2
figures actually renders.

- [ ] Delete the hardcoded document URLs from the three pension templates
- [ ] Delete the `$code_*` variables from the savings template
- [ ] Delete the URL constants from the four `helpers/extras.php` document helpers
      once step 3 is populated

### 8. CO2 intensity

Redo of PR #86, which is stacked on #69.

- [ ] Add `fund_co2_intensity` to the catalogue as a text field — text because
      trailing zeros are significant ("133.80" must not render as "133.8")
- [ ] Scope: required for the three pension templates, absent for TKF100
- [ ] Move it out of `group_fund_savings_details` with its key pinned

### 9. onboarding-service — separate repo, separate PR

- [ ] `FundReportMapping.TKF100` page slug → `tuleva-taiendav-kogumisfond-dokumendid`
- [ ] `updateAcfReportField` asserts the response's
      `acf.investment_report_file` matches the attachment id, and throws otherwise
- [ ] Re-run a month's publish and confirm all four pages repoint

---

## Out of scope

- Financial reports under `/aruanded/` — a separate page, not part of the fund page
  document set
- Fee figures — see [FEE-UPDATES.md](../FEE-UPDATES.md)
- The document *contents* and their approval; this plan only covers publishing an
  already-approved PDF
