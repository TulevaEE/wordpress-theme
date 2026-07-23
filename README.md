# Tuleva WordPress theme

The theme behind [tuleva.ee](https://tuleva.ee). Pushing to `master` runs the tests
on CircleCI and rsyncs the theme to production.

## Local setup

You need two things:

1. **Docker** — `brew install --cask docker`, then open Docker Desktop once so
   it can start.
2. **SSH access to production** — ask a maintainer to add your public SSH key.
   `setup.sh` builds your local database and plugins by connecting to production
   read-only over SSH.

Then:

```bash
git clone git@github.com:TulevaEE/wordpress-theme.git
cd wordpress-theme
./setup.sh                      # first run creates .env, then stops
```

Open `.env` and paste the `PROD_*` values from the team 1Password vault (SSH host
and a read-only database account). Then run it again:

```bash
./setup.sh
```

This builds a local copy of the site from production (read-only) — the database
and the `wp-content` plugins and translations — so it takes a few minutes. When
it finishes you have the site at **http://localhost:8880** and wp-admin at
**http://localhost:8880/wp-admin** (`admin` / `admin`).

Images come from the live site by default (missing local uploads are redirected
to tuleva.ee); to hold a full local copy instead, run
`WITH_UPLOADS=1 ./tools/export-prod-assets.sh` once.

Running `./setup.sh` again is harmless — use it to start the site any time.

## Working on the theme

Edit files in `src/wp-content/themes/tuleva/`. PHP changes appear on reload.

For CSS, run the SCSS watcher in a second terminal — it compiles `scss/` into
`css/main.css` as you save:

```bash
cd src/wp-content/themes/tuleva
npm install
npm run watch:scss
```

Node 24 is required (`.nvmrc`). With [nvm](https://github.com/nvm-sh/nvm)
installed, `nvm use` in that directory picks it up.

Tests:

```bash
cd src/wp-content/themes/tuleva
composer install && composer test   # PHPUnit
npm test                            # Jest
```

## Everyday commands

| Command | What it does |
|---|---|
| `./setup.sh` | Start the site (or resume it) |
| `./setup.sh --logs` | Tail the WordPress error log |
| `./setup.sh --wp <command>` | Run any WP-CLI command, e.g. `--wp plugin list` |
| `./setup.sh --reset` | Throw away the database and re-import the dump |
| `./setup.sh --down` | Stop everything |

Need to poke at the database directly?
`docker compose --profile tools up -d phpmyadmin` → http://localhost:8881

## When something breaks

**The site looks broken, or pages 404.** `./setup.sh --reset` re-imports the
database and re-applies every fix. This is almost always the answer.

**`Call to undefined function get_field()`.** The ACF plugin files are missing.
`./tools/export-prod-assets.sh` then `./setup.sh`.

**Docker says it cannot connect.** Open Docker Desktop and wait for it to
finish starting.

Claude Code can debug this environment on its own — `./setup.sh --wp` gives it
full WP-CLI access, and `./setup.sh --logs` shows it the errors.

## Where the local content comes from

`./tools/export-prod-assets.sh` (which `./setup.sh` runs for you) builds two
things directly from production, read-only: a dump of the database, and the
`wp-content` plugins and translations that can't live in git. It connects with
the `PROD_*` credentials in your `.env` — a **read-only** database account whose
values come from the team 1Password vault. No values are hardcoded in this repo
(it is public), so nothing production-identifying is committed.

The dump is sanitized as it is built: the WPML and ACF Pro subscription keys are
stripped, and the Wordfence / audit-log / redirection-log tables are emptied
(kept as structure, no rows) so no visitor IPs or 2FA secrets are copied. The
script prints the table list so you can see exactly what it produced.

Production media (several GB) is not copied; missing images redirect to the live
site. To keep a full local copy, run `WITH_UPLOADS=1 ./tools/export-prod-assets.sh`.

## Deployment

CircleCI deploys on every push to `master`: it runs the PHP and JavaScript tests,
then rsyncs **only** `src/wp-content/themes/tuleva/` to production. Nothing in
`docker/`, `tools/` or `wordpress/` is ever deployed. The SSH key and the deploy
target live in CircleCI environment variables, not in this repo — `DEPLOY_TARGET`
is the full rsync destination (`user@host:/absolute/path/.../themes/tuleva/`),
not just a host.

## Translations

After changing translatable strings in templates, regenerate the language files
(requires `brew install wp-cli`):

```bash
./tools/i18n/generate-pot.sh   # extract strings into lang/tuleva.pot
./tools/i18n/generate-po.sh    # merge into the Estonian .po
./tools/i18n/generate-mo.sh    # compile .mo so WordPress picks them up
```

Add the Estonian translations between steps 2 and 3, either by editing the `.po`
file or in POEdit.

If `generate-pot.sh` fails with `Allowed memory size ... exhausted`:

```bash
php -d memory_limit=512M /opt/homebrew/bin/wp i18n make-pot \
  src/wp-content/themes/tuleva src/wp-content/themes/tuleva/lang/tuleva.pot \
  --ignore-domain
```

## Fund document updates

See [CLAUDE.md](./CLAUDE.md) and [FEE-UPDATES.md](./FEE-UPDATES.md).
