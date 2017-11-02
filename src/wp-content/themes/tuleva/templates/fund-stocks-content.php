<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post();

        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) { the_row();
                if (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }

    endwhile; ?>
</div>
