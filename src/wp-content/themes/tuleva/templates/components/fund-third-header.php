<section id="mutual-header" class="bg-hero-stocks">
    <div class="container">
        <div class="row py-6">
            <div class="col-lg-10 mx-auto text-center">
                <h1 class="mb-4"><?php _e('Tuleva III Pillar Pension Fund', TEXT_DOMAIN) ?></h1>
                <h5 class=" text-normal mb-4"><?php echo sprintf(__('Share of shares %s%%', TEXT_DOMAIN), '100') ?></h5>
            </div>
        </div>
    </div>
</section>

<section id="mutual-proposal">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <div class="proposal">
                    <div class="proposal__body">
                        <h5 class="ml-md-3 mb-3"><?php _e('Suitable for you if', TEXT_DOMAIN); ?></h5>
                        <ul class="list-style-checkmark text-navy">
                            <li><?php _e('you are younger than 55 years (if you are over 55, this fund is suitable for you in combination with a low-risk fund or a bank deposit)', TEXT_DOMAIN); ?></li>
                            <li><?php echo sprintf(__('and you want to achieve %sthe best possible return%s and you are not shaken by short-term market fluctuations', TEXT_DOMAIN), '<span class="text-highlight"><strong>', '</strong></span>'); ?>.</li>
                        </ul>
                    </div>
                    <div class="proposal__cta">
                        <a href="<?php echo get_site_url(); ?>/iii-sammas-juhend" class="btn btn-primary btn-lg btn-block ga" data-label="fund-details-choose-stocks"><?php _e('Select this fund', TEXT_DOMAIN); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
