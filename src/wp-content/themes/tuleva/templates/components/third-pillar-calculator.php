<div class="card calculator shadow-md">
    <div class="card-body p-3 text-center third-pillar-calculator">
        <form class="pb-3">
            <div class="d-flex flex-row justify-content-between text-medium ml-3 mr-2">
                <span class="calculator__heading"><?php _e('Appr. gross income 2021 (<a href="#modal-gross_income">look up on e-MTA</a>)', TEXT_DOMAIN); ?>:</span>
                <span>
                    <input class="form-control d-inline-block" style="max-width: 5rem" id="wage" type="number" value="21600" min="0" step="1">
                    <span class="calculator__heading"><?php _e('euros', TEXT_DOMAIN); ?></span>
                <span>
            </div>
        </form>
        <div>
            <div class="p-3 calculator__comparison-row">
                <h6 class="calculator__comparison-fund"><?php _e('This year you can contribute to III pillar', TEXT_DOMAIN); ?>:</h6>
                <div class="calculator__comparison-result">
                    <h5 class="mb-0" id="yearlyAmount">3240 €</h5>
                </div>
            </div>
        </div>
        <div class="calculator__savings">
            <h6 class="calculator__savings-title"><?php _e('Your gain from income tax', TEXT_DOMAIN); ?></h6>
            <h1 class="calculator__sum" id="savingsSum">648 €</h1>
        </div>
    </div>
</div>
