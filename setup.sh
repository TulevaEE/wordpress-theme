#!/usr/bin/env bash
#
# One command to get tuleva.ee running on your machine.
#
#   ./setup.sh              start (or resume) the local site
#   ./setup.sh --reset      throw away the database and re-import the dump
#   ./setup.sh --logs       tail the WordPress error log
#   ./setup.sh --wp ...     run any WP-CLI command, e.g. ./setup.sh --wp plugin list
#   ./setup.sh --down       stop everything
#
# Safe to run as many times as you like.
set -euo pipefail
cd "$(dirname "$0")"

GREEN=$'\033[0;32m'; YELLOW=$'\033[0;33m'; RED=$'\033[0;31m'; NC=$'\033[0m'
info() { printf '%s==>%s %s\n' "$GREEN" "$NC" "$*"; }
warn() { printf '%s!!%s  %s\n' "$YELLOW" "$NC" "$*"; }
die()  { printf '%sxx%s  %s\n' "$RED" "$NC" "$*" >&2; exit 1; }

RESET=0
case "${1:-}" in
  --reset) RESET=1 ;;
  --logs)  exec docker compose logs -f wordpress ;;
  --down)  exec docker compose down ;;
  --wp)    shift
           exec docker compose run --rm -T --no-deps --entrypoint wp wp-init \
             --path=/var/www/html --skip-plugins --skip-themes "$@" ;;
  --help|-h) sed -n '2,12p' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
  "")      ;;
  *)       die "Unknown option: $1  (try ./setup.sh --help)" ;;
esac

# --- preflight ---------------------------------------------------------------
command -v docker >/dev/null \
  || die "Docker is not installed. Install Docker Desktop: brew install --cask docker"
docker compose version >/dev/null 2>&1 \
  || die "'docker compose' is unavailable. Update Docker Desktop."
docker info >/dev/null 2>&1 \
  || die "Docker is not running. Open Docker Desktop, wait for it to start, then run this again."

if [ ! -f .env ]; then
  cp .env.example .env
  info "Created .env from .env.example"
fi
set -a; . ./.env; set +a
SITE_URL="${SITE_URL:-http://localhost:8880}"

mkdir -p mysql-init wordpress/wp-content

# --- database dump + production assets ---------------------------------------
shopt -s nullglob
DUMPS=(mysql-init/*.sql mysql-init/*.sql.gz)
shopt -u nullglob

if [ ${#DUMPS[@]} -eq 0 ]; then
  if [ -f .assets/dev-db.sql.gz ]; then
    info "Using the database dump already built in .assets/"
    cp .assets/dev-db.sql.gz mysql-init/
  elif [ -n "${PROD_SSH:-}" ]; then
    info "No local database yet — building one from production (read-only)"
    ./tools/export-prod-assets.sh || die "Export failed. Check the PROD_* values in .env (from 1Password)."
    cp .assets/dev-db.sql.gz mysql-init/
  else
    die "No database yet. Fill the PROD_* values in .env from the team 1Password vault
  and re-run, or drop a dev-db.sql.gz from a teammate into ./mysql-init/ yourself."
  fi
  shopt -s nullglob; DUMPS=(mysql-init/*.sql mysql-init/*.sql.gz); shopt -u nullglob
  [ ${#DUMPS[@]} -gt 0 ] || die "Still no dump in ./mysql-init/. Nothing to import."
fi

# --- wp-config sanity --------------------------------------------------------
# WORDPRESS_CONFIG_EXTRA is only honoured by a wp-config.php generated from the
# image's template. The entrypoint skips generation when the file already
# exists, so a wp-config.php hand-edited per the old README would ignore every
# setting here, forever, with no error.
if [ -s wordpress/wp-config.php ] && ! grep -q 'WORDPRESS_CONFIG_EXTRA' wordpress/wp-config.php; then
  BACKUP="wordpress/wp-config.php.bak.$(date +%s)"
  warn "wordpress/wp-config.php is not the Docker template — moving it to $BACKUP"
  mv wordpress/wp-config.php "$BACKUP"
fi

if [ "$RESET" = 1 ]; then
  warn "Resetting: deleting the database volume."
  docker compose down -v
fi

info "Starting containers (the first run imports the dump — this takes a few minutes)"
docker compose up -d --wait db wordpress

# Plugins and translations land after core is unpacked, so the image's
# entrypoint does not race with the extraction. Re-extract whenever the bundle
# is newer than the last extraction (e.g. after a fresh export-prod-assets run),
# not just when ACF happens to be absent.
ASSET_MARKER=wordpress/wp-content/.tuleva-assets-extracted
if [ -f .assets/dev-assets.tar.gz ]; then
  if [ ! -d wordpress/wp-content/plugins/advanced-custom-fields-pro ] \
     || [ .assets/dev-assets.tar.gz -nt "$ASSET_MARKER" ]; then
    info "Installing production plugins and translations"
    tar -xzf .assets/dev-assets.tar.gz -C wordpress
    touch "$ASSET_MARKER"
  fi
elif [ ! -d wordpress/wp-content/plugins/advanced-custom-fields-pro ]; then
  warn "No plugin bundle in .assets/ — the site will render but ACF fields will be empty."
  warn "Run ./tools/export-prod-assets.sh (needs PROD_* in .env) then ./setup.sh again."
fi

info "Bootstrapping WordPress"
docker compose run --rm wp-init

cat <<EOF

${GREEN}Ready.${NC}

  Site          $SITE_URL
  Admin         $SITE_URL/wp-admin        ${ADMIN_USER:-admin} / ${ADMIN_PASS:-admin}

  Edit the theme in src/wp-content/themes/tuleva — changes show up on reload.
  SCSS watcher  cd src/wp-content/themes/tuleva && npm install && npm run watch:scss

  Logs          ./setup.sh --logs
  WP-CLI        ./setup.sh --wp plugin list
  Re-import DB  ./setup.sh --reset
  Stop          ./setup.sh --down
EOF
