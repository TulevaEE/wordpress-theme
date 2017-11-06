<div class="page-container">


    <?php
    get_template_part('templates/components/front-header');
    get_template_part('templates/components/fund-choosing');
    get_template_part('templates/components/tuleva-difference');
    get_template_part('templates/components/testimonials');
    ?>

    <?php
        if (have_rows('front_components')) {
            while (have_rows('front_components')) { the_row();
                if (get_row_layout() === 'text_boxes') {
                    get_template_part('templates/components/text-boxes');
                } elseif (get_row_layout() === 'qa_block') {
                    get_template_part('templates/components/qa-block');
                }
            }
        }
    ?>

    <?php
    if (ICL_LANGUAGE_CODE=='et') {
        get_template_part('templates/components/featured-articles-et');
    } elseif (ICL_LANGUAGE_CODE=='en') {
        get_template_part('templates/components/featured-articles');
    }
    get_template_part('templates/components/cta-change');
    get_template_part('templates/components/modal-calculator');
    get_template_part('templates/components/modal-security');
    ?>
</div>
