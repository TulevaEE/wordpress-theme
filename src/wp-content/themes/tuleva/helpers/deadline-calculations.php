<?php

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
    $december31 = new DateTime("$year-12-31");
    return calculate_working_days_before_date($december31, 2);
}

function is_within_days_before_deadline($deadline, $days) {
    $currentDate = new DateTime();
    $daysDifference = $currentDate->diff($deadline)->days;
    $isBeforeDeadline = $currentDate < $deadline;
    return $isBeforeDeadline && $daysDifference <= $days;
}