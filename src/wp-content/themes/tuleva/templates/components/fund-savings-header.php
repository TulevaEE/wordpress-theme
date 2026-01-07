<?php
$fund_title = get_field('fund_title');
$fund_description = get_field('fund_description');
$fund_description_secondary = get_field('fund_description_secondary');
$cta_button_text = get_field('cta_button_text');
$cta_button_url = get_field('cta_button_url');
?>
<section id="savings-header" class="hero bg-hero-stocks text-navy">
    <div class="container">
        <div class="row section-spacing">
            <div class="col-lg-10 mx-auto pb-6 text-center">
                <h1 class="m-0"><?php echo esc_html($fund_title ?: __('Tuleva Savings Fund', TEXT_DOMAIN)); ?></h1>
            </div>
        </div>
    </div>
</section>

<section id="savings-proposal">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <div class="proposal">
                    <div class="proposal__body">
                        <?php if ($fund_description): ?>
                            <?php echo wp_kses_post($fund_description); ?>
                        <?php endif; ?>
                        <?php if ($fund_description_secondary): ?>
                            <?php echo wp_kses_post($fund_description_secondary); ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($cta_button_text && $cta_button_url): ?>
                    <div class="proposal__cta">
                        <div class="d-grid"><a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-primary btn-lg" data-label="fund-details-choose-savings"><?php echo esc_html($cta_button_text); ?></a></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>