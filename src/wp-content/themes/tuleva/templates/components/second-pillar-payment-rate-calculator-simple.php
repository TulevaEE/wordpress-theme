<div class="card calculator br-3">
    <div class="card-body p-2 second-pillar-payment-rate-calculator">
        <h2 class="h5 m-0 mb-2 py-3 text-navy text-center">
            <?php _e('How much tax benefit will I get?', TEXT_DOMAIN); ?>
        </h2>
        <div class="card bg-gray-2 p-3 br-2">
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
            <div class="form-group row mb-0">
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
        </div>
        <!-- results -->
        <div class="mt-2 p-3 py-4 card br-2 text-navy">
            <div class="d-flex align-items-center justify-content-between">
                <div class="mr-3">
                    <span class="d-inline-block fs-6 lh-sm"><?php _e('Net salary from 2025', TEXT_DOMAIN); ?></span><span
                        class="inline-help d-inline-block"
                        tabindex="0" data-toggle="tooltip" data-placement="bottom"
                        title="<?php _e('Starting from 2025, the income tax will increase to 22%. The tax-free allowance is up to 654 euros per month but reduces as income grows.', TEXT_DOMAIN); ?>"
                    ></span>
                </div>
                <div id="netWage" class="lh-1 fw-medium text-nowrap">1457 €</div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="mr-3">
                    <span class="d-inline-block fs-6 lh-sm"><?php _e('Your monthly contribution', TEXT_DOMAIN); ?></span>
                </div>
                <div id="monthlyContributionYou" class="lh-1 fw-medium text-nowrap">120 €</div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="mr-3">
                    <span class="d-inline-block fs-5 lh-sm fw-medium">
                        <?php _e('Yearly gain from income tax', TEXT_DOMAIN); ?>
                    </span>
                </div>
                <span id="yearlyTaxWin" class="fs-2 lh-1 fw-bold text-nowrap text-green">316 €</span>
                <span id="yearlyTaxWinZero" class="fs-2 lh-1 fw-bold text-nowrap d-none">0 €</span>
            </div>
        </div>
        <a href="<?php echo get_app_url("/2nd-pillar-payment-rate") ?>"
           class="btn btn-primary btn-lg btn-block mt-2 text-wrap">
            <?php _e('Increase contribution', TEXT_DOMAIN); ?>
        </a>
        <div class="mt-2 py-2 text-secondary text-center small">
            <?php _e('Submitting the application is <strong>free</strong> and takes only 2 minutes.', TEXT_DOMAIN); ?>
        </div>
    </div>
</div>
