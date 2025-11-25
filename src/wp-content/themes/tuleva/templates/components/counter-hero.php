<?php
require_once get_template_directory() . '/helpers/deadline-calculations.php';

$current_year = date('Y');
$current_timestamp = time() * 1000;

$deadlines = [
    new DateTime("$current_year-03-31 23:59:59"),
    new DateTime("$current_year-07-31 23:59:59"),
    new DateTime("$current_year-11-30 23:59:59"),
    get_year_end_deadline($current_year)
];

$countdown_end = $current_timestamp - 1000;
$deadline_name = '';

foreach ($deadlines as $deadline) {
    $countdown_start = calculate_working_days_before_date($deadline, 14);
    $start_timestamp = $countdown_start->getTimestamp() * 1000;
    $end_timestamp = ($deadline->getTimestamp() + (24 * 60 * 60) - 1) * 1000;

    if ($current_timestamp >= $start_timestamp && $current_timestamp <= $end_timestamp) {
        $countdown_end = $end_timestamp;
        $deadline_name = $deadline->format('F j');
        break;
    }
}
?>
<section id="<?php the_sub_field('component_id'); ?>" class="hero bg-hero-counter bg-hero-summer d-flex flex-column section-spacing">
    <div class="container my-auto">
        <div class="row align-items-center">
            <div class="col text-center text-navy"
                 role="timer"
                 aria-live="polite"
                 aria-atomic="true"
                 aria-label="<?php echo $deadline_name ? sprintf(__('Time remaining until %s', TEXT_DOMAIN), $deadline_name) : __('Countdown timer', TEXT_DOMAIN); ?>">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>

                <span class="visually-hidden"><?php _e('Countdown timer showing time remaining', TEXT_DOMAIN); ?></span>
                <p class="d-flex justify-content-center mb-5 py-md-4" data-countdown-end="<?php echo $countdown_end; ?>">
                    <span class="counter-block">
                        <span class="counter-number" role="group" aria-labelledby="hero-days-label">
                            <span id="days-first-number" aria-hidden="true">&nbsp;</span>
                            <span id="days-last-number" aria-hidden="true">&nbsp;</span>
                            <span class="visually-hidden" id="days-value"></span>
                        </span>
                        <span class="counter-label mt-2 mt-md-3" id="hero-days-label"><?php _e('days', TEXT_DOMAIN) ?></span>
                    </span>
                    <span class="counter-block">
                        <span class="counter-number" role="group" aria-labelledby="hero-hours-label">
                            <span id="hours-first-number" aria-hidden="true">&nbsp;</span>
                            <span id="hours-last-number" aria-hidden="true">&nbsp;</span>
                            <span class="visually-hidden" id="hours-value"></span>
                        </span>
                        <span class="counter-label mt-2 mt-md-3" id="hero-hours-label"><?php _e('hours', TEXT_DOMAIN) ?></span>
                    </span>
                    <span class="counter-block">
                        <span class="counter-number" role="group" aria-labelledby="hero-minutes-label">
                            <span id="minutes-first-number" aria-hidden="true">&nbsp;</span>
                            <span id="minutes-last-number" aria-hidden="true">&nbsp;</span>
                            <span class="visually-hidden" id="minutes-value"></span>
                        </span>
                        <span class="counter-label mt-2 mt-md-3" id="hero-minutes-label"><?php _e('minutes', TEXT_DOMAIN) ?></span>
                    </span>
                </p>

                <?php if (get_sub_field('button_url') && get_sub_field('button_text')) { ?>
                    <a href="<?php echo get_sub_field('button_url'); ?>"
                        class="btn btn-primary btn-lg">
                        <?php the_sub_field('button_text'); ?>
                    </a>
                <?php } ?>

                <?php if (get_sub_field('small_text') && get_sub_field('small_text')) { ?>
                    <div class="mt-4 text-center">
                        <?php the_sub_field('small_text'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
