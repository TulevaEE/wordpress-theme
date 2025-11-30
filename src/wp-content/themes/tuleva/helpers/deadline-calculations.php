<?php

function get_tallinn_timezone() {
    return new DateTimeZone('Europe/Tallinn');
}

function get_current_time_tallinn() {
    return new DateTime('now', get_tallinn_timezone());
}

function get_current_year_tallinn() {
    return (int) get_current_time_tallinn()->format('Y');
}

/**
 * Returns November countdown end timestamp (ms) if currently in Nov 17-30 period, null otherwise.
 */
function get_november_countdown_end_if_active() {
    $timezone = get_tallinn_timezone();
    $year = get_current_year_tallinn();
    $now = time() * 1000;

    $start = (new DateTime("$year-11-17 00:00:00", $timezone))->getTimestamp() * 1000;
    $end = (new DateTime("$year-11-30 23:59:59", $timezone))->getTimestamp() * 1000;

    return ($now >= $start && $now <= $end) ? $end : null;
}

/**
 * Returns year-end countdown end timestamp (ms) if within $days_before working days, null otherwise.
 */
function get_year_end_countdown_end_if_active($days_before = 14) {
    $year = get_current_year_tallinn();
    $deadline = get_year_end_deadline($year);

    if (!is_within_days_before_deadline($deadline, $days_before)) {
        return null;
    }

    return ($deadline->getTimestamp() + (24 * 60 * 60) - 1) * 1000;
}

/**
 * Returns active quarterly countdown info if within 14 working days of a deadline.
 * Returns ['end_ms' => timestamp, 'deadline_name' => 'Month Day'] or null if no active countdown.
 */
function get_quarterly_countdown_if_active() {
    $timezone = get_tallinn_timezone();
    $year = get_current_year_tallinn();
    $now = time() * 1000;

    $deadlines = [
        new DateTime("$year-03-31 23:59:59", $timezone),
        new DateTime("$year-07-31 23:59:59", $timezone),
        new DateTime("$year-11-30 23:59:59", $timezone),
        get_year_end_deadline($year)
    ];

    foreach ($deadlines as $deadline) {
        $countdown_start = calculate_working_days_before_date($deadline, 14);
        $start_ms = $countdown_start->getTimestamp() * 1000;
        $end_ms = ($deadline->getTimestamp() + (24 * 60 * 60) - 1) * 1000;

        if ($now >= $start_ms && $now <= $end_ms) {
            return [
                'end_ms' => $end_ms,
                'deadline_name' => $deadline->format('F j'),
            ];
        }
    }

    return null;
}

function calculate_working_days_before_date($date, $workingDays) {
    $result = clone $date;
    $daysBack = 0;

    while ($daysBack < $workingDays) {
        $result->modify('-1 day');
        $dayOfWeek = (int)$result->format('N');
        if ($dayOfWeek < 6) {
            $daysBack++;
        }
    }

    return $result;
}

function get_year_end_deadline($year) {
    $december31 = new DateTime("$year-12-31", get_tallinn_timezone());
    return calculate_working_days_before_date($december31, 2);
}

function is_within_days_before_deadline($deadline, $days) {
    $currentDate = get_current_time_tallinn();
    $daysDifference = $currentDate->diff($deadline)->days;
    $isBeforeDeadline = $currentDate < $deadline;
    return $isBeforeDeadline && $daysDifference <= $days;
}