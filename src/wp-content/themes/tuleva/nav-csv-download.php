<?php
$isin = 'EE0000003283';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 5,
    ]
]);

if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    $json = file_get_contents(
        'https://onboarding-service.tuleva.ee/v1/funds/' . $isin . '/nav',
        false,
        $context
    );
} else {
    $json = '[
        {"date": "2026-04-11", "value": 0.9927},
        {"date": "2026-04-10", "value": 0.9931},
        {"date": "2026-02-03", "value": 1.0000},
        {"date": "2026-02-02", "value": 1.0000}
    ]';
}

$data = json_decode($json, true);

if (empty($data)) {
    http_response_code(503);
    echo 'NAV data temporarily unavailable';
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="tuleva-tkf-nav.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Kuupäev', 'NAV (EUR)'], ';');

foreach ($data as $row) {
    $date = date('d.m.Y', strtotime($row['date']));
    fputcsv($output, [$date, $row['value']], ';');
}

fclose($output);
