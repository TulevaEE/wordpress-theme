<main id="main" class="page-container landing-page second-pillar-rejoin-page">

    <section class="hero bg-hero-rejoin section-spacing">
        <div class="container">
            <div class="row align-items-center gy-5 gx-xl-5">
                <div class="col-lg-6 text-center text-lg-start text-navy">
                    <span class="eyebrow"><?php _e('Rejoining the II pillar', TEXT_DOMAIN); ?></span>
                    <h1 class="mb-4"><?php _e('Get the <em>tax win</em> again', TEXT_DOMAIN); ?></h1>
                    <p class="m-0 lead"><?php _e('<b>It\'s&nbsp;your&nbsp;money.</b> Direct part of your taxes into your own&nbsp;account. Apply by <b>30&nbsp;November</b> and saving starts in&nbsp;May.', TEXT_DOMAIN); ?></p>
                </div>

                <div class="col-lg-6" id="kalkulaator">
                    <div class="card calculator rounded-4">
                        <div class="card-body p-2">
                            <div class="bg-gray-2 p-3 rounded-3">
                                <div class="align-items-center row">
                                    <label class="col-sm-6 col-lg-5 col-xl-6 col-form-label pe-0" for="calcSalary"><?php _e('Gross salary', TEXT_DOMAIN); ?></label>
                                    <div class="col-sm-6 col-lg-7 col-xl-6">
                                        <div class="input-group input-group-lg">
                                            <input class="form-control text-end" type="number" id="calcSalary" min="0" max="99999" step="100" placeholder="2000" inputmode="numeric">
                                            <span class="input-group-text">&euro;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 pt-4 pb-3 rejoin-sum" role="status" aria-live="polite">
                                <div class="rejoin-sum__row">
                                    <span class="rejoin-sum__operator" aria-hidden="true"></span>
                                    <span class="rejoin-sum__label"><?php _e('From your net salary', TEXT_DOMAIN); ?></span>
                                    <span class="rejoin-sum__amount" id="resNetSalary">94&nbsp;&euro;</span>
                                </div>
                                <div class="rejoin-sum__row rejoin-sum__row--tax-win">
                                    <span class="rejoin-sum__operator" aria-hidden="true">+</span>
                                    <span class="rejoin-sum__label"><?php _e('Tax win', TEXT_DOMAIN); ?><span class="inline-help d-inline-block" id="taxWinInfo" role="button" tabindex="0"
                                              aria-label="<?php echo esc_attr__('How the tax win is made up', TEXT_DOMAIN); ?>"
                                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                                              data-income-tax-label="<?php echo esc_attr__('Income tax savings', TEXT_DOMAIN); ?>"
                                              data-social-tax-label="<?php echo esc_attr__('From social tax (4% of gross salary)', TEXT_DOMAIN); ?>"
                                              title=""></span>
                                    </span>
                                    <span class="rejoin-sum__amount" id="resTaxWin">106&nbsp;&euro;</span>
                                </div>
                                <div class="rejoin-sum__row rejoin-sum__row--total">
                                    <span class="rejoin-sum__operator" aria-hidden="true">=</span>
                                    <span class="rejoin-sum__label"><?php _e('Into your II pillar each month', TEXT_DOMAIN); ?></span>
                                    <span class="rejoin-sum__amount" id="resTotal">200&nbsp;&euro;</span>
                                </div>
                                <div class="rejoin-sum__ten-years">
                                    <span><?php _e('Saved in 10 years', TEXT_DOMAIN); ?></span>
                                    <span class="fw-medium" id="resTenYears">24&nbsp;000&nbsp;&euro;</span>
                                </div>
                                <a href="<?php echo get_app_url('/2nd-pillar-rejoin'); ?>" class="btn btn-primary btn-lg w-100 mt-4"><?php _e('Start saving again', TEXT_DOMAIN); ?></a>
                            </div>
                        </div>
                    </div>
                    <p class="text-secondary text-center mt-3 mb-0"><?php _e('The calculation assumes you contribute 6% of your gross salary. Social tax paid into the II pillar also has a small effect on your I pillar.', TEXT_DOMAIN); ?></p>
                </div>

            </div>
        </div>
    </section>

</main>
