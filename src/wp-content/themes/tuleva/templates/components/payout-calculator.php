<div class="card calculator br-3">
    <div class="card-body p-2 payout-calculator">
        <div class="card bg-gray-2 p-3 br-2">
            <div class="form-group row">
                <label for="portfolioSum" class="col-sm-6 col-form-label vertical-align pr-0">
                    <?php _e('Accumulated by age 65', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="text" class="portfolioSum form-control form-control-lg text-right"
                               placeholder="20 000" inputmode="numeric" pattern="\d{1,7}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <div class="input-group-addon" style="min-width: 70px"><?php _e('euros', TEXT_DOMAIN); ?></div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label for="returnRate" class="col-sm-6 col-form-label py-2 pr-0">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6 return-rate">
                    <input type="range" class="returnRate custom-range" min="-10" max="10" step="1" data-unit="%">
                    <span class="custom-tooltip">0%</span>
                    <span class="historic-return-rate small text-secondary"><?php _e('historic return on stocks 7%', TEXT_DOMAIN); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row m-0">
            <div class="col-12 col-sm-6 px-0 pr-sm-1">
                <div class="card bg-blue-2 mt-2 p-3 py-4 text-center text-nowrap br-2">
                    <span class="text-navy fw-medium">
                        <?php _e('You will receive monthly', TEXT_DOMAIN); ?><span
                            class="increasingTooltip inline-help ml-2 d-none"
                            tabindex="0" data-toggle="tooltip" data-placement="top" data-html="true"
                            title="<p class='fw-medium'><?php _e('If your pension assets increase due to positive returns, the monthly amount you receive will also increase.', TEXT_DOMAIN); ?></p><p><?php _e('The range shows how much you will receive at the beginning of the payout period and how it will change by the end of the period.', TEXT_DOMAIN); ?></p>"
                        ></span><span
                            class="decreasingTooltip inline-help ml-2 d-none"
                            tabindex="0" data-toggle="tooltip" data-placement="top" data-html="true"
                            title="<p class='fw-medium'><?php _e('If your pension assets decrease due to negative returns, the monthly amount you receive will also decrease.', TEXT_DOMAIN); ?></p><p><?php _e('The range shows how much you will receive at the beginning of the payout period and how it will change by the end of the period.', TEXT_DOMAIN); ?></p>"
                        ></span>
                    </span>
                    <div class="fs-3 mt-1 text-blue fw-bold lh-sm">
                        <span class="recurringPayoutMonthly1">88</span><span class="recurringArrow d-none ff-system fw-normal lh-1">&thinsp;→&thinsp;</span><span class="recurringPayoutMonthly2 d-none">88</span>
                        <span class="recurringEuro"> €</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 px-0 pl-sm-1">
                <div class="card bg-blue-2 mt-2 p-3 py-4 text-center br-2">
                    <span class="text-navy fw-medium"><?php _e('You will receive in total', TEXT_DOMAIN); ?></span>
                    <div class="recurringPayoutSum fs-3 mt-1 text-blue fw-bold lh-sm">20 000 €</div>
                </div>
            </div>
        </div>
        <p class="m-0 mt-2 p-3 small text-secondary text-center"><?php _e('The calculator assumes you’ll start withdrawals <strong>at age 65</strong> and receive them <strong>over 19 years</strong> (the average remaining lifespan for a 65-year-old today). While the stock market’s historical return is 7%, future returns are not guaranteed.', TEXT_DOMAIN); ?></p>
    </div>
</div>
