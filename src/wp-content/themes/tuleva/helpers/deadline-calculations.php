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
 * Returns 3rd pillar year-end countdown end timestamp (ms) if within $days_before working days, null otherwise.
 * 3rd pillar deadline: last working day of year minus 2 working days at 15:59:59 Tallinn time.
 */
function get_third_pillar_countdown_if_active($days_before = 14) {
    $year = get_current_year_tallinn();
    $deadline = get_third_pillar_deadline($year);

    if (!is_within_days_before_deadline($deadline, $days_before)) {
        return null;
    }

    return $deadline->getTimestamp() * 1000;
}

/**
 * Returns 2nd pillar deadlines for a given year.
 * 2nd pillar has 3 deadlines: March 31, July 31, November 30 at 23:59:59 Tallinn time.
 */
function get_second_pillar_deadlines($year) {
    $timezone = get_tallinn_timezone();
    return [
        new DateTime("$year-03-31 23:59:59", $timezone),
        new DateTime("$year-07-31 23:59:59", $timezone),
        new DateTime("$year-11-30 23:59:59", $timezone),
    ];
}

/**
 * Returns active 2nd pillar countdown info if within 14 working days of a deadline.
 * Returns ['end_ms' => timestamp, 'deadline_name' => 'Month Day'] or null if no active countdown.
 */
function get_second_pillar_countdown_if_active() {
    $year = get_current_year_tallinn();
    $now = time() * 1000;
    $deadlines = get_second_pillar_deadlines($year);

    foreach ($deadlines as $deadline) {
        $countdown_start = calculate_working_days_before_date($deadline, 14);
        $start_ms = $countdown_start->getTimestamp() * 1000;
        $end_ms = $deadline->getTimestamp() * 1000;

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

function get_third_pillar_deadline($year) {
    $december31 = new DateTime("$year-12-31", get_tallinn_timezone());
    $lastWorkingDay = get_last_working_day_on_or_before($december31);
    $deadline = calculate_working_days_before_date($lastWorkingDay, 2);
    $deadline->setTime(15, 59, 59);
    return $deadline;
}

function get_last_working_day_on_or_before($date) {
    $result = clone $date;
    $dayOfWeek = (int)$result->format('N');

    // If Saturday (6), go back to Friday
    if ($dayOfWeek === 6) {
        $result->modify('-1 day');
    }
    // If Sunday (7), go back to Friday
    elseif ($dayOfWeek === 7) {
        $result->modify('-2 days');
    }

    return $result;
}

function is_within_days_before_deadline($deadline, $days) {
    $currentDate = get_current_time_tallinn();
    $daysDifference = $currentDate->diff($deadline)->days;
    $isBeforeDeadline = $currentDate < $deadline;
    return $isBeforeDeadline && $daysDifference <= $days;
}