<div class="card shadow-md">
    <div class="card-body p-4 second-pillar-payment-rate-calculator">
            <h5 class="mb-3 text-navy">
                <?php _e('What does increasing your II pillar payments mean to you?', TEXT_DOMAIN); ?>
            </h5>
            <div class="card bg-blue-washed p-3">
                <div class="form-group row">
                    <label for="yourAge" class="col-sm-6 col-form-label vertical-align pr-0">
                        <?php _e('Age', TEXT_DOMAIN); ?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-lg text-right" id="yourAge"
                               placeholder="30" inputmode="numeric" pattern="\d{1,2}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="monthlyWage" class="col-sm-6 col-form-label vertical-align pr-0">
                        <?php _e('Monthly gross salary', TEXT_DOMAIN); ?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-lg text-right" id="monthlyWage"
                               placeholder="2000" inputmode="numeric" pattern="\d{1,6}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label pr-0">
                        <?php _e('Your II pillar contribution', TEXT_DOMAIN); ?>
                    </label>
                    <div class="col-sm-6">
                        <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                            <label class="btn btn-outline-primary w-100">
                                <input type="radio" name="pillarContribution" id="payment2" value="2"> 2%
                            </label>
                            <label class="btn btn-outline-primary w-100">
                                <input type="radio" name="pillarContribution" id="payment4" value="4"> 4%
                            </label>
                            <label class="btn btn-outline-primary w-100 active">
                                <input type="radio" name="pillarContribution" id="payment6" value="6" checked> 6%
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label for="returnRate" class="col-sm-6 col-form-label pr-0 pb-3 pb-sm-2 vertical-align d-flex align-items-end">
                        <?php _e('Expected annual return', TEXT_DOMAIN); ?>

                    </label>
                    <div class="col-sm-6 text-center position-relative d-flex">
                        <input type="range" class="second-pillar-calculator-range custom-range" id="returnRate" min="-10" max="10" step="1">
                        <span id="customTooltip" class="d-inline-block text-bold mb-1">0%</span>
                    </div>
                    <div class="col-sm-6 offset-sm-6 pr-3 pt-4 pt-sm-0 text-right text-nowrap small text-muted p-0 mt-1"><?php _e('the average return on stocks is 7%', TEXT_DOMAIN); ?></div>
                </div>
            </div>
            <div class="results mt-3 text-gray-700">
                <div class="d-flex align-items-center justify-content-between mb-2 px-sm-3">
                    <div class="mr-1">
                        <strong class="d-block">
                            <?php _e('Your net salary', TEXT_DOMAIN); ?>
                            <!--<span class="text-nowrap text-secondary" id="netWageDiff">−100 €</span>-->
                            <span class="text-nowrap text-success font-weight-normal income-tax-savings"><span id="monthlyTaxWin">17 €</span> <?php _e('income tax savings', TEXT_DOMAIN); ?></span>
                        </strong>

                    </div>
                    <h4 id="netWage" class="text-nowrap vertical-align m-0 text-gray-700">1457 €</h4>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2 px-sm-3">
                    <div class="mr-1">
                        <strong class="d-block">
                            <?php _e('You accumulate more monthly', TEXT_DOMAIN); ?>
                        </strong>
                        <small class="text-muted d-none">
                            <?php _e('Yearly gain from income tax', TEXT_DOMAIN); ?>
                            <span class="text-nowrap" id="yearlyTaxWin">+211 €</span>
                            <span class="text-nowrap d-none"
                                  id="yearlyTaxWinZero"><?php _e('no change', TEXT_DOMAIN); ?></span>
                        </small>

                    </div>
                    <h4 id="monthlyContributionYouDifference" class="text-nowrap vertical-align m-0 text-gray-700">80 €</h4>
                    <h4 id="monthlyContribution" class="d-none text-nowrap vertical-align m-0 text-gray-700">200 €</h4>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2 px-sm-3">
                    <div class="mr-1">
                        <strong class="d-block">
                            <?php _e('You accumulate more by age 65', TEXT_DOMAIN); ?>
                        </strong>
                    </div>
                    <h4 id="savingsSum" class="text-nowrap vertical-align text-success m-0">+33 600 €</h4>
                    <h4 id="savingsSumZero"
                    class="text-nowrap vertical-align text-orange m-0 d-none"><?php _e('No change', TEXT_DOMAIN); ?></h4>
                </div>
                <div class="pt-3 text-center text-muted">
                    <small><?php _e('The calculator takes into account the July 2024 coalition agreement: <br class="d-none d-md-inline">income tax is raised to 22% and tax-free basic exemption is not raised.', TEXT_DOMAIN); ?> <?php _e('The average return on stocks shown in the calculator has historically been 7%, but this does not guarantee similar returns in the future.', TEXT_DOMAIN); ?></small>
                </div>
            </div>

            <div class="d-lg-none d-block">
                <a href="<?php echo get_app_url("/2nd-pillar-payment-rate") ?>"
                   class="btn btn-primary btn-lg btn-block mt-3 text-wrap"
                   target="_blank">
                    <?php _e('Start saving more<span class="d-none d-md-inline"> (2 min)</span>', TEXT_DOMAIN); ?>
                </a>
                <div class="mt-2 text-center">
                    <small class="text-secondary">
                        <?php _e('Submitting an application is <strong>free of charge</strong>.', TEXT_DOMAIN); ?>
                    </small>
                </div>
            </div>
    </div>
</div>
