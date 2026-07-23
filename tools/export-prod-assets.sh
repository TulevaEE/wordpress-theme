#!/usr/bin/env bash
#
# Builds the local database dump and the wp-content assets bundle by pulling
# from production. Reads read-only credentials from .env (from 1Password).
# setup.sh runs this for you when there is no local dump yet.
#
#   ./tools/export-prod-assets.sh
#   ./tools/export-prod-assets.sh --publish     also upload to a private GitHub release
#
# All PROD_* values come from .env (get them from the team 1Password vault).
# Nothing production-identifying is hardcoded here — this repo is public.
set -euo pipefail
cd "$(dirname "$0")/.."

[ -f .env ] && { set -a; . ./.env; set +a; }

: "${PROD_SSH:?Set PROD_SSH in .env (from 1Password), e.g. PROD_SSH=user@host}"
: "${PROD_DB_NAME:?Set PROD_DB_NAME in .env (from 1Password)}"
: "${PROD_DB_USER:?Set PROD_DB_USER in .env (from 1Password)}"
: "${PROD_DB_PASS:?Set PROD_DB_PASS in .env (from 1Password)}"
# The host runs the database on a separate server from the web server, so
# mysqldump needs an explicit -h.
: "${PROD_DB_HOST:?Set PROD_DB_HOST in .env (from 1Password), e.g. PROD_DB_HOST=your-db-host}"
: "${PROD_WEBROOT:?Set PROD_WEBROOT in .env (from 1Password), e.g. PROD_WEBROOT=path/to/site/htdocs}"

# Optional: a private repo to publish the built assets to. Must be PRIVATE —
# wordpress-theme itself is public, and release assets are as downloadable as
# the repo they hang off, so --publish refuses to run against a public repo.
REPO="${ASSETS_REPO:-}"
TAG="${ASSETS_TAG:-dev-assets-latest}"
WEBROOT="$PROD_WEBROOT"
OUT=.assets
mkdir -p "$OUT"

echo "==> 1/4  exporting the production database"
# Password is passed with -p: Zone's managed mysql/mysqldump wrappers ignore
# MYSQL_PWD, and each tenant is process-isolated so ps exposure is not a concern.
PREFIX="${TABLE_PREFIX:-wp_}"

# Log/telemetry tables whose STRUCTURE we keep but whose DATA we drop: Wordfence
# (visitor IPs, user agents, 2FA secrets), the security audit log, and the
# Redirection hit logs. Discovered by pattern so a new wf* table is caught too.
# Kept as empty tables (not --ignore-table'd away) so Wordfence / WSAL /
# Redirection don't fault on a missing table when the dump is loaded locally.
echo "    finding log/secret tables to empty"
SENSITIVE=$(ssh "$PROD_SSH" "mysql -N -B \
  -h'$PROD_DB_HOST' -u'$PROD_DB_USER' -p'$PROD_DB_PASS' '$PROD_DB_NAME' -e \"
    SELECT table_name FROM information_schema.tables
     WHERE table_schema='$PROD_DB_NAME'
       AND (table_name LIKE '${PREFIX}wf%'
            OR table_name LIKE '${PREFIX}wsal\\_%'
            OR table_name IN ('${PREFIX}redirection_logs','${PREFIX}redirection_404'))\"")
SENSITIVE=$(echo $SENSITIVE)   # collapse newlines to spaces
echo "    emptying $(printf '%s' "$SENSITIVE" | wc -w | tr -d ' ') table(s): $SENSITIVE"

# --skip-extended-insert writes one INSERT per row, which is what makes step 2's
# line-based licence-key strip safe (batched inserts pack many rows per line).
IGNORE=""
for t in $SENSITIVE; do IGNORE="$IGNORE --ignore-table=$PROD_DB_NAME.$t"; done

REMOTE_DUMP="mysqldump --single-transaction --quick \
  --skip-extended-insert --default-character-set=utf8mb4 \
  -h'$PROD_DB_HOST' -u'$PROD_DB_USER' -p'$PROD_DB_PASS' $IGNORE '$PROD_DB_NAME'"
# Append the emptied tables' structure (only when there are any, else this would
# re-dump the whole database).
if [ -n "$SENSITIVE" ]; then
  REMOTE_DUMP="$REMOTE_DUMP; mysqldump --single-transaction \
    --no-data --default-character-set=utf8mb4 \
    -h'$PROD_DB_HOST' -u'$PROD_DB_USER' -p'$PROD_DB_PASS' '$PROD_DB_NAME' $SENSITIVE"
fi
ssh "$PROD_SSH" "$REMOTE_DUMP" | gzip -9 > "$OUT/dev-db.sql.gz"

echo "==> 2/4  removing paid subscription keys from the dump"
# These wp_options rows carry the WPML site key and the ACF Pro licence —
# credentials for paid subscriptions, with no reason to be on anyone's laptop.
# Everything else in the dump is left exactly as it is.
BEFORE=$(gzip -dc "$OUT/dev-db.sql.gz" | grep -ac '^INSERT INTO' || true)
gzip -dc "$OUT/dev-db.sql.gz" \
  | grep -avE "^INSERT INTO \`?${PREFIX}options\`? VALUES \([0-9]+,'(wp_installer_settings|otgs_installer_settings|icl_installer_settings|wpml_site_key|icl_site_key|acf_pro_license|acf_pro_license_status)'" \
  | gzip -9 > "$OUT/dev-db.sql.gz.tmp"
mv "$OUT/dev-db.sql.gz.tmp" "$OUT/dev-db.sql.gz"
AFTER=$(gzip -dc "$OUT/dev-db.sql.gz" | grep -ac '^INSERT INTO' || true)
echo "    removed $((BEFORE - AFTER)) row(s)"
[ "$((BEFORE - AFTER))" -le 10 ] \
  || { echo "!! That removed far more rows than expected — refusing to continue." >&2; exit 1; }

echo "==> 3/4  pulling wp-content assets (plugins, languages)"
# Uploads are excluded by default — the production media library is several GB,
# and local dev serves missing images straight from tuleva.ee via the
# uploads-fallback rule in the mu-plugin. Set WITH_UPLOADS=1 to bundle them.
SOURCES=(
  "$PROD_SSH:$WEBROOT/wp-content/plugins"
  "$PROD_SSH:$WEBROOT/wp-content/languages"
)
[ "${WITH_UPLOADS:-0}" = 1 ] && SOURCES+=( "$PROD_SSH:$WEBROOT/wp-content/uploads" )

# Excludes caches and Wordfence's local data, which is large, machine-specific,
# and full of visitor IP addresses.
# Start from a clean staging dir instead of using rsync --delete. Prod is only
# ever the SOURCE here (rsync never writes to a source), so --delete could only
# have pruned this local scratch dir anyway — but removing the flag makes that
# impossible to get wrong. $OUT is .assets/, gitignored scratch.
rm -rf "$OUT/wp-content"
mkdir -p "$OUT/wp-content"

# --stats (not --info=progress2, which macOS's bundled openrsync rejects) so
# this works with both openrsync and rsync 3.x.
rsync -az --stats \
  --exclude='cache/' \
  --exclude='wflogs/' \
  --exclude='*.log' \
  -e ssh \
  "${SOURCES[@]}" \
  "$OUT/wp-content/"

# The theme is deployed from git; shipping a second copy would let people edit
# the wrong one.
rm -rf "$OUT/wp-content/themes"

tar -czf "$OUT/dev-assets.tar.gz" -C "$OUT" wp-content

echo "==> 4/4  built:"
ls -lh "$OUT/dev-db.sql.gz" "$OUT/dev-assets.tar.gz"

echo
echo "==> verifying log/secret tables were emptied (structure kept, no rows)"
LEAK=0
for pat in "${PREFIX}wf" "${PREFIX}wsal_" "${PREFIX}redirection_logs" "${PREFIX}redirection_404"; do
  n=$(gzip -dc "$OUT/dev-db.sql.gz" | grep -acE "^INSERT INTO \`?${pat}" || true)
  if [ "$n" -gt 0 ]; then
    echo "    LEAK: $n data row(s) for ${pat}* still present"; LEAK=1
  else
    echo "    ok: ${pat}* carries no data"
  fi
done
[ "$LEAK" = 0 ] || { echo "!! Sensitive data present in dump — refusing to publish." >&2; exit 1; }

echo
echo "Tables in the dump — check for anything else that should not be shared:"
gzip -dc "$OUT/dev-db.sql.gz" | grep -aoE '^CREATE TABLE `[^`]+`' | sed 's/CREATE TABLE //' | tr -d '`'

if [ "${1:-}" = "--publish" ]; then
  echo
  : "${REPO:?Set ASSETS_REPO in .env to a PRIVATE repo before --publish}"
  # Release assets inherit the repository's visibility. Attaching a production
  # dump to a public repo publishes it to anyone who guesses the URL, with no
  # authentication and no download log. Refuse, loudly.
  VISIBILITY=$(gh repo view "$REPO" --json isPrivate -q .isPrivate 2>/dev/null) \
    || { echo "!! Cannot read $REPO. Create it as a PRIVATE repo first." >&2; exit 1; }
  [ "$VISIBILITY" = "true" ] \
    || { echo "!! $REPO is PUBLIC. Refusing to upload a production dump to it." >&2
         echo "!! Set ASSETS_REPO in .env to a private repository." >&2; exit 1; }

  echo "==> publishing to release $TAG in $REPO (private)"
  gh release view "$TAG" --repo "$REPO" >/dev/null 2>&1 \
    || gh release create "$TAG" --repo "$REPO" --title "Local dev assets" \
         --notes "Database dump and wp-content assets for local development. Rebuilt periodically."
  gh release upload "$TAG" --repo "$REPO" --clobber \
    "$OUT/dev-db.sql.gz" "$OUT/dev-assets.tar.gz"
  echo "==> published. Team members run ./setup.sh"
fi
