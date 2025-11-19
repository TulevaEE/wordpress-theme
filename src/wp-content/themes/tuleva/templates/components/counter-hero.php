<?php
// Dynamic date calculation for determining which countdown to show
// Counter visibility is controlled via wp-admin page template selection
$current_year = date('Y');
$current_timestamp = time() * 1000; // Current time in milliseconds
$countdown_end = 0;
$deadline_name = '';

// Period 1: March 31 (2 weeks before: March 17-31)
$march_17_start = strtotime("$current_year-03-17 00:00:00") * 1000;
$march_31_end = strtotime("$current_year-03-31 23:59:59") * 1000;

// Period 2: July 31 (2 weeks before: July 17-31)
$july_17_start = strtotime("$current_year-07-17 00:00:00") * 1000;
$july_31_end = strtotime("$current_year-07-31 23:59:59") * 1000;

// Period 3: November 30 (2 weeks before: Nov 16-30)
$nov_16_start = strtotime("$current_year-11-16 00:00:00") * 1000;
$nov_30_end = strtotime("$current_year-11-30 23:59:59") * 1000;

// Period 4: December 29 (2 weeks before: Dec 15-29, adjustable for holidays)
// You can adjust the end date here based on year-end holidays
$dec_15_start = strtotime("$current_year-12-15 00:00:00") * 1000;
$dec_29_end = strtotime("$current_year-12-29 23:59:59") * 1000; // Adjust if needed (27-29)

// Determine which countdown end date to use based on current date
if ($current_timestamp >= $march_17_start && $current_timestamp <= $march_31_end) {
    $countdown_end = $march_31_end;
    $deadline_name = 'March 31';
} elseif ($current_timestamp >= $july_17_start && $current_timestamp <= $july_31_end) {
    $countdown_end = $july_31_end;
    $deadline_name = 'July 31';
} elseif ($current_timestamp >= $nov_16_start && $current_timestamp <= $nov_30_end) {
    $countdown_end = $nov_30_end;
    $deadline_name = 'November 30';
} elseif ($current_timestamp >= $dec_15_start && $current_timestamp <= $dec_29_end) {
    $countdown_end = $dec_29_end;
    $deadline_name = 'December 29'; // Update this based on actual year-end deadline
} else {
    // Default fallback - show 00:00:00 when outside countdown periods
    // This happens right after a deadline passes (e.g., Aug 1, Dec 1)
    // Set countdown_end to current time so timer shows zeros
    $countdown_end = $current_timestamp - 1000; // Set to past time to show 00:00:00
    $deadline_name = ''; // Empty for aria-label handling below
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
