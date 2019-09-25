<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post();

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
                } else if (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                } else if (get_row_layout() === 'third_pillar_hero') {
                    get_template_part('templates/components/third-pillar-hero');
                }
            }
        }

        get_template_part('templates/components/modal-founders');
        get_template_part('templates/components/modal-question-rights');

    endwhile; ?>
</div>
