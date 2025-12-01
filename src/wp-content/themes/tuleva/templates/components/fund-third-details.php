<section id="details" class="pt-5 section-spacing-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="mt-5 mb-4 h4"><?php _e('Fund details', TEXT_DOMAIN) ?></h2>
                        <p class="fund-info__item">
                            <span class="small text-bold">ISIN</span>
                            <span>EE3600001707</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Currency', TEXT_DOMAIN) ?></span>
                            <span>EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Date of inception', TEXT_DOMAIN) ?></span>
                            <span><?php _e('15th October 2019', TEXT_DOMAIN) ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Fund volume', TEXT_DOMAIN) ?></span>
                            <span><span id="third-fund-volume"></span> EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold">NAV</span>
                            <span><span id="third-fund-nav"></span> EUR</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Management fee', TEXT_DOMAIN) ?></span>
                            <span>0,21%</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Ongoing charges', TEXT_DOMAIN) ?></span>
                            <span>0,31%</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Redemption fee and issue fee', TEXT_DOMAIN) ?></span>
                            <span>0%</span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e("Fund manager's participation rate in fund", TEXT_DOMAIN) ?></span>
                            <span>0 <?php _e('units', TEXT_DOMAIN) ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Risk profile', TEXT_DOMAIN) ?></span>
                            <span><?php _e('Aggressive', TEXT_DOMAIN) ?></span>
                        </p>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('Comparison index', TEXT_DOMAIN) ?></span>
                            <span>100% MSCI ACWI (EUR)</span>
                        </p>
                    </div>
                    <div class="col-md-6 ps-md-6">
                        <h2 class="mt-5 mb-4 h4"><?php _e('Documents', TEXT_DOMAIN) ?></h2>
                        <ul class="list-style-arrow text-secondary">
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/12/TUV100-Prospekt-kehtib-alates-01.12.2025.pdf" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/05/Tuleva-III-Samba-Pensionifondi-tingimused-15.06.2025.pdf" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/07/20250301_Mudelportfell_Tuleva.pdf" target="_blank"><?php _e('Model portfolio', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/12/Pohiteave-TUV100-kehtib-alates-01.12.2025.pdf" target="_blank"><?php _e('Key Investor Information', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_nav_procedure_future_document_url(); ?>" target="_blank"><?php _e('Procedure for determining net worth of fund', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
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
                                <?php echo generate_report_link('https://tuleva.ee/wp-content/uploads/2025/11/Tuleva-III-Samba-Pensionifondi-investeeringute-aruanne-oktoober-2025.pdf',__('Investment reports', TEXT_DOMAIN)); ?><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                                <br>
                                <a href="https://www.pensionikeskus.ee/iii-sammas/vabatahtlikud-fondid/fid/81/" target="_blank"><?php _e('Previous reports', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/aruanded/ "><?php _e('Financial reports of fund and fund manager', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                        </ul>

                        <h2 class="mt-5 mb-4 h4"><?php _e('Sustainability information', TEXT_DOMAIN) ?></h2>
                        <p class="fund-info__item">
                            <span class="small text-bold"><?php _e('CO2 intensity', TEXT_DOMAIN) ?></span>
                            <span><?php echo sprintf(__('%s tons / $1M turnover per year', TEXT_DOMAIN), 80.13) ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
