<?php
$fund = [
    'isin' => 'EE3600109443',
    'inception_date' => __('27 March 2017', TEXT_DOMAIN),
    'management_fee' => '0,163%',
    'ongoing_charges' => '0,28%',
    'redemption_fee' => '0%',
    'manager_participation' => '306 250',
    'risk_profile' => __('Conservative', TEXT_DOMAIN),
    'comparison_index' => ['50% Bloomberg Barclays Global Aggregate Index (EUR)', '50% Bloomberg Barclays Euro Aggregate Bond Index (EUR)'],
];

// Documents. The ACF fields on this page are the source of truth; the URLs below are
// the pre-ACF values and render only while a field is empty. Update the field, not the
// literal — editing a literal whose field is already set changes nothing visible, which
// is a quiet way to believe a document was published.
$prospectus_url = tuleva_fund_disclosure_value('prospectus_file', get_site_url() . '/wp-content/uploads/2026/03/TUK75-ja-TUK00-Prospekt-kehtib-alates-02.03.2026.pdf');
$terms_url = tuleva_fund_disclosure_value('terms_file', get_site_url() . '/wp-content/uploads/2026/08/Tuleva-Maailma-Volakirjade-Pensionifond-tingimused-kehtib-alates-31.08.2026.pdf');
$prospectus_upcoming_url = tuleva_fund_disclosure_value('prospectus_upcoming_file', get_site_url() . '/wp-content/uploads/2026/08/TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf');
$terms_upcoming_url = tuleva_fund_disclosure_value('terms_upcoming_file', get_site_url() . '/wp-content/uploads/2026/08/TUK00-tingimused-kehtivad-alates-01.01.2027.pdf');
$upcoming_effective_date = tuleva_document_effective_date($prospectus_upcoming_url ?: $terms_upcoming_url, '01.01.2027');
$model_portfolio_url = tuleva_fund_disclosure_value('model_portfolio_file', get_site_url() . '/wp-content/uploads/2026/08/Mudelportfell-avalikustamiseks-19.08.2026-seisuga.pdf');
$key_investor_info_url = tuleva_fund_disclosure_value('key_investor_info_file', get_site_url() . '/wp-content/uploads/2026/03/Pohiteave-TUK00-kehtib-alates-19.03.2026.pdf');
$investment_report_url = tuleva_fund_disclosure_value('investment_report_file', 'https://tuleva.ee/wp-content/uploads/2026/09/Tuleva-Maailma-Volakirjade-Pensionifondi-investeeringute-aruanne-2026-08.pdf');
$previous_reports_url = tuleva_fund_disclosure_value('previous_reports_url', 'https://www.pensionikeskus.ee/ii-sammas/kohustuslikud-pensionifondid/fid/76/');
$fund_co2_intensity = tuleva_fund_disclosure_value('fund_co2_intensity', '133.80');
?>
<section id="details" class="pt-5 section-spacing-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="mt-5 mb-4 h4"><?php _e('Fund details', TEXT_DOMAIN) ?></h2>
                        <p class="fund-info__item">
                            <span class="small text-bold">ISIN</span>
                            <span><?php echo $fund['isin']; ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Currency', TEXT_DOMAIN) ?></span>
                            <span>EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Date of inception', TEXT_DOMAIN) ?></span>
                            <span><?php echo $fund['inception_date']; ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Fund volume', TEXT_DOMAIN) ?></span>
                            <span><span id="bond-fund-volume"></span> EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold">NAV</span>
                            <span><span id="bond-fund-nav"></span> EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Management fee', TEXT_DOMAIN) ?></span>
                            <span><?php echo $fund['management_fee']; ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Ongoing charges', TEXT_DOMAIN) ?></span>
                            <span><?php echo $fund['ongoing_charges']; ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Redemption fee and issue fee', TEXT_DOMAIN) ?></span>
                            <span><?php echo $fund['redemption_fee']; ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e("Fund manager's participation rate in fund", TEXT_DOMAIN) ?></span>
                            <span><?php echo $fund['manager_participation']; ?> <?php _e('units', TEXT_DOMAIN) ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Risk profile', TEXT_DOMAIN) ?></span>
                            <span><?php echo $fund['risk_profile']; ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Comparison index', TEXT_DOMAIN) ?></span>
                            <?php foreach ($fund['comparison_index'] as $index) { ?>
                                <span><?php echo $index; ?></span>
                            <?php } ?>
                        </p>
                    </div>
                    <div class="col-md-6 ps-md-6">
                        <h2 class="mt-5 mb-4 h4"><?php _e('Documents', TEXT_DOMAIN) ?></h2>
                        <ul class="list-style-arrow text-secondary">
                            <?php if ($prospectus_url || $terms_url): ?>
                            <li>
                                <a href="<?php echo esc_url($prospectus_url); ?>" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo esc_url($terms_url); ?>" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                <?php if ($prospectus_upcoming_url || $terms_upcoming_url): ?>
                                    <br>
                                    <a href="<?php echo esc_url($prospectus_upcoming_url); ?>" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo esc_url($terms_upcoming_url); ?>" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php printf(__(' (in Estonian, effective from %s)', TEXT_DOMAIN), esc_html($upcoming_effective_date)); ?>
                                <?php endif; ?>
                            </li>
                            <?php endif; ?>
                            <?php if ($model_portfolio_url): ?>
                            <li>
                                <a href="<?php echo esc_url($model_portfolio_url); ?>" target="_blank"><?php _e('Model portfolio', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <?php endif; ?>
                            <?php if ($key_investor_info_url): ?>
                            <li>
                                <a href="<?php echo esc_url($key_investor_info_url); ?>" target="_blank"><?php _e('Key Investor Information', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo get_nav_procedure_document_url(); ?>" target="_blank"><?php _e('Procedure for determining net worth of fund', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                <?php if (get_nav_procedure_upcoming_document_url()): ?>
                                    <br>
                                    <a href="<?php echo get_nav_procedure_upcoming_document_url(); ?>" target="_blank"><?php _e('Procedure for determining net worth of fund', TEXT_DOMAIN) ?></a><?php printf(__(' (in Estonian, effective from %s)', TEXT_DOMAIN), get_nav_procedure_upcoming_effective_date()); ?>
                                <?php endif; ?>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_document_url(); ?>" target="_blank"><?php _e('Sustainability', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_factors_document_url(); ?>" target="_blank"><?php _e('Non-consideration of adverse impacts of investment decisions on sustainability factors', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_remuneration_document_url(); ?>" target="_blank"><?php _e('Remuneration Policy of Tuleva Fondid AS', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                        </ul>

                        <h2 class="mt-5 mb-4 h4"><?php _e('Reports', TEXT_DOMAIN) ?></h2>
                        <ul class="list-style-arrow text-secondary">
                            <li>
                                <?php echo generate_report_link($investment_report_url, __('Investment reports', TEXT_DOMAIN)); ?><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                <?php if ($previous_reports_url): ?>
                                    <br>
                                    <a href="<?php echo esc_url($previous_reports_url); ?>" target="_blank"><?php _e('Previous reports', TEXT_DOMAIN) ?></a>
                                <?php endif; ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/aruanded/ "><?php _e('Financial reports of fund and fund manager', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                        </ul>

                        <h2 class="mt-5 mb-4 h4"><?php _e('Sustainability information', TEXT_DOMAIN) ?></h2>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('CO2 intensity', TEXT_DOMAIN) ?></span>
                            <span><?php echo sprintf(__('%s tons / $1M turnover per year', TEXT_DOMAIN), esc_html($fund_co2_intensity)) ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo fund_schema_script($fund); ?>
