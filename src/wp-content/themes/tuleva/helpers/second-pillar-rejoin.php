<?php

require_once __DIR__ . '/deadline-calculations.php';

/**
 * Returns the rejoin page's hero lead for the deadline still open at $now: 31 March,
 * 31 July or 30 November. The month contributions start is named in the closing lead.
 */
function get_second_pillar_rejoin_lead(DateTimeInterface $now) {
    return match ((int) get_next_second_pillar_deadline($now)->format('n')) {
        3 => __('See how much of your taxes you can direct into your own II&nbsp;pillar&nbsp;account. <b class="text-highlight text-nowrap">Deadline 31&nbsp;March.</b>', TEXT_DOMAIN),
        7 => __('See how much of your taxes you can direct into your own II&nbsp;pillar&nbsp;account. <b class="text-highlight text-nowrap">Deadline 31&nbsp;July.</b>', TEXT_DOMAIN),
        11 => __('See how much of your taxes you can direct into your own II&nbsp;pillar&nbsp;account. <b class="text-highlight text-nowrap">Deadline 30&nbsp;November.</b>', TEXT_DOMAIN),
    };
}

/**
 * Returns the lead of the call to action at the bottom of the rejoin page, naming the same
 * deadline as the hero lead.
 */
function get_second_pillar_rejoin_closing_lead(DateTimeInterface $now) {
    return match ((int) get_next_second_pillar_deadline($now)->format('n')) {
        3 => __('<span class="rejoin-cta-line">If you submitted your withdrawal application by November 2021, you can start saving again from September.</span><br><span class="rejoin-cta-line">To do that, apply by <b>31&nbsp;March</b>.</span>', TEXT_DOMAIN),
        7 => __('<span class="rejoin-cta-line">If you submitted your withdrawal application by November 2021, you can start saving again from next January.</span><br><span class="rejoin-cta-line">To do that, apply by <b>31&nbsp;July</b>.</span>', TEXT_DOMAIN),
        11 => __('<span class="rejoin-cta-line">If you submitted your withdrawal application by November 2021, you can start saving again from next May.</span><br><span class="rejoin-cta-line">To do that, apply by <b>30&nbsp;November</b>.</span>', TEXT_DOMAIN),
    };
}

/**
 * Returns the day of the rejoin deadline still open at $now, for text that names it on its own.
 */
function get_second_pillar_rejoin_deadline_label(DateTimeInterface $now) {
    return match ((int) get_next_second_pillar_deadline($now)->format('n')) {
        3 => __('31&nbsp;March', TEXT_DOMAIN),
        7 => __('31&nbsp;July', TEXT_DOMAIN),
        11 => __('30&nbsp;November', TEXT_DOMAIN),
    };
}

/**
 * Returns the same deadline as get_second_pillar_rejoin_deadline_label() in the form text leads
 * with when it names the day before what can be done by then: Estonian "30. novembrini saad".
 */
function get_second_pillar_rejoin_deadline_until_label(DateTimeInterface $now) {
    return match ((int) get_next_second_pillar_deadline($now)->format('n')) {
        3 => _x('31&nbsp;March', 'until the rejoin deadline', TEXT_DOMAIN),
        7 => _x('31&nbsp;July', 'until the rejoin deadline', TEXT_DOMAIN),
        11 => _x('30&nbsp;November', 'until the rejoin deadline', TEXT_DOMAIN),
    };
}

/**
 * Replaces {deadline} and {deadline_until} in editor-written text with the rejoin deadline still
 * open at $now, so the text moves on to the next window by itself.
 */
function with_second_pillar_rejoin_deadline(string $text, DateTimeInterface $now) {
    return strtr($text, [
        '{deadline}' => get_second_pillar_rejoin_deadline_label($now),
        '{deadline_until}' => get_second_pillar_rejoin_deadline_until_label($now),
    ]);
}
