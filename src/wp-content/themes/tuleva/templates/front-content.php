<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!--
    <div class="container row-spacing-half">
        <div class="row">
            <h1 class="text-center landing-page-headline"><?php the_field('heading'); ?></h1>
        </div>
    </div>
    -->

    <?php get_template_part('templates/components/front-proposal'); ?>

<!--        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseExample"-->
<!--                aria-expanded="false" aria-controls="collapseExample">-->
<!--            Button with data-target-->
<!--        </button>-->
<!--        <div class="collapse" id="collapseExample">-->
<!--            <div class="well">-->
<!--                ...-->
<!--            </div>-->
<!--        </div>-->


        <?php

        if (have_rows('front_components')) {
            while (have_rows('front_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                    get_template_part('templates/components/reasons');
                    get_template_part('templates/components/front-founders');
                    get_template_part('templates/components/signup');
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
