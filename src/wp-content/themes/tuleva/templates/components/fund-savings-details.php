<?php
// Fund details from ACF
$fund_isin = get_field('fund_isin');
$fund_currency = get_field('fund_currency') ?: 'EUR';
$fund_inception_date = get_field('fund_inception_date');
$fund_management_fee = get_field('fund_management_fee');
$fund_ongoing_charges = get_field('fund_ongoing_charges');
$fund_redemption_fee = get_field('fund_redemption_fee') ?: '0%';
$fund_manager_participation = get_field('fund_manager_participation');
$fund_risk_profile = get_field('fund_risk_profile');
$fund_comparison_index = get_field('fund_comparison_index');
$fund_co2_intensity = get_field('fund_co2_intensity');

// Documents (file fields return URL directly)
$prospectus_url = get_field('prospectus_file');
$terms_url = get_field('terms_file');
$model_portfolio_url = get_field('model_portfolio_file');
$key_investor_info_url = get_field('key_investor_info_file');
$investment_report_url = get_field('investment_report_file');
$previous_reports_url = get_field('previous_reports_url');
$nav_procedure_url = get_field('nav_procedure_file');
?>
<section id="details" class="pt-5 section-spacing-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="mt-5 mb-4 h4"><?php _e('Fund details', TEXT_DOMAIN) ?></h2>
                        <?php if ($fund_isin): ?>
                            <p class="fund-info__item">
                                <span class="small text-bold">ISIN</span>
                                <span><?php echo esc_html($fund_isin); ?></span>
                            </p>
                        <?php endif; ?>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Currency', TEXT_DOMAIN) ?></span>
                            <span><?php echo esc_html($fund_currency); ?></span>
                        </p>
                        <?php if ($fund_inception_date): ?>
                            <p class="fund-info__item">
                                <span class="small text-bold"><?php _e('Date of inception', TEXT_DOMAIN) ?></span>
                                <span><?php echo esc_html($fund_inception_date); ?></span>
                            </p>
                        <?php endif; ?>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Fund volume', TEXT_DOMAIN) ?></span>
                            <span><span id="savings-fund-volume"></span> EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold">NAV</span>
                            <span><span id="savings-fund-nav"></span> EUR</span>
                        </p>
                        <?php if ($fund_management_fee): ?>
                            <p class="fund-info__item">
                                <span class="small text-bold"><?php _e('Management fee', TEXT_DOMAIN) ?></span>
                                <span><?php echo esc_html($fund_management_fee); ?></span>
                            </p>
                        <?php endif; ?>
                        <?php if ($fund_ongoing_charges): ?>
                            <p class="fund-info__item">
                                <span class="small text-bold"><?php _e('Ongoing charges', TEXT_DOMAIN) ?></span>
                                <span><?php echo esc_html($fund_ongoing_charges); ?></span>
                            </p>
                        <?php endif; ?>
                        <p class="fund-info__item">
                            <span
                                class="small text-bold"><?php _e('Redemption fee and issue fee', TEXT_DOMAIN) ?></span>
                            <span><?php echo esc_html($fund_redemption_fee); ?></span>
                        </p>
                        <?php if ($fund_manager_participation): ?>
                            <p class="fund-info__item">
                                <span
                                    class="small text-bold"><?php _e("Fund manager's participation rate in fund", TEXT_DOMAIN) ?></span>
                                <span><?php echo esc_html($fund_manager_participation); ?><?php _e('units', TEXT_DOMAIN) ?></span>
                            </p>
                        <?php endif; ?>
                        <?php if ($fund_risk_profile): ?>
                            <p class="fund-info__item">
                                <span class="small text-bold"><?php _e('Risk profile', TEXT_DOMAIN) ?></span>
                                <span><?php echo esc_html($fund_risk_profile); ?></span>
                            </p>
                        <?php endif; ?>
                        <?php if ($fund_comparison_index): ?>
                            <p class="fund-info__item">
                                <span class="small text-bold"><?php _e('Comparison index', TEXT_DOMAIN) ?></span>
                                <span><?php echo esc_html($fund_comparison_index); ?></span>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 ps-md-6">
                        <h2 class="mt-5 mb-4 h4"><?php _e('Documents', TEXT_DOMAIN) ?></h2>
                        <ul class="list-style-arrow text-secondary">
                            <?php if ($prospectus_url || $terms_url): ?>
                                <li>
                                    <?php if ($prospectus_url): ?>
                                        <a href="<?php echo esc_url($prospectus_url); ?>"
                                           target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a>
                                    <?php endif; ?>
                                    <?php if ($prospectus_url && $terms_url): _e(' and ', TEXT_DOMAIN); endif; ?>
                                    <?php if ($terms_url): ?>
                                        <a href="<?php echo esc_url($terms_url); ?>"
                                           target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a>
                                    <?php endif; ?>
                                    <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                </li>
                            <?php endif; ?>
                            <?php if ($model_portfolio_url): ?>
                                <li>
                                    <a href="<?php echo esc_url($model_portfolio_url); ?>"
                                       target="_blank"><?php _e('Model portfolio', TEXT_DOMAIN) ?></a>
                                    <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                </li>
                            <?php endif; ?>
                            <?php if ($key_investor_info_url): ?>
                                <li>
                                    <a href="<?php echo esc_url($key_investor_info_url); ?>"
                                       target="_blank"><?php _e('Key Investor Information', TEXT_DOMAIN) ?></a>
                                    <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo esc_url($nav_procedure_url ?: get_nav_procedure_future_document_url()); ?>"
                                   target="_blank"><?php _e('Procedure for determining net worth of fund', TEXT_DOMAIN) ?></a>
                                <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_document_url(); ?>"
                                   target="_blank"><?php _e('Sustainability', TEXT_DOMAIN) ?></a>
                                <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_factors_document_url(); ?>"
                                   target="_blank"><?php _e('Non-consideration of adverse impacts of investment decisions on sustainability factors', TEXT_DOMAIN) ?></a>
                                <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_remuneration_document_url(); ?>"
                                   target="_blank"><?php _e('Remuneration Policy of Tuleva Fondid AS', TEXT_DOMAIN) ?></a>
                                <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                        </ul>

                        <h2 class="mt-5 mb-4 h4"><?php _e('Reports', TEXT_DOMAIN) ?></h2>
                        <ul class="list-style-arrow text-secondary">
                            <?php if ($investment_report_url): ?>
                                <li>
                                    <?php echo generate_report_link($investment_report_url, __('Investment reports', TEXT_DOMAIN)); ?>
                                    <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                    <?php if ($previous_reports_url): ?>
                                        <br>
                                        <a href="<?php echo esc_url($previous_reports_url); ?>"
                                           target="_blank"><?php _e('Previous reports', TEXT_DOMAIN) ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/aruanded/"><?php _e('Financial reports of fund and fund manager', TEXT_DOMAIN) ?></a>
                                <?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                        </ul>

                        <?php if ($fund_co2_intensity): ?>
                            <h2 class="mt-5 mb-4 h4"><?php _e('Sustainability information', TEXT_DOMAIN) ?></h2>
                            <p class="fund-info__item">
                                <span class="small text-bold"><?php _e('CO2 intensity', TEXT_DOMAIN) ?></span>
                                <span><?php echo sprintf(__('%s tons / $1M turnover per year', TEXT_DOMAIN), esc_html($fund_co2_intensity)) ?></span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
