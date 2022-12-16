<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<div class="card calculator shadow-md">
    <div class="card-body p-4 text-center third-pillar-calculator">
        <form class="pb-2">
            <table style="width: 100%;" class="text-navy">
                <tr>
                    <td class="text-right pr-2 pb-3">
                        <strong>
                            <?php _e('<a href="#modal-gross_income">2022 gross income</a>', TEXT_DOMAIN); ?>
                        </strong>
                    </td>
                    <td style="width: 109px" class="pb-3">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="wage"
                                   min="0" step="1" placeholder="21600"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="pb-3 text-left pl-2">
                        <h5 class="mb-0 text-normal">
                            <span class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?></span>
                            <span class="d-md-none">&euro;</span>
                        </h5>
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2 pb-3">
                        <?php _e('2022 gross income on which income tax is not refundable', TEXT_DOMAIN); ?><span
                            class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                            title="<?php _e('Dividends received from an Estonian company, from which income tax has not been withheld as a private person; income from abroad.', TEXT_DOMAIN); ?>"
                        ></span>
                    </td>
                    <td class="pb-3">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="wageDeduction"
                                   min="0" step="1" placeholder="1000"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="pb-3 text-left pl-2">
                        <h5 class="mb-0 text-normal">
                            <span class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?></span>
                            <span class="d-md-none">&euro;</span>
                        </h5>
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2 pb-3">
                        <?php _e('Other tax reliefs', TEXT_DOMAIN); ?><span
                            class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                            title="<?php _e('Home loan interest up to €300, gifts and donations, education costs, including kindergarten fees', TEXT_DOMAIN); ?>"
                        ></span>
                    </td>
                    <td class="pb-3">
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control text-right" id="taxReliefs"
                                   min="0" step="1" placeholder="300"
                                   oninput="validity.valid||(value='');">
                        </div>
                    </td>
                    <td class="pb-3 text-left pl-2">
                        <h5 class="mb-0 text-normal">
                            <span class="d-none d-md-inline-block"><?php _e('&euro;/year', TEXT_DOMAIN); ?></span>
                            <span class="d-md-none">&euro;</span>
                        </h5>
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2 pb-3">
                        <?php _e('Number of children', TEXT_DOMAIN); ?><span
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
                    <td class="pb-3 text-left pl-2">
                        <h5 class="mb-0 text-normal">
                            <?php _e('kids', TEXT_DOMAIN); ?>
                        </h5>
                    </td>
                </tr>
                <tr class="calculator__summary-row">
                    <td class="text-right pr-2">
                        <strong>
                            <?php _e('This year it is reasonable to contribute to III pillar', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                                title="<?php _e('Contributions to the III pillar are subject to an income tax discount of up to 15% of taxable income in Estonia, but not more than 6000 euros per year', TEXT_DOMAIN); ?>"
                            ></span>
                        </strong>
                    </td>
                    <td class="text-right pl-3">
                        <h1 class="calculator__win text-navy" id="yearlyAmount">3240</h1>
                    </td>
                    <td class="text-left pl-2">
                        <h1 class="calculator__win text-navy">&euro;</h1>
                    </td>
                </tr>
                <tr>
                    <td class="text-right pr-2 pt-3">
                        <strong>
                            <?php _e('Your gain from income tax', TEXT_DOMAIN); ?><span
                                class="icon-info d-none d-md-inline-block" data-toggle="tooltip" data-placement="top"
                                title="<?php _e('The declaration of income for 2022 will start on February 15, 2023. Remember that you cannot get more income tax back than you paid this year.', TEXT_DOMAIN); ?>"
                            ></span>
                        </strong>
                    </td>
                    <td class="text-right pl-3 pt-3">
                        <h1 class="calculator__win text-green" id="savingsSum">648</h1>
                    </td>
                    <td class="pt-3 text-left pl-2">
                        <h1 class="calculator__win text-green">&euro;</h1>
                    </td>
                </tr>
            </table>
            <a href="<?php echo get_app_url("/3rd-pillar-flow") ?>"
               class="btn btn-primary btn-lg btn-block mt-3">
                <?php _e('Make a III pillar payment', TEXT_DOMAIN); ?>
            </a>

        </form>
    </div>
</div>
