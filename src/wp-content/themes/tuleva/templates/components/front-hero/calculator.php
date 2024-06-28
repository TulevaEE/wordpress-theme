<?php print_funds_js(); ?>
<div class="card calculator shadow-md">
    <div class="card-body p-3 text-center">
        <form class="d-flex flex-row justify-content-center pb-3">
            <div class="d-inline-block align-items-center text-medium mr-2">
                <span class="calculator__heading"><?php _e("If you're", TEXT_DOMAIN); ?></span>
                <input class="form-control d-inline-block mx-1" id="age" type="number" value="29" min="18" max="65" onkeyup="calculateSaving()" onchange="calculateSaving()">
                <span class="calculator__heading"><?php _e("years old and earn", TEXT_DOMAIN); ?></span>
                <input class="form-control d-inline-block mx-1" id="netWage" type="number" value="1800" onkeyup="calculateSaving()" onchange="calculateSaving()">
                <span class="calculator__heading">
                    <?php _e("euros per month (net),", TEXT_DOMAIN); ?>
                    <?php _e("at", TEXT_DOMAIN); ?>
                    <select class="form-control mx-1 d-inline-block" id="marketReturn" onchange="calculateSaving()">
                        <option value="1.08">8%</option>
                        <option value="1.05">5%</option>
                        <option value="1" selected>0%</option>
                        <option value="0.99">-1%</option>
                    </select>
                    <?php _e("return", TEXT_DOMAIN); ?>
                    <?php _e("your second pillar", TEXT_DOMAIN); ?>
                    <a id="calculator" href="#modal-calculator"><?php _e("could", TEXT_DOMAIN); ?></a>
                    <?php _e("grow", TEXT_DOMAIN); ?>
                </span>
            </div>
        </form>
        <div>
            <div class="p-3 calculator__comparison-row">
                <select class="form-control calculator__comparison-fund" name="pensionFunds" id="comparisonFund" onchange="calculateSaving()">
                    <option value="average">
                        <?php _e("Estonian pension fund average", TEXT_DOMAIN); ?>
                        <!-- Other funds are filled with JS in calculator.js -->
                    </option>
                </select>
                <div class="calculator__comparison-result">
                    <h5 class="mb-0" id="future-value">- €</h5>
                    <small class="text-secondary text-nowrap"><?php _e("Annual fees", TEXT_DOMAIN); ?> <span id="fund-fee">0,86%</span></small>
                </div>
            </div>
            <div class="p-3 calculator__comparison-row">
                <h6 class="calculator__comparison-fund"><?php _e("In Tuleva World Stocks Pension Fund", TEXT_DOMAIN); ?></h6>
                <div class="calculator__comparison-result">
                    <h5 class="mb-0" id="future-value-tuleva">- €</h5>
                    <small class="text-secondary"><?php _e('Annual fees', TEXT_DOMAIN); ?> <span id="fund-fee">0,35%</span></small>
                </div>
            </div>
        </div>
        <div class="calculator__savings">
            <h6 class="calculator__savings-title"><?php _e("Cost of high fees", TEXT_DOMAIN); ?></h6>
            <h1 class="calculator__sum" id="tuleva-saving">- €</h1>
        </div>
    </div>
</div>
