<section id="<?php the_sub_field('component_id'); ?>" class="hero bg-hero-counter bg-hero-summer d-flex flex-column section-spacing">
    <div class="container my-auto">
        <div class="row align-items-center">
            <div class="col text-center text-navy" role="timer" aria-live="polite" aria-atomic="true">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>

                <p class="d-flex justify-content-center mb-5 py-md-4" aria-hidden="true">
                    <span class="counter-block">
                        <span class="counter-number">
                            <span id="days-first-number">&nbsp;</span>
                            <span id="days-last-number">&nbsp;</span>
                        </span>
                        <span class="counter-label mt-2 mt-md-3"><?php _e('days', TEXT_DOMAIN) ?></span>
                    </span>
                    <span class="counter-block">
                        <span class="counter-number">
                            <span id="hours-first-number">&nbsp;</span>
                            <span id="hours-last-number">&nbsp;</span>
                        </span>
                        <span class="counter-label mt-2 mt-md-3"><?php _e('hours', TEXT_DOMAIN) ?></span>
                    </span>
                    <span class="counter-block">
                        <span class="counter-number">
                            <span id="minutes-first-number">&nbsp;</span>
                            <span id="minutes-last-number">&nbsp;</span>
                        </span>
                        <span class="counter-label mt-2 mt-md-3"><?php _e('minutes', TEXT_DOMAIN) ?></span>
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
