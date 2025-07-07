<section id="<?php the_sub_field('component_id'); ?>" class="hero bg-hero-mutual d-flex flex-column section-spacing">
    <div class="container my-auto">
        <div class="row align-items-center gy-5 gy-sm-6 gx-xl-5">
            <div class="col-lg-6 text-center text-lg-start text-navy">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>
                <?php if (get_sub_field('quote')) { ?>
                    <p class="lead mb-3"><?php echo do_shortcode(get_sub_field('quote')); ?></p>
                    <?php if (get_sub_field('source')) { ?>
                        <p class="m-0">
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

                <?php if (get_sub_field('button_url') && get_sub_field('button_text')) { ?>
                    <div class="d-grid mt-5 mb-3">
                        <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-primary btn-lg"><?php the_sub_field('button_text'); ?></a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-6">
                <?php get_template_part('templates/components/third-pillar-calculator'); ?>
                <?php if (get_sub_field('below_calculator_link_url') && get_sub_field('below_calculator_link_text')) { ?>
                    <p class="m-0 mt-3 text-center">
                        <a href="<?php the_sub_field('below_calculator_link_url'); ?>" target="_blank">
                            <?php the_sub_field('below_calculator_link_text'); ?>
                        </a>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
