<?php
$countdown_end_ms = get_query_var('countdown_end_ms', 0);

if (!$countdown_end_ms) {
    return;
}
?>
<div class="counter-small d-flex justify-content-center justify-content-lg-start mb-5"
     data-countdown-end="<?php echo $countdown_end_ms; ?>"
     role="timer"
     aria-label="<?php _e('Countdown timer', TEXT_DOMAIN); ?>"
     aria-live="polite"
     aria-atomic="true">
    <span class="visually-hidden"><?php _e('Countdown timer showing time remaining', TEXT_DOMAIN) ?></span>
    <div class="d-flex flex-row">
        <div class="counter-block">
            <div class="counter-number" role="group" aria-labelledby="days-label">
                <span id="days-first-number" aria-hidden="true">&nbsp;</span>
                <span id="days-last-number" aria-hidden="true">&nbsp;</span>
                <span class="visually-hidden" id="days-value"></span>
            </div>
            <div class="counter-label mt-2" id="days-label"><?php _e('days', TEXT_DOMAIN) ?></div>
        </div>
        <div class="counter-block">
            <div class="counter-number" role="group" aria-labelledby="hours-label">
                <span id="hours-first-number" aria-hidden="true">&nbsp;</span>
                <span id="hours-last-number" aria-hidden="true">&nbsp;</span>
                <span class="visually-hidden" id="hours-value"></span>
            </div>
            <div class="counter-label mt-2" id="hours-label"><?php _e('hours', TEXT_DOMAIN) ?></div>
        </div>
        <div class="counter-block">
            <div class="counter-number" role="group" aria-labelledby="minutes-label">
                <span id="minutes-first-number" aria-hidden="true">&nbsp;</span>
                <span id="minutes-last-number" aria-hidden="true">&nbsp;</span>
                <span class="visually-hidden" id="minutes-value"></span>
            </div>
            <div class="counter-label mt-2" id="minutes-label"><?php _e('minutes', TEXT_DOMAIN) ?></div>
        </div>
    </div>
</div>