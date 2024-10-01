<div class="card shadow-md br-3">
    <div class="card-body p-2 second-pillar-payment-rate-calculator">
        <h2 class="h5 m-0 mb-2 py-2 text-navy text-center">
            <?php _e('What does increasing your II pillar payments mean to you?', TEXT_DOMAIN); ?>
        </h2>
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
                <label for="returnRate" class="col-sm-6 col-form-label py-2 pr-0">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6 return-rate">
                    <input type="range" class="custom-range" id="returnRate" min="-10" max="10" step="1" data-unit="%">
                    <span class="custom-tooltip text-bold">0%</span>
                    <span class="historic-return-rate small text-secondary"><?php _e('the historic return on stocks is 7%', TEXT_DOMAIN); ?></span>
                </div>
            </div>
        </div>
        <!-- results -->
        <div class="mt-4 px-3 text-gray-700">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="mr-3">
                    <strong class="d-block">
                        <?php _e('Your net salary', TEXT_DOMAIN); ?>
                        <!--<span class="text-nowrap text-secondary fw-medium small" id="netWageDiff">−100 €</span>-->
                        <span class="text-nowrap text-green fw-medium small income-tax-savings"><span
                                id="monthlyTaxWin">17 €</span> <?php _e('income tax savings', TEXT_DOMAIN); ?></span>
                    </strong>
                </div>
                <span id="netWage" class="fs-4 lh-sm fw-bold text-nowrap">1457 €</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="mr-3">
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
                <span id="monthlyContributionYouDifference" class="fs-4 lh-sm fw-bold text-nowrap">80 €</span>
                <span id="monthlyContribution" class="fs-4 lh-sm fw-bold text-nowrap d-none">200 €</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="mr-3">
                    <strong class="d-block">
                        <?php _e('You accumulate more by age 65', TEXT_DOMAIN); ?>
                    </strong>
                </div>
                <span id="savingsSum" class="fs-4 lh-sm fw-bold text-nowrap text-green">+33 600 €</span>
                <span id="savingsSumZero" class="fs-4 lh-sm fw-bold text-nowrap text-orange d-none"><?php _e('No change', TEXT_DOMAIN); ?></span>
            </div>

            <!-- disclaimer -->
            <div class="mt-3 py-2 px-3 text-center text-secondary small">
                <?php _e('The calculator takes into account the July 2024 coalition agreement: <br class="d-none d-md-inline">income tax is raised to 22% and tax-free basic exemption is not raised.', TEXT_DOMAIN); ?>
                <?php _e('The average return on stocks shown in the calculator has historically been 7%, but this does not guarantee similar returns in the future.', TEXT_DOMAIN); ?>
            </div>
        </div>

        <div class="d-lg-none d-block mt-3">
            <a href="<?php echo get_app_url("/2nd-pillar-payment-rate") ?>"
               class="btn btn-primary btn-lg btn-block mt-3 text-wrap"
               target="_blank">
                <?php _e('Start saving more<span class="d-none d-md-inline"> (2 min)</span>', TEXT_DOMAIN); ?>
            </a>
        </div>
    </div>
</div>
