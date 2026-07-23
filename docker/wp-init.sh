#!/bin/sh
# Runs inside wordpress:cli once, after the database import. Idempotent:
# re-running is a no-op until BOOTSTRAP_VERSION changes.
set -eu

BOOTSTRAP_VERSION=1

SITE_URL="${SITE_URL:-http://localhost:8880}"
ADMIN_USER="${ADMIN_USER:-admin}"
ADMIN_PASS="${ADMIN_PASS:-admin}"
ADMIN_EMAIL="${ADMIN_EMAIL:-dev@example.test}"

# Every command skips plugins and themes. The imported database lists
# really-simple-ssl and wordfence as active; really-simple-ssl force-redirects
# to https and would break WP-CLI before it can fix anything. This affects only
# this process — it does not change what the browser loads.
WP="wp --path=/var/www/html --skip-plugins --skip-themes"

log() { echo "==> $*"; }

# The wordpress container generates wp-config.php on its first start.
i=0
while [ ! -f /var/www/html/wp-config.php ]; do
  i=$((i + 1))
  [ "$i" -gt 60 ] && { echo "!! wp-config.php never appeared. Is the wordpress container running?"; exit 1; }
  sleep 1
done

# `wp rewrite flush --hard` refuses to write .htaccess unless WP-CLI knows
# mod_rewrite is loaded. This file cannot be bind-mounted: Docker cannot create
# a single-file mountpoint inside a directory that is itself a bind mount.
[ -f /var/www/html/wp-cli.yml ] || \
  printf 'path: /var/www/html\napache_modules:\n  - mod_rewrite\n' > /var/www/html/wp-cli.yml

if ! $WP core is-installed 2>/dev/null; then
  echo "!! The database has no WordPress installation in it."
  echo "!! Put a dump in ./mysql-init/ and run ./setup.sh --reset"
  exit 1
fi

# The marker includes the settings this script bakes into the database, so
# changing SITE_URL, ADMIN_USER or ADMIN_PASS in .env re-runs the bootstrap
# instead of leaving stale serialized URLs or a login that no longer matches
# what setup.sh prints. The password is included as a checksum, not in clear.
PASS_HASH=$(printf '%s' "$ADMIN_PASS" | cksum | cut -d' ' -f1)
MARKER="$BOOTSTRAP_VERSION:$SITE_URL:$ADMIN_USER:$PASS_HASH"
CURRENT="$($WP option get tuleva_local_bootstrap 2>/dev/null || echo 0)"
if [ "$CURRENT" = "$MARKER" ]; then
  log "Already bootstrapped (v$BOOTSTRAP_VERSION, $SITE_URL)."
  exit 0
fi

# --- URLs --------------------------------------------------------------------
# Replaces README step 6's manual SQL.
#
# That UPDATE fixed the two scalar rows siteurl and home. Every other
# occurrence of https://tuleva.ee is inside a PHP-serialized blob —
# icl_sitepress_settings, theme_mods_tuleva, widget_*, ACF option values — stored
# as s:17:"https://tuleva.ee". Replacing the text without rewriting the byte
# count 17 makes unserialize() return false, and WordPress silently falls back
# to defaults: menus vanish, the language switcher dies, ACF fields read empty.
# search-replace unserializes, replaces, and re-serializes properly.
log "Rewriting production URLs -> $SITE_URL"
for FROM in "https://tuleva.ee" "http://tuleva.ee" "https://www.tuleva.ee" "http://www.tuleva.ee"; do
  # --all-tables-with-prefix: WPML's wp_icl_* tables are not registered on $wpdb
  # --precise: force PHP-side unserialize/replace/serialize
  # --skip-columns=guid: GUIDs are permanent feed identifiers, never URLs to follow
  $WP search-replace "$FROM" "$SITE_URL" \
    --all-tables-with-prefix \
    --precise \
    --recurse-objects \
    --skip-columns=guid \
    --report-changed-only || true
done
$WP option update siteurl "$SITE_URL"
$WP option update home "$SITE_URL"

# --- plugins -----------------------------------------------------------------
# Belt and braces. The load-bearing mechanism is the mu-plugin's
# option_active_plugins filter, which needs no database write.
log "Deactivating production-only plugins"
for P in really-simple-ssl wordfence; do
  $WP plugin is-active "$P" >/dev/null 2>&1 && $WP plugin deactivate "$P" || true
done

# --- WPML --------------------------------------------------------------------
# 1 = language directories (/en/), which is what production uses. 2 is
# per-domain and cannot work on a single localhost port.
log "Forcing WPML directory language URLs"
$WP option patch update icl_sitepress_settings language_negotiation_type 1 >/dev/null 2>&1 \
  || echo "    (skipped: icl_sitepress_settings not in this dump)"

# --- admin -------------------------------------------------------------------
log "Ensuring local admin '$ADMIN_USER'"
if $WP user get "$ADMIN_USER" >/dev/null 2>&1; then
  $WP user update "$ADMIN_USER" --user_pass="$ADMIN_PASS" --role=administrator >/dev/null
else
  $WP user create "$ADMIN_USER" "$ADMIN_EMAIL" --role=administrator \
    --user_pass="$ADMIN_PASS" --porcelain >/dev/null
fi

# --- misc --------------------------------------------------------------------
$WP option update blog_public 0    # never let a local copy be indexed
$WP option update permalink_structure '/%postname%/'

# Flush with --skip-plugins. Loading plugins here just makes WPML's CLI bootstrap
# throw (WPML_Cache_Directory under WP-CLI), which always aborted the flush and
# fell back to this same skip-plugins flush anyway — so the only difference was a
# noisy stack trace. Core permalink rules are regenerated here; WPML's language
# routing is runtime, not .htaccess-based, and regenerates plugin/CPT rules on the
# next front-end request.
$WP rewrite flush --hard

$WP option update tuleva_local_bootstrap "$MARKER" --autoload=no
log "Done. $SITE_URL/wp-admin ($ADMIN_USER / $ADMIN_PASS)"
