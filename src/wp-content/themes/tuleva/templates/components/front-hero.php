<section id="<?php the_sub_field('component_id'); ?>">
    <div class="bg-hero-main d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center py-5">
                <div class="col-lg-6 text-center text-lg-left pr-lg-5 pr-lg-6">
                    <h1><?php the_sub_field('heading'); ?></h1>
                    <p class="lead text-navy"><?php the_sub_field('lead_text'); ?></p>

                    <?php if (get_sub_field('button_url') && get_sub_field('button_text')) { ?>
                        <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-primary btn-lg btn-block mb-3"><?php the_sub_field('button_text'); ?></a>
                    <?php } ?>

                    <p class="small text-navy mb-md-5 mb-lg-0"><?php the_sub_field('small_text'); ?></p>
                </div>
                <div class="col-lg-6">
                    <?php get_template_part('templates/components/front-hero/calculator'); ?>
                    <?php if (get_sub_field('below_calculator_link_url') && get_sub_field('below_calculator_link_text')) { ?>
                        <a href="<?php the_sub_field('below_calculator_link_url'); ?>" class="btn btn-link btn-block text-medium text-uppercase text-center my-3"><?php the_sub_field('below_calculator_link_text'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Credentials -->
        <div class="container-fluid bg-blueish-gray d-none d-sm-block py-4">
            <div class="container">
                <div class="row">
                    <div class="col col-md-6 d-flex">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-lock.svg" alt="<?php the_sub_field('security_text'); ?>" class="mr-4">
                        <?php if (get_sub_field('security_text')) { ?>
                            <div class="d-flex flex-column justify-content-center">
                                <p class="mb-2 text-navy"><?php the_sub_field('security_text'); ?></p>
                                <?php if (get_sub_field('security_link_url') && get_sub_field('security_link_text')) { ?>
                                    <a id="security" href="<?php the_sub_field('security_link_url'); ?>" class="text-uppercase text-medium"><?php the_sub_field('security_link_text'); ?></a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (get_sub_field('members_count') && get_sub_field('members_count_description')) { ?>
                        <div class="col-md-6 d-none d-md-flex align-items-center">
                            <span class="membercount mr-4"><?php the_sub_field('members_count'); ?></span>
                            <p class="mb-0 text-navy"><?php the_sub_field('members_count_description'); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
