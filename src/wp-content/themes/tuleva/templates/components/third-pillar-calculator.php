<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<div class="card calculator shadow-md">
    <div class="card-body p-4 text-center third-pillar-calculator">
        <form class="pb-2">
            <table class="w-100">
                <tr class="calculator__summary-row first">
                    <td class="text-right pr-2 py-3 pl-3 bg-gray-tint">
                        <strong>
                            <?php _e('<a href="#modal-gross_income">2022 total gross income</a>', TEXT_DOMAIN); ?>
                        </strong>
                    </td>
                    <td style="width: 109px" class="py-3 bg-gray-tint">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="wage"
                                   min="0" step="1" placeholder="24000"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="text-left text-muted pl-2 py-3 pr-3 bg-gray-tint">
                        <span class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?>,</span>
                        <span class="d-md-none">&euro;,</span>
                    </td>
                </tr>
                <tr class="calculator__summary-row last">
                    <td class="text-right pr-2 pb-3 pl-3 bg-gray-tint">
                        <?php _e('of which dividends', TEXT_DOMAIN); ?><span
                            class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                            title="<?php _e('Gross income, from which income tax has not been withheld as a private person. For example, dividends received from an Estonian company; dividends that have been taxed abroad.', TEXT_DOMAIN); ?>"
                        ></span>
                    </td>
                    <td class="pb-3 bg-gray-tint">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="wageDeduction"
                                   min="0" step="1" placeholder="0"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="text-left text-muted pl-2 pb-3 pr-3 bg-gray-tint">
                        <span class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?></span>
                        <span class="d-md-none">&euro;</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2 py-3 pl-3">
                        <?php _e('Other tax reliefs', TEXT_DOMAIN); ?><span
                            class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
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
                        <span class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?></span>
                        <span class="d-md-none">&euro;</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2 pb-3 pl-3">
                        <?php _e('Number of underage children', TEXT_DOMAIN); ?><span
                            class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
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
                        <span class="d-none d-md-inline-block"><?php _e('kids', TEXT_DOMAIN); ?></span>
                    </td>
                </tr>
                <tr class="calculator__summary-row first last">
                    <td class="text-right pr-2 py-3 pl-3">
                        <strong>
                            <?php _e('Sensible contribution to III pillar', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                                title="<?php _e('Contributions to the III pillar are subject to an income tax discount of up to 15% of taxable income in Estonia, but not more than 6000 euros per year', TEXT_DOMAIN); ?>"
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
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                                title="<?php _e('The declaration of income for 2022 will start on February 15, 2023. Remember that you cannot get more income tax back than you paid this year.', TEXT_DOMAIN); ?>"
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
        </form>
    </div>
</div>
