<section id="choose-fund" class="py-6">
    <div class="container text-center">
        <h2 class="mb-5">
            <?php _e('Which Tuleva pension fund to choose?', TEXT_DOMAIN) ?>
        </h2>
        <div class="row">
            <div class="col">
                <div class="card-deck">
                    <div class="card shadow-sm">
                        <div class="card-body text-center pt-5">
                            <h6 class="mb-4">
                                <?php _e('If you are less than 55 years old, then the fund suitable to you is', TEXT_DOMAIN); ?>
                            </h6>
                            <h4>
                                <?php _e('<a href="/en/tuleva-world-stocks-pension-fund/" target="_blank" class="text-navy">Tuleva World Stocks Pension Fund</a>', TEXT_DOMAIN); ?>
                            </h4>
                            <img src="<?php echo get_template_directory_uri() ?>/img/stocks-pie.svg" class="img-fluid my-5" alt="<?php _e('Tuleva World Stocks Pension Fund', TEXT_DOMAIN); ?>">

                            <div class="bg-white mb-4">
                                <?php _e('<a href="/en/tuleva-world-stocks-pension-fund/" class="text-uppercase text-medium d-block mb-4">See fund info</a>', TEXT_DOMAIN); ?>
                                <?php _e('<a href="/en/online-bank-instructions/" class="btn btn-primary btn-lg btn-block" id="fund-choose-stocks">Select this fund</a>', TEXT_DOMAIN); ?>
                            </div>
                            <hr class="mb-4">
                            <p class="text-left"><?php  echo sprintf( __('Low cost: management fee %s0,34%%%s and total expense ratio %s0,45%%%s', TEXT_DOMAIN), '<span class="text-highlight"><strong>', '</strong></span>', '<span class="text-highlight"><strong>', '</strong></span>' ); ?></p>
                            <ul class="text-left list-style-checkmark mb-0">
                                <li><?php _e('Most of the money is invested in stocks: bigger expected return and risk', TEXT_DOMAIN); ?></li>
                                <li><?php _e('Suitable for you if you want <span class="text-highlight"><strong>best expected return</strong></span> over long term and you are not disturbed by short-term fluctuations of the market', TEXT_DOMAIN); ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body text-center pt-5">
                            <h6 class="mb-4">
                                <?php _e('If you are over 55, the fund better suited for you could be', TEXT_DOMAIN); ?>
                            </h6>
                            <h4>
                                <?php _e('<a href="/en/tuleva-world-bonds-pension-fund/" target="_blank" class="text-navy">Tuleva World Bonds Pension Fund</a>', TEXT_DOMAIN); ?>
                            </h4>
                            <img src="<?php echo get_template_directory_uri() ?>/img/volakirjad-pie.svg?v2" class="img-fluid my-5" alt="<?php _e('Tuleva World Bonds Pension Fund', TEXT_DOMAIN); ?>">
                            <div class="bg-white mb-4">
                                <?php _e('<a href="/en/tuleva-world-bonds-pension-fund/" class="text-uppercase text-medium d-block mb-4">See fund info</a>', TEXT_DOMAIN); ?>
                                <?php _e('<a href="/en/online-bank-instructions/" class="btn btn-primary btn-lg btn-block" id="fund-choose-bonds">Select this fund</a>', TEXT_DOMAIN); ?>
                            </div>
                            <hr class="mb-4">
                            <p class="text-left"><?php _e('Low cost: management fee <span class="text-highlight"><strong>0,34%</strong></span> and total expenses <span class="text-highlight"><strong>0,47%</strong></span>', TEXT_DOMAIN); ?></p>
                            <ul class="text-left list-style-checkmark mb-0">
                                <li><?php _e('Money is invested into world goverments’ and governmental organisations’ bonds: smaller risk, smaller expected return', TEXT_DOMAIN); ?></li>
                                <li><?php _e('This suits you in case you have just a few years till retirement or in case you are willing to forgo returns in order to avoid losses', TEXT_DOMAIN); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
