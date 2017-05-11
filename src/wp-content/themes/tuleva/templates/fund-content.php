<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container row-spacing">
        <div class="row">
            <div class="col-md-12">
                <h1 class="h1-sm text-center"><?php the_field('heading'); ?></h1>
            </div>
        </div>
    </div>

    <?php
        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                } elseif (get_row_layout() === 'media') {
                    get_template_part('templates/components/media');
                } elseif (get_row_layout() === 'text_rows_block') {
                    get_template_part('templates/components/text-rows-block');
                } elseif (get_row_layout() === 'comparison_block') {
                    get_template_part('templates/components/comparison-block');
                } elseif (get_row_layout() === 'single_quote_block') {
                    get_template_part('templates/components/single-quote-block');
                } elseif (get_row_layout() === 'fund_contacts_block') {
                    get_template_part('templates/components/fund-contacts-block');
                }
            }
        }
    ?>

    <?php endwhile; ?>
</div>
