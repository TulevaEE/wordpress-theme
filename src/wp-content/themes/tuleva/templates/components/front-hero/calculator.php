<?php print_funds_js(); ?>
<div class="card calculator rounded-4 second-pillar-calculator">
    <div class="card-body p-2">
        <div class="bg-blue-2 p-3 rounded-3">
            <div class="mb-3 align-items-center row">
                <label for="age" class="col-sm-6 col-form-label pe-0">
                    <?php _e('Your age', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="number" class="form-control text-end" id="age" inputmode="numeric"
                               min="0" max="99" step="1" placeholder="30"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <span class="input-group-text" style="min-width: 76px">
                            <?php _e('years', TEXT_DOMAIN); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3 align-items-center row">
                <label for="grossWage" class="col-sm-6 col-form-label pe-0">
                    <?php _e('Gross salary', TEXT_DOMAIN); ?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="number" id="grossWage" class="form-control text-end"
                               min="0" step="1" max="999999" placeholder="2000" inputmode="numeric"
                               oninput="validity.valid||(value=value.slice(0, -1))">
                        <span class="input-group-text" style="min-width: 76px">
                            <?php _e('&euro;/month', TEXT_DOMAIN); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
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

            <div class="mb-0 row">
                <label for="marketReturn" class="col-sm-6 col-form-label py-2 pe-0">
                    <?php _e('Expected annual return', TEXT_DOMAIN); ?><span
                        class="inline-help d-inline-block"
                        role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="<?php _e('Actual amounts depend greatly on the rate of return, and neither we nor anyone else can guarantee you a specific return.', TEXT_DOMAIN); ?>"
                    ></span>
                </label>
                <div class="col-sm-6 return-rate">
                    <input type="range" id="marketReturn" class="form-range" min="-10" max="10" step="1"
                           data-unit="%">
                    <span class="custom-tooltip">0%</span>
                    <span
                        class="historic-return-rate small text-secondary"><?php _e('7% historic return on stocks', TEXT_DOMAIN); ?></span>
                </div>
            </div>
        </div>

        <label for="comparisonFund" class="form-label visually-hidden"><?php _e('Compare with a fund', TEXT_DOMAIN); ?></label>
        <select class="form-select mt-2 ps-3 fw-medium bg-gray-1 text-navy" name="pensionFunds"
                id="comparisonFund"
                onchange="calculateSaving()">
            <option value="average">
                <?php _e("Estonian average II pillar fund", TEXT_DOMAIN); ?>
                <!-- Other funds are filled with JS in calculator.js -->
            </option>
        </select>

        <div class="mt-3 px-3 text-navy">
            <div class="d-flex align-items-center justify-content-between">
                <div class="me-3">
                    <?php _e('Could accumulate by age 65', TEXT_DOMAIN); ?><span
                        class="inline-help d-inline-block"
                        role="button" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="<?php _e('We assume you started saving for your retirement at the age of 21 and a salary growth of 3% per year. The ‘Estonian average II pillar fund’ represents all II pillar funds collectively. We display its fee as a weighted average based on volume.', TEXT_DOMAIN); ?>"
                    ></span>
                </div>
                <div class="fs-5 lh-1 fw-bold text-nowrap">
                    <span id="future-value">- €</span>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="me-3">
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
        <div class="mt-3 px-3 text-navy mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="me-3">
                    <span class="fs-6 lh-sm fw-medium">
                        <?php _e('Tuleva World Stocks Pension Fund', TEXT_DOMAIN); ?>
                    </span>
                </div>
                <div class="fs-5 lh-1 fw-bold text-nowrap">
                    <span id="future-value-tuleva">- €</span>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="me-3">
                    <?php _e('You pay in fees', TEXT_DOMAIN); ?>
                </div>
                <div class="text-nowrap"><?php _e('0.31% per year', TEXT_DOMAIN); ?></div>
            </div>
        </div>
    </div>
</div>
