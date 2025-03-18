<section id="<?php the_sub_field('component_id'); ?>">
    <div class="bg-hero-counter bg-hero-spring d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center py-5">
                <div class="col text-center text-lg-left text-navy py-md-5">
                    <h1 class="text-center mb-5"><?php the_sub_field('heading'); ?></h1>

                    <div class="d-flex justify-content-center mb-5 py-md-4">
                        <div class="d-flex flex-row">
                            <div class="counter-block">
                                <div class="counter-number">
                                    <div id="days-first-number">&nbsp;</div>
                                    <div id="days-last-number">&nbsp;</div>
                                </div>
                                <div class="counter-label mt-2 mt-md-3"><?php _e('Days', TEXT_DOMAIN) ?></div>
                            </div>
                            <div class="counter-block">
                                <div class="counter-number">
                                    <div id="hours-first-number">&nbsp;</div>
                                    <div id="hours-last-number">&nbsp;</div>
                                </div>
                                <div class="counter-label mt-2 mt-md-3"><?php _e('Hours', TEXT_DOMAIN) ?></div>
                            </div>
                            <div class="counter-block">
                                <div class="counter-number">
                                    <div id="minutes-first-number">&nbsp;</div>
                                    <div id="minutes-last-number">&nbsp;</div>
                                </div>
                                <div class="counter-label mt-2 mt-md-3"><?php _e('Minutes', TEXT_DOMAIN) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
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
        </div>
    </div>
</section>
