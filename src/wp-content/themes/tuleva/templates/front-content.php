<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container row-spacing-half">
        <div class="row">
            <div class="col-md-6">
                <h1 class="text-center landing-page-headline"><?php the_field('heading'); ?></h1>
                <?php if ( is_active_sidebar( 'landing_page_widget_area' ) ) : ?>
                    <?php dynamic_sidebar( 'landing_page_widget_area' ); ?>
                <?php endif; ?>
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
