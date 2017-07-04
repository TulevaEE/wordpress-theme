<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container row-spacing-half">
        <div class="row">
            <div class="col-md-6">
                <h1 class="text-center landing-page-headline"><?php the_field('heading'); ?></h1>
                <?php if ( is_active_sidebar( 'landing_page_widget_area' ) ) : ?>
                    <?php dynamic_sidebar( 'landing_page_widget_area' ); ?>
                <?php endif; ?>
                <?php //if (isset($_GET['login'])) { ?>
                    <div>
                        <div id="inline-login" class="inline-login"></div>
                        <link href="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/css/login.efe556b6.css" rel="stylesheet">
                        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/login.b7298c72.js"></script>
                        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/polyfills.9add4524.js"></script>
<!--                        <script type="text/javascript" src="http://localhost:3000/static/js/login.js"></script>-->
<!--                        <script type="text/javascript" src="http://localhost:3000/static/js/polyfills.js"></script>-->
                    </div>
                <?php //} ?>
                <?php if (isset($_GET['signup'])) { ?>
                    <div>
                        <div id="inline-signup" class="inline-signup"></div>
                        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/signup.567d7aae.js"></script>
                        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/polyfills.f6585fb3.js"></script>
                        <!-- <script type="text/javascript" src="http://localhost:3000/static/js/signup.js"></script> -->
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-5 col-md-offset-1">
                <?php get_template_part('templates/components/pension-calculator'); ?>
            </div>
        </div>
    </div>

    <?php

        if (have_rows('front_components')) {
            while (have_rows('front_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                } elseif (get_row_layout() === 'button') {
                    get_template_part('templates/components/button');
                } elseif (get_row_layout() === 'text_block') {
                    get_template_part('templates/components/text-block');
                } elseif (get_row_layout() === 'text_rows_block') {
                    get_template_part('templates/components/text-rows-block');
                } elseif (get_row_layout() === 'quotes_block') {
                    get_template_part('templates/components/quotes-block');
                } elseif (get_row_layout() === 'testimonial_slider') {
                    get_template_part('templates/components/testimonial-slider');
                } elseif (get_row_layout() === 'hero_block') {
                    get_template_part('templates/components/hero-block');
                } elseif (get_row_layout() === 'people_slider') {
                    get_template_part('templates/components/people-slider');
                } elseif (get_row_layout() === 'tabs') {
                    get_template_part('templates/components/tabs');
                }
            }
        }
    ?>

    <?php endwhile; ?>
</div>
