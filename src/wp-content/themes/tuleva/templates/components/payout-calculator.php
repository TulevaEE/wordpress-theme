<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<div class="card shadow-md" style="border-radius: 1rem">
    <div class="card-body p-2 payout-calculator">
        <div class="card bg-blue-washed p-3" style="border-radius: 1rem">
            <div class="form-group row">
                <label for="portfolioSum" class="col-sm-6 col-form-label vertical-align pr-0">
                    <?php _e('Accumulated by pension age', TEXT_DOMAIN); ?>
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
            <div class="form-group row">
                <label for="pensionYears" class="col-sm-6 col-form-label vertical-align pr-0">
                    <?php _e('Length of pension', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg text-right" id="pensionYears"
                               placeholder="20" inputmode="numeric" pattern="\d{1,2}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <div class="input-group-addon" style="min-width: 70px"><?php _e('years', TEXT_DOMAIN); ?></div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label for="returnRate" class="col-sm-6 col-form-label pr-0 vertical-align d-flex align-items-end">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6 d-flex text-center position-relative return-rate">
                    <input type="range" class="custom-range" id="returnRate" min="-10" max="10" step="1">
                    <span id="customTooltip" class="d-inline-block text-bold mb-1">0%</span>
                </div>
                <div class="col-sm-6 offset-sm-6 text-center small text-muted p-0 mt-1">
                    <?php _e('historic stock market return 7%', TEXT_DOMAIN); ?>
                </div>
            </div>
        </div>
        <div class="results row m-0">
            <div class="col-6 pl-0 pr-1">
                <div class="card bg-green-washed p-3 mt-2 text-center text-nowrap" style="border-radius: 1rem">
                    <b><?php _e('You will receive monthly', TEXT_DOMAIN); ?></b>
                    <div>
                        <span id="recurringPayoutMonthly1" class="h4 text-green mb-0">83</span><span
                            id="recurringArrow" class="h4 text-green mb-0 d-none"
                            style="font-family: system-ui; font-size: 1.2rem">→</span><span id="recurringPayoutMonthly2"
                                                                                            class="h4 text-green mb-0 d-none">83</span>
                        <span id="recurringEuro" class="h4 text-green mb-0"> €</span>
                    </div>
                </div>
            </div>
            <div class="col-6 pl-1 pr-0">
                <div class="card bg-green-washed p-3 mt-2 text-center" style="border-radius: 1rem">
                    <b><?php _e('You will receive in total', TEXT_DOMAIN); ?></b>
                    <div id="recurringPayoutSum" class="h4 text-green mb-0">20 000 €</div>
                </div>
            </div>
        </div>
    </div>
</div>
