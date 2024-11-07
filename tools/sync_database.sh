#!/bin/bash

# Set SSH and database credentials
REMOTE_HOST="www.tuleva.ee"
SSH_USER="TODO"
REMOTE_DB_NAME="TODO"
REMOTE_DB_USER="TODO"
REMOTE_DB_PASS="TODO"
LOCAL_DB_NAME="d56838_tuleva20"
LOCAL_DB_USER="wordpress"
LOCAL_DB_PASS="wordpress"

# Docker container name for the MySQL instance
DOCKER_DB_CONTAINER="wordpress-theme-db-1"

# WordPress tables to sync
TABLES=("wp_posts" "wp_postmeta")

# Batch size for syncing rows
BATCH_SIZE=1000

echo "Starting sync process..."

# Disable foreign key checks on the local database
docker exec $DOCKER_DB_CONTAINER mysql -u$LOCAL_DB_USER -p$LOCAL_DB_PASS -e "SET foreign_key_checks = 0;" $LOCAL_DB_NAME

# Loop over each table to sync
for TABLE in "${TABLES[@]}"; do
    # Get total number of rows in the remote table
    TOTAL_ROWS=$(ssh $SSH_USER@$REMOTE_HOST "mysql -u$REMOTE_DB_USER -p$REMOTE_DB_PASS --skip-column-names --batch --disable-pager -e 'SELECT COUNT(*) FROM $TABLE;' $REMOTE_DB_NAME" | tail -n1)

    if [ -z "$TOTAL_ROWS" ]; then
        echo "Failed to retrieve row count for table: $TABLE"
        continue
    fi

    echo "Syncing table: $TABLE. Total rows: $TOTAL_ROWS"

    # Truncate the local table before sync
    docker exec $DOCKER_DB_CONTAINER mysql -u$LOCAL_DB_USER -p$LOCAL_DB_PASS -e "TRUNCATE TABLE $TABLE;" $LOCAL_DB_NAME

    OFFSET=0

    # Sync data in batches
    while [ "$OFFSET" -lt "$TOTAL_ROWS" ]; do
        # Updated mysqldump command with --single-transaction to avoid LOCK TABLES
        ssh $SSH_USER@$REMOTE_HOST "mysqldump -u$REMOTE_DB_USER -p$REMOTE_DB_PASS --single-transaction --skip-tz-utc --no-create-info --complete-insert --skip-add-locks --quick --default-character-set=utf8mb4 $REMOTE_DB_NAME $TABLE --where='1 LIMIT $BATCH_SIZE OFFSET $OFFSET'" > /tmp/${TABLE}_batch.sql

        # Load batch into the local database using Docker
        docker exec -i $DOCKER_DB_CONTAINER mysql -u$LOCAL_DB_USER -p$LOCAL_DB_PASS $LOCAL_DB_NAME < /tmp/${TABLE}_batch.sql

        OFFSET=$((OFFSET + BATCH_SIZE))

        # Calculate and display progress
        PROGRESS=$((OFFSET * 100 / TOTAL_ROWS))
        echo "Processed $OFFSET rows from table: $TABLE... Progress: $PROGRESS% complete."

        # Clean up batch file
        rm -f /tmp/${TABLE}_batch.sql
    done

    echo "Table $TABLE synced successfully!"
done

# Re-enable foreign key checks on the local database
docker exec $DOCKER_DB_CONTAINER mysql -u$LOCAL_DB_USER -p$LOCAL_DB_PASS -e "SET foreign_key_checks = 1;" $LOCAL_DB_NAME

echo "All tables synced successfully!"
