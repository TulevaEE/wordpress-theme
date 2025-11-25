<?php
$lead_text = get_sub_field('lead_text');
?>
<section id="<?php the_sub_field('component_id'); ?>" class="hero bg-hero-main d-flex flex-column section-spacing">
    <div class="container my-auto">
        <div class="row align-items-center gy-5 gy-sm-6 gx-xl-5">
            <div class="col-lg-6 text-center text-lg-start text-navy">
                <h1><?php the_sub_field('heading'); ?></h1>
                <p class="lead"><?php echo do_shortcode($lead_text); ?></p>

                <?php if (get_sub_field('button_url') && get_sub_field('button_text')) { ?>
                    <div class="d-grid mt-5 mb-3"><a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-primary btn-lg"><?php the_sub_field('button_text'); ?></a></div>
                <?php } ?>

                <p class="small text-center mb-md-5 mb-lg-0">
                    <?php
                    $isMarch = (date('m') == 3);
                    $isJuly = (date('m') == 7);
                    $isNovember = (date('m') == 11);
                    $dayOfMonth = (int)date('j');

                    if ($isMarch && $dayOfMonth > 16) {
                        _e('The deadline for this fund exchange period is <b>March 31.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
                    } elseif ($isJuly && $dayOfMonth > 16) {
                        _e('The deadline for this fund exchange period is <b>July 31.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
                    } elseif ($isNovember && $dayOfMonth > 15) {
                        _e('The deadline for this fund exchange period is <b>November 30.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
                    } else {
                        the_sub_field('small_text');
                    }
                    ?>
                </p>
            </div>
            <div class="col-lg-6">
                <?php get_template_part('templates/components/front-hero/calculator'); ?>
                <?php if (get_sub_field('below_calculator_link_url') && get_sub_field('below_calculator_link_text')) { ?>
                    <p class="d-grid m-0 mt-3"><a href="<?php the_sub_field('below_calculator_link_url'); ?>" class="btn btn-link text-medium text-center"><?php the_sub_field('below_calculator_link_text'); ?></a></p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
