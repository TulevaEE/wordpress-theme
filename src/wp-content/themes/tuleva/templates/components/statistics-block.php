<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes(); ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-rows-box<?php
                    if (!get_sub_field('has_padding')) {
                        echo ' no-padding';
                    } ?>">
                    <div class="text-rows-box__row">Tulevaga on liitunud <strong class="text-highlight"><?php the_sub_field('members'); ?></strong> inimest.</div>
                    <div class="text-rows-box__row">Tuleva Maailma Aktsiate Pensionifondi maht on <strong class="text-highlight"><?php echo number_format(get_sub_field('stock_market_volume'), 0, ',', ' '); ?></strong> eurot.</div>
                    <div class="text-rows-box__row">Tuleva Maailma Võlakirjade Pensionifondi maht on <strong class="text-highlight"><?php echo number_format(get_sub_field('bonds_volume'), 0, ',', ' '); ?></strong> eurot.</div>
                </div>
            </div>
        </div>
    </div>
</div>
