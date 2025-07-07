<section id="<?php the_sub_field('component_id'); ?>" class="hero bg-hero-mutual d-flex flex-column section-spacing">
    <div class="container my-auto">
        <div class="row align-items-center gy-5 gy-sm-6 gx-xl-5">
            <div class="hero-main col-lg-6 text-center text-lg-start text-navy">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>

                <?php
                $current_timestamp = time() * 1000; // Current time in milliseconds
                $november_30_end_of_day = 1733003999000; // Timestamp for 30 Nov 2024, 23:59:59

                if ($current_timestamp < $november_30_end_of_day) { ?>
                    <div class="counter-small d-flex justify-content-center justify-content-lg-start mb-5">
                        <div class="d-flex flex-row">
                            <div class="counter-block">
                                <div class="counter-number">
                                    <div id="days-first-number">&nbsp;</div>
                                    <div id="days-last-number">&nbsp;</div>
                                </div>
                                <div class="counter-label mt-2"><?php _e('Days', TEXT_DOMAIN) ?></div>
                            </div>
                            <div class="counter-block">
                                <div class="counter-number">
                                    <div id="hours-first-number">&nbsp;</div>
                                    <div id="hours-last-number">&nbsp;</div>
                                </div>
                                <div class="counter-label mt-2"><?php _e('Hours', TEXT_DOMAIN) ?></div>
                            </div>
                            <div class="counter-block">
                                <div class="counter-number">
                                    <div id="minutes-first-number">&nbsp;</div>
                                    <div id="minutes-last-number">&nbsp;</div>
                                </div>
                                <div class="counter-label mt-2"><?php _e('Minutes', TEXT_DOMAIN) ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if (get_sub_field('quote')) { ?>
                    <p class="m-0 lead"><?php echo do_shortcode(get_sub_field('quote')); ?></p>
                    <?php if (get_sub_field('source')) { ?>
                        <p class="m-0 mt-3">
                            <?php if (get_sub_field('source_link_url')) { ?>
                            <a href="<?php the_sub_field('source_link_url'); ?>" class="text-navy text-bold">
                                <?php } ?>
                                <?php the_sub_field('source'); ?><?php
                                if (get_sub_field('source_link_url')) { ?></a><?php } ?><?php
                            if (get_sub_field('source_description')) { ?>,
                                <?php the_sub_field('source_description'); ?>
                            <?php } ?>
                        </p>
                    <?php } ?>
                <?php } ?>

                <div class="d-none d-lg-block">
                    <?php if (get_sub_field('button_url') && get_sub_field('button_text')) { ?>
                        <div class="d-grid mt-5 mb-3">
                            <a href="<?php echo get_sub_field('button_url'); ?>"
                            class="btn btn-primary btn-lg">
                                <?php the_sub_field('button_text'); ?>
                            </a>
                        </div>
                    <?php } ?>

                    <?php if (get_sub_field('small_text') && get_sub_field('small_text')) { ?>
                        <p class="m-0 small text-center">
                            <?php the_sub_field('small_text'); ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <div class="hero-aside col-lg-6">
                <?php get_template_part('templates/components/second-pillar-payment-rate-calculator'); ?>
            </div>
        </div>
    </div>
</section>
