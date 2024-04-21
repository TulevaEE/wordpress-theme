<div class="page-container">
    <script>
        var LANGCODE = '<?php echo apply_filters( "wpml_current_language", NULL );  ?>'; // eslint-disable-line
    </script>

    <?php if ( have_posts() ) while ( have_posts() ) : the_post();
        if (have_rows('page_components')) {
            while (have_rows('page_components')) { the_row();
                if (get_row_layout() === 'mutual_hero') {
                    get_template_part('templates/components/mutual-hero');
                } else if (get_row_layout() === 'mutual_proposal') {
                    get_template_part('templates/components/mutual-proposal');
                } else if (get_row_layout() === 'team_hero') {
                    get_template_part( 'templates/components/team-hero' );
                 } else if (get_row_layout() === 'fees_hero') {
                    get_template_part( 'templates/components/fees-hero' );
                } elseif (get_row_layout() === 'cta_block') {
                    get_template_part('templates/components/cta-change');
                } elseif (get_row_layout() === 'emphasis_cta_block') {
                    get_template_part('templates/components/emphasis-cta-block');
                } else if (get_row_layout() === 'story') {
                    get_template_part('templates/components/story');
                } else if (get_row_layout() === 'goals') {
                    get_template_part('templates/components/goals');
                } else if (get_row_layout() === 'middle_cta') {
                    get_template_part('templates/components/middle-cta');
                } else if (get_row_layout() === 'founder_carousel') {
                    get_template_part('templates/components/founder-carousel');
                } else if (get_row_layout() === 'signup_block') {
                    get_template_part('templates/components/signup-block');
                } else if (get_row_layout() === 'login_block') {
                    get_template_part('templates/components/login-block');
                } else if (get_row_layout() === 'steps_block') {
                    get_template_part('templates/components/steps-block');
                } else if (get_row_layout() === 'text_block') {
                    get_template_part('templates/components/text-block');
                } else if (get_row_layout() === 'rich_text_block') {
                    get_template_part('templates/components/rich-text-block');
                } else if (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                 } else if (get_row_layout() === 'accordion_block') {
                    get_template_part('templates/components/accordion-block');
                } else if (get_row_layout() === 'third_pillar_hero') {
                    get_template_part('templates/components/third-pillar-hero');
                } else if (get_row_layout() === 'second_pillar_payment_rate_hero') {
                    get_template_part('templates/components/second-pillar-payment-rate-hero');
                } else if (get_row_layout() === 'featured_articles') {
                    get_template_part('templates/components/featured-articles');
                } else if (get_row_layout() === 'media_block_with_cta') {
                    get_template_part('templates/components/tuleva-difference');
                } else if (get_row_layout() === 'media_block') {
                    get_template_part('templates/components/media-block/media-block');
                } if (get_row_layout() === 'login_cta') {
                    get_template_part( 'templates/components/login-cta' );
                } else if (get_row_layout() === 'calculator_cta') {
                    get_template_part('templates/components/calculator-cta');
                }
            }
        }

        get_template_part('templates/components/modal-security');
        get_template_part('templates/components/modal-founders');
        get_template_part('templates/components/modal-question-rights');
        get_template_part('templates/components/modal-second-pillar-payment-rate');

    endwhile; ?>
</div>
