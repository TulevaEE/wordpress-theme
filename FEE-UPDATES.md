# Fee update guide — tuleva.ee

Use this guide whenever Tuleva changes its ongoing charges figure.

## Management fee and the fund manager's units: nothing to change here

The four fund pages read both from the onboarding service's fund list (`/v1/funds`):

- **Management fee** — the rate in force today in onboarding-service's `investment_fee_rate`
  table, so it switches by itself on the row's start date. A management fee change is one
  dated row in that table, not an edit in this repo or in WP Admin.
- **Fund manager's units** — Tuleva Fondid AS's units in each fund at the last month end,
  from the fund's unit register, shown with that date.

`helpers/fund-figures.php` formats them for the page's language. While the fund list cannot
be read, the pages show the last figures it returned (one WP option per fund,
`tuleva_fund_figures_last_good_<ISIN>`); before it has ever been read, the two rows are left out.
The plan behind this is "TODO — Fund page figures from the database", in the private tuleva
repo under `work/investeerimistegevus/docs/`.

---

## Current ongoing charges (last updated 27.02.2026)

| Fund | ongoingChargesFigure (decimal) | Display (comma) |
|---|---|---|
| TUK75 (Aktsiate) | 0.0028 | 0,28% |
| TUK00 (Võlakirjade) | 0.0028 | 0,28% |
| TUV100 (III Samba) | 0.0028 | 0,28% |
| TKF100 (Täiendav) | 0.0028 | 0,28% |
| Calculator / homepage | 0.0028 | 0,28% |

---

## Part A — Git changes

All files are in `src/wp-content/themes/tuleva/`.

### 1. Fund detail components (displayed fee table and JSON-LD)

Each pension fund component declares its facts once in the `$fund` array at the top of the file; the visible table and the `InvestmentFund` JSON-LD (read by search engines and AI crawlers) both render from it. Edit `ongoing_charges` there:

| File | Array key |
|---|---|
| `templates/components/fund-stocks-details.php` | `'ongoing_charges' => 'X,XX%'` |
| `templates/components/fund-bonds-details.php` | same |
| `templates/components/fund-third-details.php` | same |

TKF100 (`fund-savings-details.php`) reads it from an ACF field (see Part B); its JSON-LD follows that field automatically.

### 2. Calculator (homepage)

**`js/calculator.js` line 2**
```js
var tulevaFee = 0.00XX;   // decimal, e.g. 0.0028
```

**`templates/components/front-hero/calculator.php` line ~134**
```php
<?php _e('0.XX% per year', TEXT_DOMAIN); ?>
```

**`lang/et.po` — two things must match:**
```
msgid "0.XX% per year"     ← must match the PHP string exactly (dot, EN format)
msgstr "0,XX% aastas"      ← Estonian display (comma, ET format)
```

> **Common mistake:** if you update the PHP string, you must also update the `msgid`
> in `et.po` to match — it is the lookup key, not just a comment.
> If only `msgstr` is updated the translation will silently fall back to English.

After editing `et.po`, regenerate both `.mo` files:
```bash
python3 -c "
import polib, os
d = 'src/wp-content/themes/tuleva/lang'
for f in ['et', 'tuleva']:
    po_path = os.path.join(d, f + '.po')
    if os.path.exists(po_path):
        po = polib.pofile(po_path)
        po.save_as_mofile(os.path.join(d, f + '.mo'))
        print('Generated', f + '.mo')
"
```
> `polib` can be installed with `pip install polib` if missing.

### 3. Savings fund landing pages

Three pages write the figure into translatable strings. Change the PHP string and its
`msgid` in `et.po` together, update the `msgstr`, then regenerate `.mo` as in step 2. One
`msgid` is shared by all three templates, so change it in all three at once.

| File | Page | Strings with the figure |
|---|---|---|
| `templates/savings-fund-landing-content.php` | TKF100 landing, `tuleva.ee/taiendav-kogumisfond/` | `The fund's fee is 0.28% per year, …`; `Fee <strong>0.28%</strong> per year, no extra charges` |
| `templates/child-savings-content.php` | `tuleva.ee/lapsele-kogumine/` | `Fee <strong>0.28%</strong> per year, …`; `The fund's ongoing charges are 0.28% per year. …`; `LHV's Kasvukonto is a platform …` |
| `templates/company-savings-content.php` | `tuleva.ee/osauhingule-kogumine/` | `A fund fee of 0.28% a year, …`; `Fee <strong>0.28%</strong> per year, …` |

### 4. API fallback values

These stand in for the API on `localhost` only, for local development. Production never
reads them, so keeping them current is optional:

| File | Field | New value |
|---|---|---|
| `templates/fund-stocks-content.php` | `"ongoingChargesFigure"` | decimal, e.g. `0.0028` |
| `templates/fund-bonds-content.php` | same field | |
| `templates/fund-third-content.php` | same field | |
| `templates/fund-savings-content.php` | same field | |

### 5. Commit and push

```bash
git add \
  src/wp-content/themes/tuleva/templates/components/fund-stocks-details.php \
  src/wp-content/themes/tuleva/templates/components/fund-bonds-details.php \
  src/wp-content/themes/tuleva/templates/components/fund-third-details.php \
  src/wp-content/themes/tuleva/js/calculator.js \
  src/wp-content/themes/tuleva/templates/components/front-hero/calculator.php \
  src/wp-content/themes/tuleva/templates/savings-fund-landing-content.php \
  src/wp-content/themes/tuleva/templates/child-savings-content.php \
  src/wp-content/themes/tuleva/templates/company-savings-content.php \
  src/wp-content/themes/tuleva/lang/et.po \
  src/wp-content/themes/tuleva/lang/et.mo \
  src/wp-content/themes/tuleva/templates/fund-stocks-content.php \
  src/wp-content/themes/tuleva/templates/fund-bonds-content.php \
  src/wp-content/themes/tuleva/templates/fund-third-content.php \
  src/wp-content/themes/tuleva/templates/fund-savings-content.php

git commit -m "Update ongoing charges effective DD.MM.YYYY"
git push origin master
```

CircleCI deploys automatically on push to `master`.

---

## Part B — WordPress Admin (manual, live immediately)

These values live in the database, not in theme files. Edit them in WP Admin after the git deploy.

### Transfer pension page
URL: `https://tuleva.ee/kuidas-tuua-pension-tulevasse/`
Find the page in WP Admin → edit content → update ongoing charges figure in both the ET and EN content sections.

### Tasud-alla page
URL: `https://tuleva.ee/tasud-alla/`
Find the page in WP Admin → edit content → update Tuleva's ongoing charges figure.

### TKF100 savings fund ACF field — both languages
Edit **both** pages:
- Estonian: `https://tuleva.ee/wp-admin/post.php?post=35292&action=edit`
- English: `https://tuleva.ee/wp-admin/post.php?post=36156&action=edit`

Scroll to the ACF custom fields section and update:
- **Ongoing charges** field: display value e.g. `0,28%`

Use the delete + re-add pattern (delete current value, click Add, type new value).

Unlike the fund documents, this field is not read from the Estonian page:
`fund-savings-details.php` reads it with `get_field()` from the page being viewed, and the
English page keeps its own value. Changing only page 35292 leaves the English page on the
old figure.

### TKF100 documents page content
On page 35292, the page content (the questions and answers) states the ongoing charges in
free text. Update the figure there too.

### TKF100 landing page SEO description
The Yoast meta description of `tuleva.ee/taiendav-kogumisfond/` and its English page states
the fee. Update it in the Yoast box on both pages.

---

## Verification checklist

After CI goes green and WP Admin edits are saved:

- [ ] `tuleva.ee/tuleva-maailma-aktsiate-pensionifond/` — fund table shows the new ongoing charges
- [ ] `tuleva.ee/tuleva-maailma-volakirjade-pensionifond/` — fund table shows the new ongoing charges
- [ ] `tuleva.ee/tuleva-iii-samba-pensionifond/` — fund table shows the new ongoing charges
- [ ] `tuleva.ee` homepage calculator — shows **0,28% aastas** (ET) and **0.28% per year** (EN at `/en/`)
- [ ] `tuleva.ee/kuidas-tuua-pension-tulevasse/` — both language sections updated
- [ ] `tuleva.ee/tasud-alla/` — Tuleva fee updated
- [ ] TKF100 documents page, `tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/` and `tuleva.ee/en/additional-investment-fund-documents/` — fund table and the questions and answers show the new ongoing charges
- [ ] TKF100 landing page, `tuleva.ee/taiendav-kogumisfond/` and `/en/additional-investment-fund/` — page text and the search description
- [ ] `tuleva.ee/lapsele-kogumine/` and `tuleva.ee/osauhingule-kogumine/` — fee figures in the page text

Use a private/incognito window or hard-refresh (Cmd+Shift+R) to bypass browser cache.

---

## How to do this with Claude Code next time

Open Claude Code in the `wordpress-theme` directory and paste a prompt like this:

```
Please update the ongoing charges on tuleva.ee. New values effective DD.MM.YYYY:

| Fund    | Kogukulu |
|---------|---------|
| TUK75   | 0,XX%   |
| TUK00   | 0,XX%   |
| TUV100  | 0,XX%   |
| TKF100  | 0,XX%   |

Follow the plan in FEE-UPDATES.md. Do Part A (git changes) automatically.
For Part B (WP Admin) give me step-by-step instructions.
```

Claude will:
1. Read `FEE-UPDATES.md` and the current fee table for old values
2. Edit the theme files listed in Part A
3. Update `msgid` **and** `msgstr` in `et.po` (dot format for msgid, comma format for msgstr)
4. Regenerate `et.mo` with polib
5. Commit and push — CircleCI deploys automatically
6. Tell you what to do manually in WP Admin (Part B)

### Things to double-check before saying yes to the push

- `et.po`: both `msgid "0.XX% per year"` and `msgstr "0,XX% aastas"` use the **new** percentage
- `calculator.js`: `tulevaFee` matches the new ongoing charges decimal
- Management fee: nothing in this repo changes — it is a dated row in onboarding-service's `investment_fee_rate`
