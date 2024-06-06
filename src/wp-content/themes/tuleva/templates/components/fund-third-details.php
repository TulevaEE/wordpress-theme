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
                            <div>0,23%</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold">
                                <?php _e('Total expense ratio', TEXT_DOMAIN) ?>
                                <span
                                    class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?php echo sprintf(
                                        __("Starting from %s fund's depositary fee was changed and ongoing charges are %s%%. Ongoing charges based on expenses for year %d are %s%%. Ongoing charges may vary from year to year.", TEXT_DOMAIN),
                                        "01.10.2023",
                                        "0,33",
                                        date("Y") - 1,
                                        "0,34"
                                    );  ?>"
                                ></span>
                            </div>
                            <div>0,33%</div>
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
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2024/02/TUV100-Prospekt-16.02.2024.pdf" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/09/Tuleva-III-Samba-Pensionifondi-Tingimused_01.09.2022.pdf" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2024/03/Mudelportfell-01.03.2024.pdf" target="_blank"><?php _e('Model portfolio (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2024/02/Pohiteave-TUV100-16.02.2024.pdf" target="_blank"><?php _e('Key Investor Information (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2021/01/Fondide-vara-puhasvaartuse-maaramise-kord_01.01.2021.pdf" target="_blank"><?php _e('Procedure for determining net worth of fund (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_esg_document_url(); ?>" target="_blank"><?php _e('Sustainability', TEXT_DOMAIN) ?></a>
                            </li>
                        </ul>

                        <h5 class="mb-4"><?php _e('Reports', TEXT_DOMAIN) ?></h5>
                        <ul class="list-style-arrow mb-5">
                            <li>
                                <?php _e('Investment reports (in Estonian)', TEXT_DOMAIN) ?>
                                <br>
                                <?php echo generate_report_link('https://tuleva.ee/wp-content/uploads/2024/06/TUV100-investeeringute-aruanne-31.05.2024.pdf'); ?>
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
                            <?php echo sprintf(__('%s tons / $1M turnover per year', TEXT_DOMAIN), 82.26) ?>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold"><?php _e('Share of investments with increased negative impact in the equity portfolio', TEXT_DOMAIN) ?></div>
                            <?php echo sprintf(
                                __('Controversial Weapons (%s%%); Nuclear Weapons (%s%%); Civilian Firearms (%s%%); Tobacco (%s%%); UN Global Compact Violators (%s%%); Thermal Coal (%s%%); Oil Sands (%s%%)', TEXT_DOMAIN),
                                '0.00', // Controversial Weapons
                                '0.00', // Nuclear Weapons
                                '0.00', // Civilian Firearms
                                '0.00', // Tobacco
                                '0.00', // UN Global Compact Violators
                                '0.00', // Thermal Coal
                                '0.00'  // Oil Sands
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
