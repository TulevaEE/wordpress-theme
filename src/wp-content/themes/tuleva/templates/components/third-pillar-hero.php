<section id="<?php the_sub_field('component_id'); ?>">
    <div class="bg-hero-mutual d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center py-5">
                <div class="col-lg-6 text-center text-lg-start pr-lg-5 pe-lg-6">
                    <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>
                    <?php if (get_sub_field('quote')) { ?>
                        <p class="lead text-navy mb-3"><?php echo do_shortcode(get_sub_field('quote')); ?></p>
                        <?php if (get_sub_field('source')) { ?>
                            <p class="text-navy mb-5">
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
                        <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-primary btn-lg btn-block mb-3"><?php the_sub_field('button_text'); ?></a>
                    <?php } ?>
                </div>
                <div class="col-lg-6">
                    <?php get_template_part('templates/components/third-pillar-calculator'); ?>
                    <?php if (get_sub_field('below_calculator_link_url') && get_sub_field('below_calculator_link_text')) { ?>
                        <div class="my-3 text-center">
                            <a href="<?php the_sub_field('below_calculator_link_url'); ?>" target="_blank">
                                <?php the_sub_field('below_calculator_link_text'); ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
