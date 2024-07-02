<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<div class="card shadow-md">
    <div class="card-body p-4 payout-calculator">
            <h5 class="mb-3 text-navy">
                <?php _e('How to use the money accumulated in your pillars?', TEXT_DOMAIN); ?>
            </h5>
            <div class="card bg-blue-washed p-3">
                <div class="form-group row">
                    <label for="portfolioSum" class="col-sm-6 col-form-label vertical-align pr-0">
                        <?php _e('Accumulated by the age of 65', TEXT_DOMAIN); ?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-lg text-right" id="portfolioSum"
                               placeholder="50 000" inputmode="numeric" pattern="\d{1,7}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pensionYears" class="col-sm-6 col-form-label vertical-align pr-0">
                        <?php _e('Length of pension in years', TEXT_DOMAIN); ?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-lg text-right" id="pensionYears"
                               placeholder="20" inputmode="numeric" pattern="\d{1,2}"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-sm-6 col-form-label pr-0 vertical-align d-flex align-items-end">
                        <?php _e('Expected annual return', TEXT_DOMAIN); ?>

                    </label>
                    <div class="col-sm-6 text-center position-relative d-flex mt-2 mb-2 mt-sm-0 mb-sm-0">
                        <input type="range" class="custom-range" id="returnRate" min="-10" max="10" step="1">
                        <span id="customTooltip" class="d-inline-block text-bold mb-1">0%</span>
                    </div>
                </div>
            </div>
            <div class="results mt-4">
                <h6 class="text-navy mb-4">
                    <?php _e('You will receive', TEXT_DOMAIN); ?>
                </h6>
                <div>
                    <canvas id="payoutChart" class="w-100"></canvas>
                </div>
            </div>

            <div class="d-lg-none d-block">
                <a href="<?php echo get_app_url("/payout") ?>"
                   class="btn btn-primary btn-lg btn-block mt-3 text-wrap"
                   target="_blank">
                    <?php _e('See more', TEXT_DOMAIN); ?>
                </a>
                <!--
                <div class="mt-2 text-center">
                    <small class="text-secondary">
                        <?php _e('Submitting an application is <strong>free of charge</strong>.', TEXT_DOMAIN); ?>
                    </small>
                </div>
                -->
            </div>
    </div>
</div>
