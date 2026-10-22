<?php

require_once __DIR__ . '/deadline-calculations.php';

/**
 * Returns the rejoin page's hero lead for the deadline still open at $now: 31 March,
 * 31 July or 30 November. The month contributions start is named in the closing lead.
 */
function get_second_pillar_rejoin_lead(DateTimeInterface $now) {
    return match ((int) get_next_second_pillar_deadline($now)->format('n')) {
        3 => __('<b>It\'s&nbsp;your&nbsp;money.</b> Direct part of your taxes into your own II&nbsp;pillar&nbsp;account again. Apply by <b>31&nbsp;March</b>.', TEXT_DOMAIN),
        7 => __('<b>It\'s&nbsp;your&nbsp;money.</b> Direct part of your taxes into your own II&nbsp;pillar&nbsp;account again. Apply by <b>31&nbsp;July</b>.', TEXT_DOMAIN),
        11 => __('<b>It\'s&nbsp;your&nbsp;money.</b> Direct part of your taxes into your own II&nbsp;pillar&nbsp;account again. Apply by <b>30&nbsp;November</b>.', TEXT_DOMAIN),
    };
}

/**
 * Returns the lead of the call to action at the bottom of the rejoin page, naming the same
 * deadline as the hero lead.
 */
function get_second_pillar_rejoin_closing_lead(DateTimeInterface $now) {
    return match ((int) get_next_second_pillar_deadline($now)->format('n')) {
        3 => __('If you took money out of your II pillar in 2021, you can apply now. Do it by <b>31&nbsp;March</b> and your II pillar starts growing again as early as September.', TEXT_DOMAIN),
        7 => __('If you took money out of your II pillar in 2021, you can apply now. Do it by <b>31&nbsp;July</b> and your II pillar starts growing again as early as January.', TEXT_DOMAIN),
        11 => __('If you took money out of your II pillar in 2021, you can apply now. Do it by <b>30&nbsp;November</b> and your II pillar starts growing again as early as May.', TEXT_DOMAIN),
    };
}
