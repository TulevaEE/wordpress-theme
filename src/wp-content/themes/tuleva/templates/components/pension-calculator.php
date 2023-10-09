<h2 class="row-spacing-bottom-quarter text-center"><?php _e('Average management fee savings with Tuleva', TEXT_DOMAIN); ?></h2>

<div class="row-spacing-bottom-quarter">
    <div class="row">
        <div class="col-xs-6 text-uppercase slider-title">
            <?php _e('Net wage', TEXT_DOMAIN); ?>
        </div>
        <div class="col-xs-6 text-right slider-title">
            <span><span id="wage">1000</span> &euro;</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <input id="tuleva-calculator-wage" type="range" min="400" max="6000" step="100" value="1000" />
        </div>
    </div>
</div>

<div class="row-spacing-bottom-quarter">
    <div class="row">
        <div class="col-xs-6 text-uppercase slider-title">
            <?php _e('Age', TEXT_DOMAIN); ?>
        </div>
        <div class="col-xs-6 text-right slider-title">
            <span><span id="age">30</span> <?php _e('y/o', TEXT_DOMAIN); ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <input id="tuleva-calculator-age" type="range" min="18" max="64" step="1" value="30" />
        </div>
    </div>
</div>

<div class="popover-calculation">
    <div class="popover bottom">
        <div class="arrow"></div>
        <div class="popover-content">
            <span id="tuleva-calculator-result">20 707</span>&nbsp;&euro;
        </div>
    </div>
</div>
