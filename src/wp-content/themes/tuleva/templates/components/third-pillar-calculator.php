<div class="card calculator shadow-md">
    <div class="card-body p-3 text-center third-pillar-calculator">
        <form class="pb-3">
            <div class="d-flex flex-row justify-content-between text-medium ml-3 mr-2">
                <span class="calculator__heading"><?php _e('Your monthly gross income', TEXT_DOMAIN); ?>:</span>
                <span>
                    <input class="form-control d-inline-block mx-1" style="max-width: 4.5rem" id="wage" type="number" value="1800" min="0" step="1">
                    <span class="calculator__heading"><?php _e('euros', TEXT_DOMAIN); ?></span>
                <span>
            </div>
        </form>
        <div>
            <div class="p-3 calculator__comparison-row">
                <h6 class="calculator__comparison-fund"><?php _e('You can contribute monthly to III pillar', TEXT_DOMAIN); ?>:</h6>
                <div class="calculator__comparison-result">
                    <h5 class="mb-0" id="monthlyAmount">270 €</h5>
                </div>
            </div>
            <div class="p-3 calculator__comparison-row">
                <h6 class="calculator__comparison-fund"><?php _e('Your can contribute annually to III pillar', TEXT_DOMAIN); ?>:</h6>
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
