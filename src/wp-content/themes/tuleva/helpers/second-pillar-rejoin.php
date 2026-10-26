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
        3 => __('<span class="rejoin-cta-line">If you took money out of your II&nbsp;pillar in 2021, you can apply now.</span><br><span class="rejoin-cta-line">Do it by <b>31&nbsp;March</b> and you\'ll be saving in your II&nbsp;pillar again from September.</span>', TEXT_DOMAIN),
        7 => __('<span class="rejoin-cta-line">If you took money out of your II&nbsp;pillar in 2021, you can apply now.</span><br><span class="rejoin-cta-line">Do it by <b>31&nbsp;July</b> and you\'ll be saving in your II&nbsp;pillar again from January.</span>', TEXT_DOMAIN),
        11 => __('<span class="rejoin-cta-line">If you took money out of your II&nbsp;pillar in 2021, you can apply now.</span><br><span class="rejoin-cta-line">Do it by <b>30&nbsp;November</b> and you\'ll be saving in your II&nbsp;pillar again from May.</span>', TEXT_DOMAIN),
    };
}
