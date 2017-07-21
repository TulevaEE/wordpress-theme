<div class="page-container">
    <div class="page-container funds-container">
        <div class="page-container funds-container-hero">

            <div class="funds-container-hero-gradient">
                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                <div class="container">
                    <!--
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="h1-sm text-center"><?php the_field('heading'); ?></h1>
                        </div>
                    </div>
                    -->
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="h1-sm text-center"><?php _e('Aged 18 - 55? For you suits <br>Tuleva World Stocks Pension Fund.', TEXT_DOMAIN); ?></h1>
                        </div>
                    </div>
                    <div class="row mt-5 top-buffer">
                        <div class="col-md-12">
                            <p class="h1-sm text-center"><?php _e('This fund has been already chosen by 3420 people - including Tuleva founders and most of the members. <br> If you are over 55, consider our bond fund.<br> Changing pension fund if free for you and takes only 5 minutes in your internet bank.', TEXT_DOMAIN) ?></p>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12 text-center">
                            <?php _e('<a href="/en/transfer-pension-tuleva/" class="btn btn-primary btn-lg">Choose Tuleva pension fund</a>', TEXT_DOMAIN) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
    get_template_part('templates/components/fund-navigation');
    get_template_part('templates/components/fund-choosing');
    get_template_part('templates/components/fund-security');
    get_template_part('templates/components/fund-fees');
    get_template_part('templates/components/fund-calculator');
    //get_template_part('templates/components/fund-counter');
    ?>

    <?php
        if (have_rows('fund_components')) {
            while (have_rows('fund_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                } elseif (get_row_layout() === 'button') {
                    get_template_part('templates/components/button');
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
                } elseif (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }
    ?>



    <?php //get_template_part('templates/components/fund-qa'); ?>

    <?php endwhile; ?>
</div>
