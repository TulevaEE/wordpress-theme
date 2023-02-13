<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php
$isDecember = (date('m') == 12);
?>
<div class="card calculator shadow-md">
    <div class="card-body p-4 text-center third-pillar-calculator">
        <form>
            <?php if ($isDecember) { ?>
                <table class="w-100">
                    <tr class="calculator__summary-row first">
                        <td class="text-right pr-2 py-3 pl-3 bg-gray-tint">
                            <?php _e('<strong><a href="#modal-gross_income">Total gross income this year</a></strong>', TEXT_DOMAIN); ?>
                        </td>
                        <td style="width: 109px" class="py-3 bg-gray-tint">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="yearlyWage"
                                       min="0" step="1" placeholder="24000"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 py-3 pr-3 bg-gray-tint">
                            <span class="d-none d-md-inline-block">
                                <?php _e('&euro;/year', TEXT_DOMAIN); ?>,
                            </span>
                            <span class="d-md-none">&euro;,</span>
                        </td>
                    </tr>
                    <tr class="calculator__summary-row last">
                        <td class="text-right pr-2 pb-3 pl-3 bg-gray-tint">
                            <?php _e('of which dividends', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php _e('Gross income, from which income tax has not been withheld as a private person. For example, dividends received from an Estonian company; dividends that have been taxed abroad.', TEXT_DOMAIN); ?>"
                            ></span>
                        </td>
                        <td class="pb-3 bg-gray-tint">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right"
                                       id="wageDeduction"
                                       min="0" step="1" placeholder="0"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 pb-3 pr-3 bg-gray-tint">
                            <span class="d-none d-md-inline-block">
                                <?php _e('&euro;/year', TEXT_DOMAIN); ?>
                            </span>
                            <span class="d-md-none">&euro;</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-2 py-3 pl-3">
                            <?php _e('Other tax reliefs', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php _e('Home loan interest up to €300, gifts and donations, education costs, including kindergarten fees', TEXT_DOMAIN); ?>"
                            ></span>
                        </td>
                        <td class="py-3">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="taxReliefs"
                                       min="0" step="1" placeholder="300"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 py-3 pr-3">
                            <span
                                class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?></span>
                            <span class="d-md-none">&euro;</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-2 pb-3 pl-3">
                            <?php _e('Number of underage children', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php _e('One parent of children under the age of 18 can deduct additional tax-free income', TEXT_DOMAIN); ?>"
                            ></span>
                        </td>
                        <td class="pb-3">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="kids"
                                       min="0" step="1" placeholder="2"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 pb-3 pr-3">
                            <span
                                class="d-none d-md-inline-block"><?php _e('kids', TEXT_DOMAIN); ?></span>
                        </td>
                    </tr>
                    <tr class="calculator__summary-row first last">
                        <td class="text-right pr-2 py-3 pl-3">
                            <strong>
                                <?php _e('Sensible contribution to III pillar', TEXT_DOMAIN); ?>
                                <span
                                    class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?php _e('Contributions to the III pillar are subject to an income tax discount of up to 15% of taxable income in Estonia, but not more than 6000&nbsp;€/year', TEXT_DOMAIN); ?>"
                                ></span>
                            </strong>
                        </td>
                        <td class="text-right pl-3 py-3">
                            <h1 class="calculator__win" id="yearlyAmount">3600</h1>
                        </td>
                        <td class="text-left pl-2 py-3 pr-3">
                            <h1 class="calculator__win">&euro;</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-2 pt-3 pl-3">
                            <strong>
                                <?php _e('Your gain from income tax', TEXT_DOMAIN); ?><span
                                    class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?php _e('The declaration of income for this year will start on February 15 next year. Remember that you cannot get more income tax back than you paid this year.', TEXT_DOMAIN); ?>"
                                ></span>
                            </strong>
                        </td>
                        <td class="text-right pl-3 pt-3">
                            <h1 class="calculator__win text-green" id="savingsSum">720</h1>
                        </td>
                        <td class="text-left pl-2 pt-3 pr-3">
                            <h1 class="calculator__win text-green">&euro;</h1>
                        </td>
                    </tr>
                </table>
                <a href="<?php echo get_app_url("/3rd-pillar-flow") ?>"
                   class="btn btn-primary btn-lg btn-block mt-3"
                   target="_blank">
                    <?php _e('Make a III pillar payment', TEXT_DOMAIN); ?>
                </a>
                <div class="mt-2">
                    <small class="text-secondary">
                        <?php _e('To make a payment log into your pension account using your ID-card, Mobile-ID or Smart-ID.', TEXT_DOMAIN); ?>
                    </small>
                </div>
            <?php } else { ?>
                <table class="w-100">
                    <tr>
                        <td class="text-right pr-2 pb-3 pl-3">
                            <?php _e('Monthly gross salary', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php _e('Your monthly gross salary (before tax). Including parental benefits, pension, etc.', TEXT_DOMAIN); ?>"
                            ></span>
                        </td>
                        <td style="width: 109px" class="pb-3">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="monthlyWage"
                                       min="0" step="1" placeholder="2000"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 pb-3 pr-3">
                            <span class="d-none d-md-inline-block">
                                <?php _e('&euro;/month', TEXT_DOMAIN); ?>
                            </span>
                            <span class="d-md-none">&euro;</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-2 pb-3 pl-3">
                            <?php _e('Other yearly gross income', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php _e('Yearly gross income taxed at 20% income tax rate such as apartment rental income, Airbnb income, interest earned. Also dividends taxed at 7% personal income tax rate.', TEXT_DOMAIN); ?>"
                            ></span>
                        </td>
                        <td class="pb-3">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right"
                                       id="wageAddition"
                                       min="0" step="1" placeholder="0"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 pb-3 pr-3">
                            <span class="d-none d-md-inline-block">
                                <?php _e('&euro;/year', TEXT_DOMAIN); ?>
                            </span>
                            <span class="d-md-none">&euro;</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-2 pb-3 pl-3">
                            <?php _e('Number of underage children', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php _e('One parent of children under the age of 18 can deduct additional tax-free income', TEXT_DOMAIN); ?>"
                            ></span>
                        </td>
                        <td class="pb-3">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control text-right" id="kids"
                                       min="0" step="1" placeholder="2"
                                       oninput="validity.valid||(value='');">
                            </div>
                        </td>
                        <td class="text-left text-muted pl-2 pb-3 pr-3">
                            <span
                                class="d-none d-md-inline-block"><?php _e('kids', TEXT_DOMAIN); ?></span>
                        </td>
                    </tr>
                    <tr class="calculator__summary-row first last">
                        <td class="text-right pr-2 py-3 pl-3">
                            <strong>
                                <?php _e('<span class="d-inline-block">Reasonable monthly contribution</span> <span class="d-inline-block">to III pillar</span>', TEXT_DOMAIN); ?>
                                <span
                                    class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?php _e('Contributions to the III pillar are subject to an income tax discount of up to 15% of taxable income in Estonia, but not more than 6000&nbsp;€/year', TEXT_DOMAIN); ?>"
                                ></span>
                            </strong>
                        </td>
                        <td class="text-right pl-3 py-3">
                            <h1 class="calculator__win" id="monthlyAmount">300</h1>
                        </td>
                        <td class="text-left pl-2 py-3 pr-3">
                            <h1 class="calculator__win d-inline-block">&euro;</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-2 pt-3 pl-3">
                            <strong>
                                <?php _e('Yearly gain from income tax', TEXT_DOMAIN); ?><span
                                    class="icon-info d-none d-md-inline-block" data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?php _e('The declaration of income for this year will start on February 15 next year. Remember that you cannot get more income tax back than you paid this year.', TEXT_DOMAIN); ?>"
                                ></span>
                            </strong>
                        </td>
                        <td class="text-right pl-3 pt-3">
                            <h1 class="calculator__win text-green" id="savingsSum">720</h1>
                        </td>
                        <td class="text-left pl-2 pt-3 pr-3">
                            <h1 class="calculator__win text-green">&euro;</h1>
                        </td>
                    </tr>
                </table>
                <a href="<?php echo get_app_url("/3rd-pillar-flow") ?>"
                   class="btn btn-primary btn-lg btn-block mt-3"
                   target="_blank">
                    <?php _e('Get started', TEXT_DOMAIN); ?>
                </a>
                <div class="mt-2">
                    <small class="text-secondary">
                        <?php _e('Setting up a recurring III pillar payment is <strong>free of charge</strong> and takes only 5&nbsp;minutes. In order to do that, you need to log in to your pension account.', TEXT_DOMAIN); ?>
                    </small>
                </div>
            <?php } ?>
        </form>
    </div>
</div>
