<div class="card calculator rounded-4">
    <div class="card-body p-2 second-pillar-payment-rate-calculator">
        <div class="card bg-gray-2 p-3 rounded-3">
            <div class="mb-3 row">
                <label for="monthlyWage" class="col-sm-6 col-form-label vertical-align pe-0">
                    <?php _e('Monthly gross salary', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control form-control-lg text-end" id="monthlyWage"
                           placeholder="2000" inputmode="numeric" pattern="\d{1,6}"
                           oninput="validity.valid||(value=value.slice(0, -1))">
                </div>
            </div>
            <div class="mb-0 row">
                <label class="col-sm-6 col-form-label pe-0">
                    <?php _e('Your II pillar contribution', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="btn-group d-flex" role="group"
                         aria-label="<?php _e('Your II pillar contribution', TEXT_DOMAIN); ?>">
                        <input type="radio" class="btn-check" name="pillarContribution" id="payment2" value="2"
                               autocomplete="off">
                        <label for="payment2" class="btn btn-outline-primary w-100">2%</label>

                        <input type="radio" class="btn-check" name="pillarContribution" id="payment4" value="4"
                               autocomplete="off">
                        <label for="payment4" class="btn btn-outline-primary w-100">4%</label>

                        <input type="radio" class="btn-check" name="pillarContribution" id="payment6" value="6"
                               autocomplete="off" checked>
                        <label for="payment6" class="btn btn-outline-primary w-100">6%</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- results -->
        <div class="mt-2 p-3 py-4 card rounded-3 text-navy">
            <div class="d-flex align-items-center justify-content-between">
                <div class="me-3">
                    <span
                        class="d-inline-block fs-6 lh-sm"><?php _e('Net salary from 2027', TEXT_DOMAIN); ?></span><span
                        class="inline-help d-inline-block"
                        role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="<?php _e('Assuming income tax rate of 22% and tax-free allowance of 700 euros per month.', TEXT_DOMAIN); ?>"
                    ></span>
                </div>
                <div id="netWage" class="lh-1 fw-medium text-nowrap">1595 €</div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="me-3">
                    <span
                        class="d-inline-block fs-6 lh-sm"><?php _e('Your monthly contribution', TEXT_DOMAIN); ?></span>
                </div>
                <div id="monthlyContributionYou" class="lh-1 fw-medium text-nowrap">120 €</div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="me-3">
                    <span class="d-inline-block fs-5 lh-sm fw-medium">
                        <?php _e('Yearly gain from income tax', TEXT_DOMAIN); ?>
                    </span>
                </div>
                <span id="yearlyTaxWin" class="fs-2 lh-1 fw-bold text-nowrap text-success">316 €</span>
            </div>
        </div>
        <!-- CTA -->
        <div class="d-grid">
            <a href="<?php echo get_app_url("/2nd-pillar-payment-rate") ?>"
               class="btn btn-primary btn-lg mt-2 text-wrap">
                <?php _e('Increase contribution', TEXT_DOMAIN); ?>
            </a>
        </div>
        <div class="mt-2 py-2 text-secondary text-center small">
            <?php _e('Submitting the application is <strong>free</strong> and takes only 2 minutes.', TEXT_DOMAIN); ?>
        </div>
    </div>
</div>
