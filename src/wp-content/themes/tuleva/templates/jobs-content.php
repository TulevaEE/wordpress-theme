<div id="jobs-page" class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'templates/components/jobs/hero' ); ?>
        <?php if (have_rows('sections')) {
            while (have_rows('sections')) { the_row();
                if (get_row_layout() === 'media_block') {
                    get_template_part('templates/components/jobs/media-block');
                } else if (get_row_layout() === 'lead') {
                    get_template_part('templates/components/jobs/lead');
                } else if (get_row_layout() === 'bullet_points') {
                    get_template_part('templates/components/jobs/bullet-points');
                } else if (get_row_layout() === 'job_openings') {
                    get_template_part('templates/components/jobs/job-openings');
                }
            }
        } ?>
    <?php endwhile; ?>
</div>
