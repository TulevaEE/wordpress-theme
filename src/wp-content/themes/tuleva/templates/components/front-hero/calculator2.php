<?php print_funds_js(); ?>
<div class="card br-3 shadow-md second-pillar-calculator">
    <div class="card-body p-2">
        <div class="bg-blue-2 p-3 br-2">
            <div class="form-group align-items-center row">
                <label for="age" class="col-sm-6 col-form-label pr-0">
                    <?php _e('Your age', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="number" class="form-control text-right" id="age" inputmode="numeric"
                               min="0" max="99" step="1" placeholder="30"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <div class="input-group-append">
                            <span class="input-group-text" style="min-width: 77px">
                                <?php _e('years', TEXT_DOMAIN); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group align-items-center row">
                <label for="grossWage" class="col-sm-6 col-form-label pr-0">
                    <?php _e('Gross salary', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="number" id="grossWage" class="form-control text-right"
                               min="0" step="1" max="999999" placeholder="2000" inputmode="numeric"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <div class="input-group-append">
                            <span class="input-group-text" style="min-width: 77px">
                                <?php _e('&euro;/month', TEXT_DOMAIN); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-6 col-form-label pr-0">
                    <?php _e('Your II pillar contribution', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="btn-group btn-group-toggle d-flex" role="group" data-toggle="buttons">
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
                <label for="marketReturn" class="col-sm-6 col-form-label py-2 pr-0">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?><span
                        class="inline-help d-inline-block"
                        tabindex="0" data-toggle="tooltip" data-placement="bottom"
                        title="<?php _e('Actual amounts depend greatly on the rate of return, and neither we nor anyone else can guarantee you a specific return.', TEXT_DOMAIN); ?>"
                    ></span>
                </label>
                <div class="col-sm-6 return-rate">
                    <input type="range" id="marketReturn" class="custom-range" min="-10" max="10" step="1"
                           data-unit="%">
                    <span class="custom-tooltip">0%</span>
                    <span
                        class="historic-return-rate small text-secondary"><?php _e('7% historic return on stocks', TEXT_DOMAIN); ?></span>
                </div>
            </div>
        </div>

        <select class="custom-select mt-2 pl-3 fs-6 lh-sm fw-medium bg-gray-1 text-navy" name="pensionFunds"
                id="comparisonFund"
                onchange="calculateSaving()">
            <option value="average">
                <?php _e("Estonian average II pillar fund", TEXT_DOMAIN); ?>
                <!-- Other funds are filled with JS in calculator.js -->
            </option>
        </select>

        <div class="mt-3 px-3 card br-2 text-navy">
            <div class="d-flex align-items-center justify-content-between">
                <div class="mr-3">
                    <?php _e('Could accumulate by age 65', TEXT_DOMAIN); ?><span
                        class="inline-help d-inline-block"
                        tabindex="0" data-toggle="tooltip" data-placement="bottom"
                        title="<?php _e('We assume you started saving for your retirement at the age of 21 and a salary growth of 3% per year. The ‘Estonian average II pillar fund’ represents all II pillar funds collectively. We display its fee as a weighted average based on volume.', TEXT_DOMAIN); ?>"
                    ></span>
                </div>
                <div class="fs-5 lh-1 fw-bold text-nowrap">
                    <span id="future-value">- €</span>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="mr-3">
                    <span class="more-fees-high">
                        <?php _e('You pay <strong class="text-danger text-nowrap"><span class="tuleva-saving">- €</span> more</strong> in fees', TEXT_DOMAIN); ?>
                    </span>
                    <span class="more-fees d-none">
                        <?php _e('You pay <span class="text-nowrap"><span class="tuleva-saving">- €</span> more</span> in fees', TEXT_DOMAIN); ?>
                    </span>
                    <span class="same-fees d-none">
                        <?php _e('You pay in fees', TEXT_DOMAIN); ?>
                    </span>
                    <span class="less-fees d-none">
                        <?php _e('You pay <span class="text-nowrap"><span class="tuleva-saving">- €</span> less</span> in fees', TEXT_DOMAIN); ?>
                    </span>
                </div>
                <div class="text-nowrap"><span id="fund-fee">0,83%</span> <?php _e('per year', TEXT_DOMAIN); ?></div>
            </div>
        </div>
        <hr class="my-3"/>
        <div class="mt-3 px-3 card br-2 text-navy mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="mr-3">
                    <span class="fs-6 lh-sm fw-medium">
                        <?php _e('Tuleva World Stocks Pension Fund', TEXT_DOMAIN); ?>
                    </span>
                </div>
                <div class="fs-5 lh-1 fw-bold text-nowrap">
                    <span id="future-value-tuleva">- €</span>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="mr-3">
                    <?php _e('You pay in fees', TEXT_DOMAIN); ?>
                </div>
                <div class="text-nowrap"><?php _e('0.31% per year', TEXT_DOMAIN); ?></div>
            </div>
        </div>
    </div>
</div>
