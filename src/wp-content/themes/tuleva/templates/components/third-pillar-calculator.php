<?php
$isOctoberNovemberDecember = (date('m') >= 10);
function get_translated_link() {
    if (ICL_LANGUAGE_CODE == 'en') {
        return '#modal-gross_income';
    } elseif (ICL_LANGUAGE_CODE == 'et') {
        return '#modal-brutotulu';
    }
}
?>
<div class="card calculator br-3">
    <div class="card-body p-2 third-pillar-calculator">
        <form>
            <?php if ($isOctoberNovemberDecember) { ?>
                <div class="card bg-gray-2 p-3 br-2">
                    <div class="form-group row align-items-center">
                        <label for="yearlyWage" class="col-sm-6 col-lg-5 col-xl-6 col-form-label pr-0">
                            <?php _e('Total gross income', TEXT_DOMAIN); ?> (<a href="<?php echo esc_url( get_translated_link() ); ?>"><?php _e('view', TEXT_DOMAIN); ?></a>)
                        </label>
                        <div class="col-sm-6 col-lg-7 col-xl-6">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="yearlyWage" min="0" step="1" placeholder="24000" inputmode="numeric" oninput="validity.valid||(value='');">
                                <div class="input-group-addon"><?php _e('&euro;/year', TEXT_DOMAIN); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="wageDeduction" class="col-sm-6 col-lg-5 col-xl-6 col-form-label pr-0">
                            <?php _e('of which dividends', TEXT_DOMAIN); ?><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('Gross income, from which income tax has not been withheld as a private person. For example, dividends received from an Estonian company; dividends that have been taxed abroad.', TEXT_DOMAIN); ?>"
                            ></span>
                        </label>
                        <div class="col-sm-6 col-lg-7 col-xl-6">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="wageDeduction" inputmode="numeric" min="0" step="1" placeholder="0" oninput="validity.valid||(value='');">
                                <div class="input-group-addon"><?php _e('&euro;/year', TEXT_DOMAIN); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row align-items-center mb-0">
                        <label for="taxReliefs" class="col-sm-6 col-lg-5 col-xl-6 col-form-label pr-0">
                            <?php _e('Other tax reliefs', TEXT_DOMAIN); ?><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('Gifts and donations, education costs, including kindergarten fees. Limited to 1200 euros in total, but no more than 50 percent of taxable income in Estonia.', TEXT_DOMAIN); ?>"
                            ></span>
                        </label>
                        <div class="col-sm-6 col-lg-7 col-xl-6">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="taxReliefs" min="0" max="1200" step="1" placeholder="0" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value !== '') this.value = Math.min(this.value, this.max);">
                                <div class="input-group-addon"><?php _e('&euro;/year', TEXT_DOMAIN); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-2 p-3 py-4 card br-2 text-navy">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mr-3">
                            <span class="d-inline-block fs-6 lh-sm fw-medium"><?php _e('Yearly gain from income tax', TEXT_DOMAIN); ?></span><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('The declaration of income for this year will start on February 15 next year. Remember that you cannot get more income tax back than you paid this year.', TEXT_DOMAIN); ?>"
                            ></span>
                        </div>
                        <div class="fs-5 lh-1 fw-bold text-green text-nowrap"><span id="savingsSum">792</span> €</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4">
                        <div class="mr-3">
                            <span class="d-inline-block fs-5 lh-sm fw-medium"><?php _e('Contribute up to', TEXT_DOMAIN); ?></span><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('Contributions to the III pillar are subject to an income tax discount of up to 15% of taxable income in Estonia, but not more than 6000&nbsp;€/year', TEXT_DOMAIN); ?>"
                            ></span>
                        </div>
                        <div class="fs-2 lh-1 fw-bold text-nowrap"><span id="yearlyAmount">3600</span> €</div>
                    </div>
                </div>

                <a href="<?php echo get_app_url("/3rd-pillar-payment") ?>" class="btn btn-primary btn-lg btn-block mt-2">
                    <?php _e('Make a III pillar payment', TEXT_DOMAIN); ?>
                </a>
                <div class="mt-2 py-2 text-secondary text-center small">
                    <?php _e('Making a payment is <strong>free</strong> and takes only 2 minutes.', TEXT_DOMAIN); ?>
                </div>
            <?php } else { ?>
                <div class="card bg-gray-2 p-3 br-2">
                    <div class="form-group align-items-center row">
                        <label for="monthlyWage" class="col-sm-6 col-lg-5 col-xl-6 col-form-label pr-0">
                            <?php _e('Gross salary', TEXT_DOMAIN); ?><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('Your monthly gross salary (before tax). Including parental benefits, unemployment insurance benefits, sickness benefits, pension, etc.', TEXT_DOMAIN); ?>"
                            ></span>
                        </label>
                        <div class="col-sm-6 col-lg-7 col-xl-6">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="monthlyWage" min="0" step="1" placeholder="2000" inputmode="numeric" oninput="validity.valid||(value='');">
                                <div class="input-group-addon" style="min-width: 110px"><?php _e('&euro;/month', TEXT_DOMAIN); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group align-items-center row mb-0">
                        <label for="wageAddition" class="col-sm-6 col-lg-5 col-xl-6 col-form-label pr-0">
                            <?php _e('Other gross income', TEXT_DOMAIN); ?><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('Yearly gross income taxed at 22% including rental, Airbnb, interest, income from the sale of securities or other assets; dividends taxed at 7%; and II/III pillar pension payments at 10% or 22%.', TEXT_DOMAIN); ?>"
                            ></span>
                        </label>
                        <div class="col-sm-6 col-lg-7 col-xl-6">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="wageAddition" inputmode="numeric" min="0" step="1" placeholder="0" oninput="validity.valid||(value='');">
                                <div class="input-group-addon" style="min-width: 110px"><?php _e('&euro;/year', TEXT_DOMAIN); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-2 p-3 py-4 card br-2 text-navy">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mr-3">
                            <span class="d-inline-block fs-6 lh-sm fw-medium"><?php _e('Yearly gain from income tax', TEXT_DOMAIN); ?></span><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('The declaration of income for this year will start on February 15 next year. Remember that you cannot get more income tax back than you paid this year.', TEXT_DOMAIN); ?>"
                            ></span>
                        </div>
                        <div class="fs-5 lh-1 fw-bold text-green text-nowrap"><span id="savingsSum">792</span> €</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4">
                        <div class="mr-3">
                            <span class="d-inline-block fs-5 lh-sm fw-medium"><?php _e('Contribute monthly up to', TEXT_DOMAIN); ?></span><span
                                class="inline-help d-none d-md-inline-block"
                                tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                title="<?php _e('Contributions to the III pillar are subject to an income tax discount of up to 15% of taxable income in Estonia, but not more than 6000&nbsp;€/year', TEXT_DOMAIN); ?>"
                            ></span>
                        </div>
                        <div class="fs-2 lh-1 fw-bold text-nowrap"><span id="monthlyAmount">300</span> €</div>
                    </div>
                </div>

                <a href="<?php echo get_app_url("/3rd-pillar-flow") ?>" class="btn btn-primary btn-lg btn-block mt-2">
                    <?php _e('Make a III pillar payment', TEXT_DOMAIN); ?>
                </a>
                <div class="mt-2 py-2 text-secondary text-center small">
                    <?php _e('Setting up a recurring III pillar payment is <strong>free</strong> and takes only 2&nbsp;minutes.', TEXT_DOMAIN); ?>
                </div>
            <?php } ?>
        </form>
    </div>
</div>
