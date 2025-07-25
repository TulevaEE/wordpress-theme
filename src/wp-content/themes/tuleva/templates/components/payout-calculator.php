<div class="card calculator rounded-4">
    <div class="card-body p-2 payout-calculator">
        <div class="card bg-gray-2 p-3 rounded-3">
            <div class="mb-3 row">
                <label for="portfolioSum" class="col-sm-6 col-form-label vertical-align pe-0">
                    <?php _e('Accumulated by age 65', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="text" class="portfolioSum form-control form-control-lg text-end"
                               id="portfolioSum" placeholder="20 000" inputmode="numeric" pattern="\d{1,7}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <span class="input-group-text" style="min-width: 70px">
                            <?php _e('euros', TEXT_DOMAIN); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mb-0 row">
                <label for="returnRate" class="col-sm-6 col-form-label py-2 pe-0">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6 return-rate">
                    <input type="range" class="returnRate form-range" id="returnRate" min="-10" max="10" step="1"
                           data-unit="%">
                    <span class="custom-tooltip">0%</span>
                    <span
                        class="historic-return-rate small text-secondary"><?php _e('7% historic return on stocks', TEXT_DOMAIN); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row m-0">
            <div class="col-12 col-sm-6 px-0 pe-sm-1">
                <div class="card bg-blue-2 mt-2 p-3 py-4 text-center text-nowrap rounded-3">
                    <span class="text-navy fw-medium">
                        <?php _e('You will receive monthly', TEXT_DOMAIN); ?><span
                            class="increasingTooltip inline-help ms-2 d-none"
                            role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                            title="<p class='fw-medium'><?php _e('If your pension assets increase due to positive returns, the monthly amount you receive will also increase.', TEXT_DOMAIN); ?></p><p><?php _e('The range shows how much you will receive at the beginning of the payout period and how it will change by the end of the period.', TEXT_DOMAIN); ?></p>"
                        ></span><span
                            class="decreasingTooltip inline-help ms-2 d-none"
                            role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                            title="<p class='fw-medium'><?php _e('If your pension assets decrease due to negative returns, the monthly amount you receive will also decrease.', TEXT_DOMAIN); ?></p><p><?php _e('The range shows how much you will receive at the beginning of the payout period and how it will change by the end of the period.', TEXT_DOMAIN); ?></p>"
                        ></span>
                    </span>
                    <div class="fs-3 mt-1 text-blue fw-bold lh-sm">
                        <span class="recurringPayoutMonthly1">88</span><span
                            class="recurringArrow d-none ff-system fw-normal lh-1">&thinsp;→&thinsp;</span><span
                            class="recurringPayoutMonthly2 d-none">88</span>
                        <span class="recurringEuro"> €</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 px-0 ps-sm-1">
                <div class="card bg-blue-2 mt-2 p-3 py-4 text-center rounded-3">
                    <span class="text-navy fw-medium"><?php _e('You will receive in total', TEXT_DOMAIN); ?></span>
                    <div class="recurringPayoutSum fs-3 mt-1 text-blue fw-bold lh-sm">20 000 €</div>
                </div>
            </div>
        </div>
        <p class="m-0 mt-2 p-3 small text-secondary text-center"><?php _e('The calculator assumes you’ll start withdrawals <strong>at age 65</strong> and receive them <strong>over 19 years</strong> (the average remaining lifespan for a 65-year-old today). While the stock market’s historical return is 7%, future returns are not guaranteed.', TEXT_DOMAIN); ?></p>
    </div>
</div>
