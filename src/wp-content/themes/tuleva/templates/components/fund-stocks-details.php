<section id="details" class="py-6">
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-4"><?php _e('Fund details', TEXT_DOMAIN) ?></h5>
                        <div class="fund-info__item">
                            <div class="small text-bold">ISIN</div>
                            <div>EE3600109435</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Currency', TEXT_DOMAIN) ?></div>
                            <div>EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Date of inception', TEXT_DOMAIN) ?></div>
                            <div><?php _e('27 March 2017', TEXT_DOMAIN) ?></div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Fund volume', TEXT_DOMAIN) ?></div>
                            <div><span id="stock-fund-volume">2 519 933</span> EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold">NAV</div>
                            <div><span id="stock-fund-nav">0.64186</span> EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Change in value', TEXT_DOMAIN) ?></div>
                            <div>0%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Management fee', TEXT_DOMAIN) ?></div>
                            <div>0,29%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Total expense ratio', TEXT_DOMAIN) ?></div>
                            <div>0,37%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Redemption fee and issue fee', TEXT_DOMAIN) ?></div>
                            <div>0%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e("Fund manager's participation rate in fund", TEXT_DOMAIN) ?></div>
                            <div>5 747 351 <?php _e('units', TEXT_DOMAIN) ?></div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Risk profile', TEXT_DOMAIN) ?></div>
                            <div><?php _e('Aggressive', TEXT_DOMAIN) ?></div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Comparison index', TEXT_DOMAIN) ?></div>
                            <div>100% MSCI ACWI (EUR) </div>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <h5 class="mb-4"><?php _e('Documents', TEXT_DOMAIN) ?></h5>
                        <ul class="list-style-arrow mb-5">
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/02/Prospekt.-TUK75-ja-TUK00.-21.02.2022.pdf" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo get_site_url(); ?>/wp-content/uploads/2021/03/Tuleva-Maailma-Aktsiate-Pensionifondi-tingimused-05.03.20211.pdf" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/03/Tuleva-pensionifondide-mudelportfellid-21.03.2022.pdf" target="_blank"><?php _e('Model portfolio (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/02/Põhiteave.TUK75_21.02.2022.pdf" target="_blank"><?php _e('Key Investor Information (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2021/01/Fondide-vara-puhasvaartuse-maaramise-kord_01.01.2021.pdf" target="_blank"><?php _e('Procedure for determining net worth of fund (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_document_url(); ?>" target="_blank"><?php _e('Consideration of sustainability risks', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/uudised/tuleva-esg-pohimotted/" target="_blank"><?php echo sprintf(__('Fund documents from %s (in Estonian)', TEXT_DOMAIN), '01.09.2022') ?></a>
                            </li>
                        </ul>

                        <h5 class="mb-4"><?php _e('Reports', TEXT_DOMAIN) ?></h5>
                        <ul class="list-style-arrow mb-5">
                            <li>
                                <?php _e('Investment reports (in Estonian)', TEXT_DOMAIN) ?>
                                <br>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/08/TULAPF-investeeringute-aruanne-290722.pdf" target="_blank">07.22</a>
                                <br>
                                <a href="https://www.pensionikeskus.ee/ii-sammas/kohustuslikud-pensionifondid/fid/77/" target="_blank"><?php _e('Previous reports', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/aruanded/ "><?php _e('Financial reports of fund and fund manager (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                        </ul>

                        <h5 class="mb-4"><?php _e('Sustainability information', TEXT_DOMAIN) ?></h5>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('CO2 intensity', TEXT_DOMAIN) ?></div>
                            <?php echo sprintf(__('%s tons / $1M turnover per year', TEXT_DOMAIN), 156) ?>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Share of investments with increased negative impact in the equity portfolio', TEXT_DOMAIN) ?></div>
                            <?php echo sprintf(
                                __('Controversial Weapons (%s%%); Nuclear Weapons (%s%%); Civilian Firearms (%s%%); Tobacco (%s%%); UN Global Compact Violators (%s%%); Thermal Coal (%s%%); Oil Sands (%s%%)', TEXT_DOMAIN),
                                '0.37', // Controversial Weapons
                                '0.34', // Nuclear Weapons
                                '0.10', // Civilian Firearms
                                '0.69', // Tobacco
                                '1.26', // UN Global Compact Violators
                                '0.15', // Thermal Coal
                                '0.27'  // Oil Sands
                            ); ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
