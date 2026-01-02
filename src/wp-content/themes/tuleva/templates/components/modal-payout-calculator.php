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
                        <p><?php _e('In 2026, income tax rate is 22% and everyone has a flat tax-free income of 700 euros per month (8400 euros per year).', TEXT_DOMAIN) ?></p>
                    </div>
                    <div class="col-md-5 p-4 bg-blue-2 rounded">
                        <h3 class="mb-3"><?php _e('Assumptions', TEXT_DOMAIN) ?></h3>
                        <div class="d-flex flex-row flex-wrap">
                            <div class="d-flex justify-content-between eeldus">
                                <p class="mb-0"><?php _e('2026 income tax rate', TEXT_DOMAIN) ?></p>
                                <p class="mb-0 text-bold text-nowrap">22%</p>
                            </div>
                            <div class="d-flex justify-content-between eeldus">
                                <p class="mb-0"><?php _e('2026 basic exemption per month', TEXT_DOMAIN) ?></p>
                                <p class="mb-0 text-bold text-nowrap">700 €</p>
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
