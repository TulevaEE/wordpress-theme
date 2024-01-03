<div class="card calculator shadow-md">
    <div class="card-body p-4 text-center second-pillar-payment-rate-calculator">
        <form>
            <table class="w-100">

                <tr class="calculator__summary-row first">
                    <td class="text-right pr-2 py-3 pl-3 bg-gray-tint">
                        <?php _e('Age', TEXT_DOMAIN); ?>
                    </td>
                    <td style="width: 109px" class="py-3 bg-gray-tint">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="yourAge"
                                   min="0" step="1" placeholder="30"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="text-left text-muted pl-2 py-3 bg-gray-tint">
                            <span class="d-none d-md-inline-block text-nowrap">
                                <?php _e('years old', TEXT_DOMAIN); ?>
                            </span>
                        <span class="d-md-none"><?php _e('y/o', TEXT_DOMAIN); ?></span>
                    </td>
                </tr>

                <tr class="calculator__summary-row">
                    <td class="text-right pr-2 pb-3 pl-3 bg-gray-tint">
                        <?php _e('Gross salary', TEXT_DOMAIN); ?>
                    </td>
                    <td style="width: 109px" class="pb-3 bg-gray-tint">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="monthlyWage"
                                   min="0" step="1" placeholder="2000"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="text-left text-muted pl-2 pb-3 pr-3 bg-gray-tint">
                            <span class="d-none d-md-inline-block">
                                <?php _e('&euro;/month', TEXT_DOMAIN); ?>
                            </span>
                        <span class="d-md-none">&euro;</span>
                    </td>
                </tr>

                <tr class="calculator__summary-row">
                    <td class="text-right pr-2 pb-4 pl-3 bg-gray-tint">
                        <?php _e('Your II pillar contribution', TEXT_DOMAIN); ?>
                    </td>
                    <td class="pb-3 bg-gray-tint">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="pillarContribution" id="payment2" value="2"> &nbsp;2%
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="pillarContribution" id="payment4" value="4"> 4%
                            </label>
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="pillarContribution" id="payment6" value="6" checked> 6%
                            </label>
                        </div>
                    </td>
                    <td class="text-left text-muted pl-2 pb-3 pr-3 bg-gray-tint">
                        <span
                            class="icon-info m-0" data-toggle="tooltip"
                            data-placement="top"
                            title="<?php _e('Percentage of gross salary. The state contributes an additional 4% to this.', TEXT_DOMAIN); ?>"></span>
                    </td>
                </tr>

                <tr class="calculator__summary-row last">
                    <td class="text-right pr-2 pb-4 pl-3 bg-gray-tint">
                        <?php _e('Annual return', TEXT_DOMAIN); ?>
                    </td>
                    <td class="pb-3 bg-gray-tint">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-secondary">
                                <input type="radio" name="returnRate" id="returnNeg1" value="-1"> -1%
                            </label>
                            <label class="btn btn-outline-secondary active">
                                <input type="radio" name="returnRate" id="return0" value="0" checked> 0%
                            </label>
                            <label class="btn btn-outline-secondary">
                                <input type="radio" name="returnRate" id="return8" value="7"> 7%
                            </label>
                        </div>
                    </td>
                    <td class="text-left text-muted pl-2 pb-3 bg-gray-tint">
                            <span class="d-none d-md-inline-block text-nowrap">
                                <?php _e('per year', TEXT_DOMAIN); ?>
                            </span>
                        <span class="d-md-none">
                             <?php _e('pa', TEXT_DOMAIN); ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="text-right py-3 pl-3 text-secondary" colspan="2">
                        <?php _e('Compared to a 2% contribution:', TEXT_DOMAIN); ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-right pr-2 pb-2 pl-3">
                        <strong>
                            <?php _e('Net salary', TEXT_DOMAIN); ?>
                        </strong>
                    </td>
                    <td class="text-right pl-3 pb-2">
                        <h4 class="calculator__win" id="netWage">-62</h4>
                    </td>
                    <td class="text-left pl-1 pb-2">
                        <h4 class="calculator__win d-inline-block">&euro;</h4>
                    </td>
                </tr>

                <tr>
                    <td class="text-right pr-2 pb-2 pl-3">
                        <strong>
                            <?php _e('II pillar contribution', TEXT_DOMAIN); ?>
                        </strong>
                    </td>
                    <td class="text-right pl-3 pb-2">
                        <h4 class="calculator__win" id="monthlyContribution">+80</h4>
                    </td>
                    <td class="text-left pl-1 pb-2">
                        <h4 class="calculator__win">&euro;</h4>
                    </td>
                </tr>

                <tr>
                    <td class="text-right pr-2 pl-3">
                        <strong>
                            <?php _e('Accumulates more in II pillar', TEXT_DOMAIN); ?>
                        </strong>
                    </td>
                    <td class="text-right pl-3">
                        <h3 class="calculator__win text-green text-nowrap" id="savingsSum">+33 600</h3>
                    </td>
                    <td class="text-left pl-1">
                        <h3 class="calculator__win text-green">&euro;</h3>
                    </td>
                </tr>
            </table>
            <a href="<?php echo get_app_url("/2nd-pillar-payment-rate") ?>"
               class="btn btn-primary btn-lg btn-block mt-3"
               target="_blank">
                <?php _e('Increase contribution<span class="d-none d-md-inline"> (2 min)</span>', TEXT_DOMAIN); ?>
            </a>
            <div class="mt-2">
                <small class="text-secondary">
                    <?php _e('Increasing your II pillar contribution is <strong>free of charge</strong> and takes only 2&nbsp;minutes. In order to do that, you need to log in to your pension account.', TEXT_DOMAIN); ?>
                </small>
            </div>
        </form>
    </div>
</div>
