<?php
ini_set('memory_limit', '1024M');

// Remote database variables
$remote_db = [
    'host' => 'TODO',
    'user' => 'TODO',
    'pass' => 'TODO',
    'name' => 'd56838_tuleva20'
];

// Database configuration for local and remote instances
$local_db = [
    'host' => 'db', // Docker service name for MySQL
    'user' => 'wordpress',
    'pass' => 'wordpress',
    'name' => 'd56838_tuleva20'
];

// WordPress content tables to sync
$tables = [
    'wp_posts',
    'wp_postmeta',
];

echo "Starting...<br>";
error_log("Starting..."); // Log to Docker

// Connect to local and remote databases
$local_conn = new mysqli($local_db['host'], $local_db['user'], $local_db['pass'], $local_db['name']);
$remote_conn = new mysqli($remote_db['host'], $remote_db['user'], $remote_db['pass'], $remote_db['name']);

// Set character set to utf8mb4 for both connections
$local_conn->set_charset("utf8mb4");
$remote_conn->set_charset("utf8mb4");

// Check connections
if ($local_conn->connect_error) {
    error_log("Local Database Connection Failed: " . $local_conn->connect_error);
    die("Local Database Connection Failed: " . $local_conn->connect_error);
}
if ($remote_conn->connect_error) {
    error_log("Remote Database Connection Failed: " . $remote_conn->connect_error);
    die("Remote Database Connection Failed: " . $remote_conn->connect_error);
}

// Function to export and replace data in batches with progress
function sync_table_data($table, $remote_conn, $local_conn) {
    $batch_size = 1000;
    $offset = 0;

    // Get total number of rows in the table
    $result = $remote_conn->query("SELECT COUNT(*) AS total_rows FROM $table");
    $row = $result->fetch_assoc();
    $total_rows = $row['total_rows'];

    $start_message = "Syncing table: $table. Total rows: $total_rows";
    echo "$start_message<br>";
    error_log($start_message);

    $local_conn->query("SET foreign_key_checks = 0");
    $local_conn->query("TRUNCATE TABLE $table");

    do {
        $result = $remote_conn->query("SELECT * FROM $table LIMIT $batch_size OFFSET $offset");
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $row_count = count($rows);

        if ($row_count > 0) {
            foreach ($rows as $row) {
                // Check and replace '0000-00-00 00:00:00' in datetime fields
                foreach ($row as $key => $value) {
                    if ($value === '0000-00-00 00:00:00') {
                        $row[$key] = NULL; // Set to NULL or default date
                    }
                }

                $columns = implode(", ", array_keys($row));
                $values = implode("', '", array_map([$local_conn, 'real_escape_string'], array_values($row)));
                $query = "INSERT INTO $table ($columns) VALUES ('$values')";
                $local_conn->query($query);
            }
            $offset += $batch_size;

            // Calculate and echo progress percentage
            $progress = min(100, ($offset / $total_rows) * 100);
            $progress_message = "Processed $offset rows from table: $table... Progress: " . round($progress, 2) . "% complete.";
            echo "$progress_message<br>";
            error_log($progress_message);
        }

    } while ($row_count > 0);

    $local_conn->query("SET foreign_key_checks = 1");
}

// Sync process (Production Run)
$total_tables = count($tables);
$current_table = 1;

foreach ($tables as $table) {
    $start_table_message = "[$current_table/$total_tables] Starting sync for table: $table...";
    echo "$start_table_message<br>";
    error_log($start_table_message);

    sync_table_data($table, $remote_conn, $local_conn);

    $complete_message = "Table $table synced successfully!";
    echo "$complete_message<br><br>";
    error_log($complete_message);

    $current_table++;
}

// Close connections
$local_conn->close();
$remote_conn->close();

$final_message = "All tables synced successfully!";
echo $final_message;
error_log($final_message);
?>
