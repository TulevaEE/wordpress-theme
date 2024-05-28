<?php
$lead_text = get_sub_field('lead_text');
?>
<section id="<?php the_sub_field('component_id'); ?>">
    <div class="bg-hero-main d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center py-5">
                <div class="col-lg-6 text-center text-lg-left pr-lg-5 pr-lg-6">
                    <h1><?php the_sub_field('heading'); ?></h1>
                    <p class="lead text-navy"><?php echo do_shortcode($lead_text); ?></p>

                    <?php if (get_sub_field('button_url') && get_sub_field('button_text')) { ?>
                        <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-primary btn-lg btn-block mb-3"><?php the_sub_field('button_text'); ?></a>
                    <?php } ?>

                    <p class="small text-navy mb-md-5 mb-lg-0">
                        <?php
                        $isMarch = (date('m') == 3);
                        $isJuly = (date('m') == 7);
                        $isNovember = (date('m') == 11);
                        $dayOfMonth = (int)date('j');

                        if ($isMarch && $dayOfMonth > 21) {
                            _e('The deadline for this fund exchange period is <b>March 31.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
                        } elseif ($isJuly && $dayOfMonth > 21) {
                            _e('The deadline for this fund exchange period is <b>July 31.</b> Switching funds is <b>free.</b>', TEXT_DOMAIN);
                        } elseif ($isNovember && $dayOfMonth > 21) {
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
                        <a href="<?php the_sub_field('below_calculator_link_url'); ?>" class="btn btn-link btn-block text-medium text-uppercase text-center my-3"><?php the_sub_field('below_calculator_link_text'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
