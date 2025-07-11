<div id="modal-payout-calculator" class="modal-full" tabindex="-1">
    <div class="close-button-modal-payout-calculator">
        <img src="<?php echo get_template_directory_uri() ?>/img/icon-close.svg" alt="<?php _e('Close', TEXT_DOMAIN) ?>">
    </div>
    <div class="modal-full__container">

        <div class="container pt-3 pt-md-6">
            <div class="modal-full__content">
                <div class="row">
                    <div class="col">
                        <h1 class="text-center mb-1 mb-md-6"><?php _e('How does the calculator work?', TEXT_DOMAIN) ?></h1>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-7 p-4">
                        <p><?php _e('Expected returns depend greatly on the evolving rate of return, and neither we nor anyone else are able to guarantee a 7% annual return.', TEXT_DOMAIN) ?></p>
                        <p><?php _e('In 2024, income tax rate is 20%.', TEXT_DOMAIN) ?></p>
                        <p><?php _e('In 2024, annual income up to 14 400 euros gives 7848 euros as annual basic exemption, if annual income increases from 14 400 euros to 25 200 euros, basic exemption decreases according to the following formula: 7848 – 7848 ÷ 10 800 × (income amount – 14 400), if annual income is above 25 200 euros, basic exemption is 0.', TEXT_DOMAIN) ?></p>
                    </div>
                    <div class="col-md-5 p-4 bg-blue-2 rounded">
                        <h3 class="mb-3"><?php _e('Assumptions', TEXT_DOMAIN) ?></h3>
                        <div class="d-flex flex-row flex-wrap">
                            <div class="d-flex justify-content-between eeldus">
                                <p class="mb-0"><?php _e('2025 income tax rate', TEXT_DOMAIN) ?></p>
                                <p class="mb-0 text-bold text-nowrap">22%</p>
                            </div>
                            <div class="d-flex justify-content-between eeldus">
                                <p class="mb-0"><?php _e('2025 basic exemption per month', TEXT_DOMAIN) ?></p>
                                <p class="mb-0 text-bold text-nowrap"><?php _e('up to 700 €', TEXT_DOMAIN) ?></p>
                            </div>
                            <div class="d-flex justify-content-between eeldus">
                                <p class="mb-0"><?php _e('Retirement age', TEXT_DOMAIN) ?></p>
                                <p class="mb-0 text-bold text-nowrap"><?php _e('65 y/o', TEXT_DOMAIN) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
