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
                               placeholder="50 000" inputmode="numeric" pattern="\d{1,7}"
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
                    <?php _e('average stock market return 7%', TEXT_DOMAIN); ?>
                </div>
            </div>
        </div>
        <div class="results px-3 mt-4">
            <table class="table text-center mb-0" style="table-layout: fixed">
                <tr>
                    <td class="border-0 h6 text-navy px-1 pt-0 pb-3">
                        <?php _e('Monthly payout<br />(funded pension)', TEXT_DOMAIN); ?>
                    </td>
                    <td class="border-0 h6 text-navy px-1 pt-0 pb-3">
                        <?php _e('Single<br />payout', TEXT_DOMAIN); ?>
                    </td>
                </tr>
            </table>

            <div style="max-height: 160px">
                <canvas id="payoutChart" class="w-100"></canvas>
            </div>

            <table class="table text-center mb-0" style="table-layout: fixed">
                <tr>
                    <td class="border-0 pt-0">
                        <div id="recurringPayoutSum" class="h5 text-green mb-0">50 000 €</div>
                        <span
                            class="small text-muted text-normal"><?php _e('You will receive in total', TEXT_DOMAIN); ?></span>
                    </td>
                    <td class="border-0 pt-0">
                        <div id="singlePayoutSum" class="h5 text-orange mb-0">45 000 €</div>
                        <span
                            class="small text-muted text-normal"><?php _e('You will receive at once', TEXT_DOMAIN); ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span id="recurringPayoutMonthly1" class="h5 text-green mb-0">208</span><span
                            id="recurringArrow" class="h5 text-green mb-0 d-none"
                            style="font-family: system-ui; font-size: 1.2rem">→</span><span id="recurringPayoutMonthly2"
                                                                                            class="h5 text-green mb-0 d-none">208</span>
                        <span id="recurringEuro" class="h5 text-green mb-0"> €</span>
                        <div id="receiveMonthly"
                             class="small text-muted text-normal"><?php _e('You will receive monthly', TEXT_DOMAIN); ?></div>
                        <div id="receiveMonthlyInc"
                             class="small text-muted text-normal d-none"><?php _e('Receive monthly, increasing', TEXT_DOMAIN); ?></div>
                        <div id="receiveMonthlyDec"
                             class="small text-muted text-normal d-none"><?php _e('Receive monthly, decreasing', TEXT_DOMAIN); ?></div>
                    </td>
                    <td>
                        <div id="singlePayoutMonthly" class="h5 text-orange mb-0">188 €</div>
                        <span
                            class="small text-muted text-normal"><?php _e('You can spend monthly', TEXT_DOMAIN); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="pb-2">
                        <span id="recurringPayoutTaxRate" class="text-bold text-green">0%</span>
                        <span class="small text-muted text-normal"><?php _e('Income tax', TEXT_DOMAIN); ?></span>
                    </td>
                    <td class="pb-2">
                        <span id="singlePayoutTaxRate" class="text-bold text-orange">10%</span>
                        <span class="small text-muted text-normal"><?php _e('Income tax', TEXT_DOMAIN); ?></span>
                    </td>
                </tr>
            </table>

        </div>
        <!--
            <div class="d-lg-none d-block">
                <a href="<?php echo get_app_url("/payout") ?>"
                   class="btn btn-primary btn-lg btn-block mt-3 text-wrap"
                   target="_blank">
                    <?php _e('See more', TEXT_DOMAIN); ?>
                </a>
                <div class="mt-2 text-center">
                    <small class="text-secondary">
                        <?php _e('Submitting an application is <strong>free of charge</strong>.', TEXT_DOMAIN); ?>
                    </small>
                </div>
            </div>
            -->
    </div>
</div>
