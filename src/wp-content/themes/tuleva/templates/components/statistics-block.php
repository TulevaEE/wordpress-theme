<div class="<?php echo get_component_classes(); ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-rows-box<?php
                    if (!get_sub_field('has_padding')) {
                        echo ' no-padding';
                    } ?>">
                    <div class="text-rows-box__row">Tuleva fondidega on liitunud <strong class="text-highlight"><?php the_sub_field('members'); ?></strong> inimest.</div>
                    <div class="text-rows-box__row">Tuleva maailma aktsiaturu pensionifondi maht on <strong class="text-highlight"><?php echo number_format(get_sub_field('stock_market_volume'), 0, ',', ' '); ?></strong> eurot.</div>
                    <div class="text-rows-box__row">Tuleva maailma võlakirjade pensionifondi maht on <strong class="text-highlight"><?php echo number_format(get_sub_field('bonds_volume'), 0, ',', ' '); ?></strong> eurot.</div>
                </div>
            </div>
        </div>
    </div>
</div>
