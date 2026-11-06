<?php
require_once get_template_directory() . '/helpers/deadline-calculations.php';
$countdown_end = get_november_countdown_end_if_active();
?>
<section id="<?php the_sub_field('component_id'); ?>" class="payment-rate-hero">
    <div class="payment-rate-hero__bars d-none d-lg-block" aria-hidden="true">
        <span class="payment-rate-hero__bar payment-rate-hero__bar--upper"></span>
        <span class="payment-rate-hero__bar payment-rate-hero__bar--lower"></span>
    </div>
    <div class="container">
        <div class="row align-items-center gy-5 gx-xl-5">
            <div class="col-lg-7 text-center text-lg-start">
                <h1 class="payment-rate-hero__title"><?php the_sub_field('heading'); ?></h1>
                <?php if (get_sub_field('quote')) { ?>
                    <p class="payment-rate-hero__lead"><?php echo do_shortcode(get_sub_field('quote')); ?></p>
                <?php } ?>
                <?php if ($countdown_end) {
                    set_query_var('countdown_end_ms', $countdown_end);
                    get_template_part('templates/components/countdown-timer');
                } ?>
            </div>
            <div class="col-lg-5">
                <?php get_template_part('templates/components/second-pillar-payment-rate-calculator'); ?>
            </div>
        </div>
    </div>
</section>
