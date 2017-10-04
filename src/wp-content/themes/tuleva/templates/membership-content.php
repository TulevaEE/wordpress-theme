<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <div class="container row-spacing-half">
            <div class="col-md-offset-1 col-md-10">
                <div><h2><?php the_content(); ?></h2></div>
                <div>
                    <h1 class="text-center landing-page-headline"><?php the_field('heading'); ?></h1>
                    <div>
                        <div id="inline-login" class="inline-login"></div>
                        <link href="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/css/login.8f82b241.css" rel="stylesheet">
                        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/polyfills.bdeb56ed.js"></script>
                        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/templates/onboarding-client/static/js/login.771bcef5.js"></script>
                        <!-- <script type="text/javascript" src="http://localhost:3000/static/js/login.js"></script> -->
                    <script type="text/javascript">
                        mixpanel.track("MEMBERSHIP_SUCCESS_PAGE");
                    </script>
                    </div>
                </div>
            </div>
        </div>

    <?php

        if (have_rows('membership_components')) {
            while (have_rows('membership_components')) { the_row();
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
