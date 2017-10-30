<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

        <?php
        get_template_part('templates/components/mutual-header');
        get_template_part('templates/components/mutual-proposal');
        get_template_part('templates/components/reasons');
        get_template_part('templates/components/goals');
        get_template_part('templates/components/cta-mutual');
        get_template_part('templates/components/founder-stories');
        get_template_part('templates/components/signup');
        ?>

        <?php

        if (have_rows('front_components')) {
            while (have_rows('front_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                }  elseif (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }
    ?>

    <?php endwhile; ?>
</div>
