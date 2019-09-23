<div class="card calculator shadow-md">
    <div class="card-body p-3 text-center">
        <form class="pb-3">
            <div class="d-flex flex-row justify-content-between text-medium ml-3 mr-2">
                <span class="calculator__heading">Sisesta oma brutokuupalk:</span>
                <span>
                    <input class="form-control d-inline-block mx-1" id="netWage" type="number" value="1800" step="100" onchange="calculateSaving()">
                    <span class="calculator__heading">eurot</span>
                <span>
            </div>
        </form>
        <div>
            <div class="p-3 calculator__comparison-row">
                <h6 class="calculator__comparison-fund">Kuus saad III sambasse panna:</h6>
                <div class="calculator__comparison-result">
                    <h5 class="mb-0">270€</h5>
                </div>
            </div>
            <div class="p-3 calculator__comparison-row">
                <h6 class="calculator__comparison-fund">Aastas saad III sambasse panna:</h6>
                <div class="calculator__comparison-result">
                    <h5 class="mb-0">3240€</h5>
                </div>
            </div>
        </div>
        <div class="calculator__savings">
            <h6 class="calculator__savings-title">Tulumaksu arvelt võidad</h6>
            <h1 class="calculator__sum">648€</h1>
        </div>
    </div>
</div>
