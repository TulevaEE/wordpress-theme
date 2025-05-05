<section id="details" class="py-6">
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-4"><?php _e('Fund details', TEXT_DOMAIN) ?></h5>
                        <div class="hidden-xs fund-info__item">
                            <div class="small text-bold">ISIN</div>
                            <div>EE3600001707</div>
                        </div>
                        <div class="hidden-xs fund-info__item">
                            <div class="small text-bold"><?php _e('Currency', TEXT_DOMAIN) ?></div>
                            <div>EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Date of inception', TEXT_DOMAIN) ?></div>
                            <div><?php _e('15th October 2019', TEXT_DOMAIN) ?></div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Fund volume', TEXT_DOMAIN) ?></div>
                            <div><span id="third-fund-volume"></span> EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold">NAV</div>
                            <div><span id="third-fund-nav"></span> EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Change in value', TEXT_DOMAIN) ?></div>
                            <div>0%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Management fee', TEXT_DOMAIN) ?></div>
                            <div>0,21%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Total expense ratio', TEXT_DOMAIN) ?></div>
                            <div>0,31%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Redemption fee and issue fee', TEXT_DOMAIN) ?></div>
                            <div>0%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e("Fund manager's participation rate in fund", TEXT_DOMAIN) ?></div>
                            <div>0 <?php _e('units', TEXT_DOMAIN) ?></div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Risk profile', TEXT_DOMAIN) ?></div>
                            <div><?php _e('Aggressive', TEXT_DOMAIN) ?></div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Comparison index', TEXT_DOMAIN) ?></div>
                            <div>100% MSCI ACWI (EUR)</div>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <h5 class="mb-4"><?php _e('Documents', TEXT_DOMAIN) ?></h5>
                        <ul class="list-style-arrow mb-5">
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/03/TUV100-Prospekt-19.02.2025-kehtib-alates-03.03.2025.pdf" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/09/Tuleva-III-Samba-Pensionifondi-Tingimused_01.09.2022.pdf" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/03/20250301_Mudelportfell_Tuleva.pdf" target="_blank"><?php _e('Model portfolio (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/03/Pohiteave-TUV100-19.02.2025-kehtib-alates-03.03.2025.pdf" target="_blank"><?php _e('Key Investor Information (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2025/03/Fondide-vara-puhasvaartuse-maaramise-kord_17.04.2025.pdf" target="_blank"><?php _e('Procedure for determining net worth of fund (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_document_url(); ?>" target="_blank"><?php _e('Sustainability', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_factors_document_url(); ?>" target="_blank"><?php _e('Non-consideration of adverse impacts of investment decisions on sustainability factors', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_remuneration_document_url(); ?>" target="_blank"><?php _e('Remuneration Policy of Tuleva Fondid AS', TEXT_DOMAIN) ?></a>
                            </li>
                        </ul>

                        <h5 class="mb-4"><?php _e('Reports', TEXT_DOMAIN) ?></h5>
                        <ul class="list-style-arrow mb-5">
                            <li>
                                <?php _e('Investment reports (in Estonian)', TEXT_DOMAIN) ?>
                                <br>
                                <?php echo generate_report_link('https://tuleva.ee/wp-content/uploads/2025/04/Tuleva-III-Samba-Pensionifondi-investeeringute-aruanne-marts-2025-2.pdf'); ?>
                                <br>
                                <a href="https://www.pensionikeskus.ee/iii-sammas/vabatahtlikud-fondid/fid/81/" target="_blank"><?php _e('Previous reports', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/aruanded/ "><?php _e('Financial reports of fund and fund manager (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                        </ul>

                        <h5 class="mb-4"><?php _e('Sustainability information', TEXT_DOMAIN) ?></h5>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('CO2 intensity', TEXT_DOMAIN) ?></div>
                            <?php echo sprintf(__('%s tons / $1M turnover per year', TEXT_DOMAIN), 78.97) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
