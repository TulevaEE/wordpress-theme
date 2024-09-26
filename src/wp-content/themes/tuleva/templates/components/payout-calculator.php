<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<div class="card shadow-md" style="border-radius: 1rem">
    <div class="card-body p-2 payout-calculator">
        <div class="card bg-light p-3" style="border-radius: 1rem">
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
                <label for="returnRate" class="col-sm-6 col-form-label pr-0 vertical-align">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6 d-flex text-center return-rate">
                    <input type="range" class="custom-range" id="returnRate" min="-10" max="10" step="1" data-unit="%">
                    <span class="custom-tooltip d-inline-block text-bold">0%</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right text-nowrap small text-muted">
                    <?php _e('the historic return on stocks is 7%', TEXT_DOMAIN); ?>
                </div>
            </div>
        </div>
        <div class="results row m-0">
            <div class="col-6 pl-0 pr-1">
                <div class="card bg-blue-washed p-3 mt-2 text-center text-nowrap" style="border-radius: 1rem">
                    <b><?php _e('You will receive monthly', TEXT_DOMAIN); ?></b>
                    <div>
                        <span id="recurringPayoutMonthly1" class="h4 text-primary mb-0">88</span><span
                            id="recurringArrow" class="h4 text-primary mb-0 d-none"
                            style="font-family: system-ui; font-size: 1.2rem">→</span><span id="recurringPayoutMonthly2"
                                                                                            class="h4 text-primary mb-0 d-none">88</span>
                        <span id="recurringEuro" class="h4 text-primary mb-0"> €</span>
                    </div>
                </div>
            </div>
            <div class="col-6 pl-1 pr-0">
                <div class="card bg-blue-washed p-3 mt-2 text-center" style="border-radius: 1rem">
                    <b><?php _e('You will receive in total', TEXT_DOMAIN); ?></b>
                    <div id="recurringPayoutSum" class="h4 text-primary mb-0">20 000 €</div>
                </div>
            </div>
        </div>
        <div class="small text-muted m-3 text-center">Statistiliselt on 65-aastasel inimesel elada jäänud <b>19 aastat</b> ja igakuised fondipensioni väljamaksed üle 19 a on <b>tulumaksuvabad.</b></div>
    </div>
</div>
