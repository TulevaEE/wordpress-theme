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
                            <div><span id="third-fund-volume">0</span> EUR</div>
                        </div>
                        <div class="fund-info__item">
                            <div class="small text-bold">NAV</div>
                            <div><span id="third-fund-nav">0</span> EUR</div>
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
                            <div class="small text-bold"><?php _e('Total expense ratio', TEXT_DOMAIN) ?></div>
                            <div>0,35%</div>
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
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/02/TUV100-Prospekt-21.02.2022.pdf" target="_blank"><?php _e('Prospectus', TEXT_DOMAIN) ?></a><?php _e(' and ', TEXT_DOMAIN) ?><a href="<?php echo get_site_url(); ?>/wp-content/uploads/2021/12/Tuleva-III-Samba-Pensionifondi-tingimused.-28.05.2021.pdf" target="_blank"><?php _e('Terms and conditions', TEXT_DOMAIN) ?></a><?php _e(' (in Estonian)', TEXT_DOMAIN) ?>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/03/Tuleva-pensionifondide-mudelportfellid-21.03.2022.pdf" target="_blank"><?php _e('Model portfolio (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/02/Põhiteave.TUV100_21.02.2022.pdf" target="_blank"><?php _e('Key Investor Information (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2021/01/Fondide-vara-puhasvaartuse-maaramise-kord_01.01.2021.pdf" target="_blank"><?php _e('Procedure for determining net worth of fund (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/03/Tuleva-jatkusuutlikkusriskidega-arvestamise-poliitika.pdf" target="_blank">Jätkusuutlikkusriskide arvesse võtmine</a>
                            </li>
                        </ul>

                        <h5 class="mb-4"><?php _e('Reports', TEXT_DOMAIN) ?></h5>
                        <ul class="list-style-arrow mb-5">
                            <li>
                                <?php _e('Investment reports (in Estonian)', TEXT_DOMAIN) ?>
                                <br>
                                <a href="<?php echo get_site_url(); ?>/wp-content/uploads/2022/05/TUV100-investeeringute-aruanne-29.04.2022.pdf" target="_blank">04.22</a>
                                <br>
                                <a href="https://www.pensionikeskus.ee/iii-sammas/vabatahtlikud-fondid/fid/81/" target="_blank"><?php _e('Previous reports', TEXT_DOMAIN) ?></a>
                            </li>
                            <li>
                                <a href="<?php echo get_site_url(); ?>/aruanded/ "><?php _e('Financial reports of fund and fund manager (in Estonian)', TEXT_DOMAIN) ?></a>
                            </li>
                        </ul>

                        <?php get_template_part('templates/components/fund-sustainability-block'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
