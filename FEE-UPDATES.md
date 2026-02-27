# Fee update guide — tuleva.ee

Use this guide whenever Tuleva changes management fees or ongoing charges figures.

---

## Current fee values (last updated 27.02.2026)

| Fund | managementFeeRate (decimal) | Display (comma) | ongoingChargesFigure (decimal) | Display (comma) |
|---|---|---|---|---|
| TUK75 (Aktsiate) | 0.00205 | 0,205% | 0.0028 | 0,28% |
| TUK00 (Võlakirjade) | 0.00163 | 0,163% | 0.0028 | 0,28% |
| TUV100 (III Samba) | 0.00179 | 0,179% | 0.0028 | 0,28% |
| TKF100 (Täiendav) | 0.00152 | 0,152% | 0.0028 | 0,28% |
| Calculator / homepage | — | — | 0.0028 | 0,28% |

---

## Part A — Git changes

All files are in `src/wp-content/themes/tuleva/`.

### 1. Fund detail components (displayed fee table)

Edit management fee and ongoing charges in:

| File | Management fee line | Ongoing charges line |
|---|---|---|
| `templates/components/fund-stocks-details.php` | `<span>X,XXX%</span>` (line ~30) | `<span>X,XX%</span>` (line ~34) |
| `templates/components/fund-bonds-details.php` | same pattern | same pattern |
| `templates/components/fund-third-details.php` | same pattern | same pattern |

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

### 3. API fallback values

These are used when the pensionikeskus API is unavailable (localhost dev or API timeout).
Edit the hardcoded JSON in:

| File | Field | New value |
|---|---|---|
| `templates/fund-stocks-content.php` | `"managementFeeRate"` | decimal, e.g. `0.00205` |
| `templates/fund-stocks-content.php` | `"ongoingChargesFigure"` | decimal, e.g. `0.0028` |
| `templates/fund-bonds-content.php` | same two fields | |
| `templates/fund-third-content.php` | same two fields | |
| `templates/fund-savings-content.php` | same two fields | |

### 4. Commit and push

```bash
git add \
  src/wp-content/themes/tuleva/templates/components/fund-stocks-details.php \
  src/wp-content/themes/tuleva/templates/components/fund-bonds-details.php \
  src/wp-content/themes/tuleva/templates/components/fund-third-details.php \
  src/wp-content/themes/tuleva/js/calculator.js \
  src/wp-content/themes/tuleva/templates/components/front-hero/calculator.php \
  src/wp-content/themes/tuleva/lang/et.po \
  src/wp-content/themes/tuleva/lang/et.mo \
  src/wp-content/themes/tuleva/templates/fund-stocks-content.php \
  src/wp-content/themes/tuleva/templates/fund-bonds-content.php \
  src/wp-content/themes/tuleva/templates/fund-third-content.php \
  src/wp-content/themes/tuleva/templates/fund-savings-content.php

git commit -m "Update management fees and ongoing charges effective DD.MM.YYYY"
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

### TKF100 savings fund ACF fields
URL: `https://tuleva.ee/wp-admin/post.php?post=35292&action=edit`
Scroll to the ACF custom fields section and update:
- **Management fee** field: display value e.g. `0,152%`
- **Ongoing charges** field: display value e.g. `0,28%`

Use the delete + re-add pattern (delete current value, click Add, type new value).

---

## Verification checklist

After CI goes green and WP Admin edits are saved:

- [ ] `tuleva.ee/aktsiate-pensionifond/` — fund table shows new management fee + 0,28%
- [ ] `tuleva.ee/volakirjade-pensionifond/` — fund table shows new management fee + 0,28%
- [ ] `tuleva.ee/iii-samba-pensionifond/` — fund table shows new management fee + 0,28%
- [ ] `tuleva.ee` homepage calculator — shows **0,28% aastas** (ET) and **0.28% per year** (EN at `/en/`)
- [ ] `tuleva.ee/kuidas-tuua-pension-tulevasse/` — both language sections updated
- [ ] `tuleva.ee/tasud-alla/` — Tuleva fee updated
- [ ] TKF100 fund page — ACF management fee and ongoing charges updated

Use a private/incognito window or hard-refresh (Cmd+Shift+R) to bypass browser cache.

---

## How to do this with Claude Code next time

Open Claude Code in the `wordpress-theme` directory and paste a prompt like this:

```
Please update the fees on tuleva.ee. New values effective DD.MM.YYYY:

| Fund    | Valitsemistasu | Kogukulu |
|---------|---------------|---------|
| TUK75   | 0,XXX%        | 0,XX%   |
| TUK00   | 0,XXX%        | 0,XX%   |
| TUV100  | 0,XXX%        | 0,XX%   |
| TKF100  | 0,XXX%        | 0,XX%   |

Follow the plan in FEE-UPDATES.md. Do Part A (git changes) automatically.
For Part B (WP Admin) give me step-by-step instructions.
```

Claude will:
1. Read `FEE-UPDATES.md` and the current fee table for old values
2. Edit all 10 theme files
3. Update `msgid` **and** `msgstr` in `et.po` (dot format for msgid, comma format for msgstr)
4. Regenerate `et.mo` with polib
5. Commit and push — CircleCI deploys automatically
6. Tell you what to do manually in WP Admin (Part B)

### Things to double-check before saying yes to the push

- `et.po`: both `msgid "0.XX% per year"` and `msgstr "0,XX% aastas"` use the **new** percentage
- `calculator.js`: `tulevaFee` matches the new ongoing charges decimal
- All four `*-content.php` fallback files have both `managementFeeRate` and `ongoingChargesFigure` updated
