<main id="main" class="page-container">
    <script>
        var LANGCODE = '<?php echo apply_filters( "wpml_current_language", NULL );  ?>'; // eslint-disable-line
    </script>

    <?php
        if (have_rows('page_components')) {
            while (have_rows('page_components')) { the_row();
                if (get_row_layout() === 'mutual_hero') {
                    get_template_part('templates/components/mutual-hero');
                } else if (get_row_layout() === 'mutual_proposal') {
                    get_template_part('templates/components/mutual-proposal');
                } else if (get_row_layout() === 'story') {
                    get_template_part('templates/components/story');
                } else if (get_row_layout() === 'goals') {
                    get_template_part('templates/components/goals');
                } elseif (get_row_layout() === 'cta_block') {
                    get_template_part('templates/components/cta-change');
                } else if (get_row_layout() === 'founder_carousel') {
                    get_template_part('templates/components/founder-carousel');
                } else if (get_row_layout() === 'signup_block') {
                    get_template_part('templates/components/signup-block');
                } else if (get_row_layout() === 'front_hero') {
                    get_template_part('templates/components/front-hero');
                }else if (get_row_layout() === 'credentials') {
                    get_template_part('templates/components/credentials');
                } else if (get_row_layout() === 'media_block_with_cta') {
                    get_template_part('templates/components/tuleva-difference');
                } else if (get_row_layout() === 'testimonials') {
                    get_template_part('templates/components/testimonials');
                } else if (get_row_layout() === 'featured_articles') {
                    get_template_part('templates/components/featured-articles');
                } else if (get_row_layout() === 'steps_block') {
                    get_template_part('templates/components/steps-block');
                } else if (get_row_layout() === 'text_block') {
                    get_template_part('templates/components/text-block');
                } else if (get_row_layout() === 'rich_text_block') {
                    get_template_part('templates/components/rich-text-block');
                } else if (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                } else if (get_row_layout() === 'notice') {
                    get_template_part('templates/components/notice');
                } else if (get_row_layout() === 'second_pillar_payment_rate_hero') {
                    get_template_part('templates/components/second-pillar-payment-rate-hero');
                    get_template_part('templates/components/modal-second-pillar-payment-rate');
                } else if (get_row_layout() === 'payout_hero') {
                    get_template_part('templates/components/payout-hero');
                    get_template_part('templates/components/modal-payout-calculator');
                } else if (get_row_layout() === 'counter_hero') {
                    get_template_part('templates/components/counter-hero');
                }
            }
        }
    ?>

    <?php
        get_template_part('templates/components/modal-calculator');
        get_template_part('templates/components/modal-security');
    ?>
</main>
