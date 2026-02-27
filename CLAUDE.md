# Claude Code instructions — TulevaEE/wordpress-theme

> For fee updates see **[FEE-UPDATES.md](./FEE-UPDATES.md)**

## Project overview

This repo contains the WordPress theme for tuleva.ee. It deploys automatically:
**push to `master`** → CircleCI runs tests → rsync to `virt56861@ftp.tuleva.ee`.

Theme templates live in:
`src/wp-content/themes/tuleva/templates/`

---

## Fund document updates (main recurring task)

Tuleva has four funds. Their legal documents (prospectus + key investor information)
are updated a few times per year. The process differs by fund:

| Fund | Document location | Deploy method |
|---|---|---|
| TUK75 (Aktsiate Pensionifond) | Hardcoded URL in PHP template | git push → CircleCI |
| TUK00 (Võlakirjade Pensionifond) | Hardcoded URL in PHP template | git push → CircleCI |
| TUV100 (III Samba Pensionifond) | Hardcoded URL in PHP template | git push → CircleCI |
| TKF100 (Täiendav Kogumisfond) | ACF field in WordPress DB | WP REST API call |

### Required credentials (environment variables — never hardcode)

```
WP_USERNAME       WordPress username (email address)
WP_APP_PASSWORD   WordPress Application Password
                  Generate at: https://tuleva.ee/wp-admin → Users → Application Passwords
```

### Step 1 — Upload PDFs

Place the new PDF files in a folder. Run:

```bash
python3 scripts/upload_docs.py /path/to/folder/with/new/pdfs
```

This uploads every PDF in the folder to the WordPress media library and writes
`upload_results.json` (with attachment IDs) to the same folder.

**File naming convention** — local filenames use Estonian characters and spaces;
the script sanitizes them automatically for URLs:
- `Põhiteave → Pohiteave` (õ→o, ä→a, ö→o, ü→u)
- spaces → `-`
- ` - ` → `-`

Example: `Põhiteave TUK75 - kehtib alates 27.02.2026.pdf`
→ uploaded as `Pohiteave-TUK75-kehtib-alates-27.02.2026.pdf`

### Step 2 — Update PHP templates (TUK75, TUK00, TUV100)

Edit the date string in the three component files. Use sed or edit manually:

```bash
OLD=19.02.2026   # replace with the old effective date
NEW=27.02.2026   # replace with the new effective date

COMPONENTS=src/wp-content/themes/tuleva/templates/components
sed -i '' "s/${OLD}/${NEW}/g" \
  "$COMPONENTS/fund-stocks-details.php" \
  "$COMPONENTS/fund-bonds-details.php" \
  "$COMPONENTS/fund-third-details.php"
```

Verify only document filenames changed:
```bash
grep -n "kehtib-alates" src/wp-content/themes/tuleva/templates/components/fund-*-details.php
```

### Step 3 — Commit and push (deploys TUK75, TUK00, TUV100)

```bash
git add src/wp-content/themes/tuleva/templates/components/fund-stocks-details.php
git add src/wp-content/themes/tuleva/templates/components/fund-bonds-details.php
git add src/wp-content/themes/tuleva/templates/components/fund-third-details.php
git commit -m "Update fund document links to DD.MM.YYYY versions"
git push origin master
```

CircleCI build status: https://app.circleci.com

### Step 4 — Update savings fund ACF fields (TKF100)

ACF REST API is **not enabled** on this site, so this step must be done
manually in the WordPress admin:

1. Go to `https://tuleva.ee/wp-admin/post.php?post=35292&action=edit`
2. Scroll to the ACF document fields
3. For **Prospectus file**: delete the current file, then Add → search `TKF100-Prospekt-kehtib-alates-DD.MM.YYYY` → select
4. For **Key investor info file**: delete the current file, then Add → search `Pohiteave-TKF100-kehtib-alates-DD.MM.YYYY` → select
5. Click **Update**

The files will already be in the media library from Step 1 — search by filename.
No git push needed; the change is live immediately after saving.

---

## Deployment

- CircleCI is triggered by every push to `master`
- It rsync-deploys changed files to `virt56861@ftp.tuleva.ee`
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
