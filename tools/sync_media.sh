#!/bin/bash

# Set SSH credentials
REMOTE_HOST="www.tuleva.ee"
REMOTE_USER="TODO"
REMOTE_UPLOADS_DIR="~/domeenid/www.tuleva.ee/htdocs/wp-content/uploads/"
LOCAL_UPLOADS_DIR="../wordpress/wp-content/uploads/"

# Rsync options:
# - `-avz` : Archive mode with verbose output and compression
# - `--ignore-existing` : Only copy files that don't exist locally (new files)
# - `--exclude=".*"` : Exclude hidden files or unwanted patterns if needed
RSYNC_OPTIONS="-avz --ignore-existing --exclude='.*'"

# Logging function to print messages to console with a timestamp
log_progress() {
    echo "$(date +'%Y-%m-%d %H:%M:%S') - $1"
}

log_progress "Starting WordPress uploads folder sync..."

# Ensure the local uploads directory exists
if [ ! -d "$LOCAL_UPLOADS_DIR" ]; then
    mkdir -p "$LOCAL_UPLOADS_DIR"
    log_progress "Created local uploads directory: $LOCAL_UPLOADS_DIR"
fi

# Sync new files only from the remote uploads directory to the local uploads directory
log_progress "Syncing new files from remote to local..."
rsync $RSYNC_OPTIONS -e ssh "$REMOTE_USER@$REMOTE_HOST:$REMOTE_UPLOADS_DIR" "$LOCAL_UPLOADS_DIR"

# Sync is complete
log_progress "Uploads folder sync completed!"
