<div class="card calculator br-3">
    <div class="card-body p-2 payout-calculator">
        <div class="card bg-gray-2 p-3 br-2">
            <div class="form-group row">
                <label for="portfolioSum" class="col-sm-6 col-form-label vertical-align pr-0">
                    <?php _e('Accumulated by age 65', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg text-right" id="portfolioSum"
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
                    <input type="range" class="custom-range" id="returnRate" min="-10" max="10" step="1" data-unit="%">
                    <span class="custom-tooltip">0%</span>
                    <span class="historic-return-rate small text-secondary"><?php _e('the historic return on stocks is 7%', TEXT_DOMAIN); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row m-0">
            <div class="col-12 col-sm-6 px-0 pr-sm-1">
                <div class="card bg-blue-2 mt-2 p-3 py-4 text-center text-nowrap br-2">
                    <span class="text-navy fw-medium"><?php _e('You will receive monthly', TEXT_DOMAIN); ?></span>
                    <div class="fs-3 mt-1 text-blue fw-bold lh-sm">
                        <span id="recurringPayoutMonthly1">88</span><span id="recurringArrow" class="d-none ff-system fw-normal lh-1">&thinsp;→&thinsp;</span><span id="recurringPayoutMonthly2" class="d-none">88</span>
                        <span id="recurringEuro"> €</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 px-0 pl-sm-1">
                <div class="card bg-blue-2 mt-2 p-3 py-4 text-center br-2">
                    <span class="text-navy fw-medium"><?php _e('You will receive in total', TEXT_DOMAIN); ?></span>
                    <div class="fs-3 mt-1 text-blue fw-bold lh-sm" id="recurringPayoutSum">20 000 €</div>
                </div>
            </div>
        </div>
        <p class="m-0 mt-2 p-3 small text-secondary text-center">Kalkulaator eeldab, et hakkad saama väljamakseid <strong>65-aastaselt</strong> ja <strong>19 aasta jooksul</strong>, sel juhul on sinu igakuised fondipensioni väljamaksed <strong class="text-green-muted fw-medium">tulumaksuvabad</strong>. Aktsiaturu keskmine tootlus on ajalooliselt olnud 7%, kuid see ei taga sarnast tootlust tulevikus.</p>
    </div>
</div>
