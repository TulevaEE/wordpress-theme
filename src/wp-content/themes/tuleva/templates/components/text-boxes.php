<div class="<?php echo get_component_classes('container'); ?>">
    <div class="row text-boxes">
        <?php for( $i = 1; $i < 3; $i++ ) { ?>
            <div class="col-md-6">
                <div class="text-box<?php
                    if (get_sub_field('box_border_radius') == 8) {
                        echo ' text-box--rounder';
                    } ?>">
                    <h2 class="text-box__title<?php
                        if (get_sub_field('title_' . $i . '_border') === 'blue') {
                            echo ' text-box__title--border-blue';
                        } elseif (get_sub_field('title_' . $i . '_border') === 'lightblue') {
                            echo ' text-box__title--border-lightblue';
                        } ?>"><?php the_sub_field('title_' . $i); ?></h2>
                    <?php the_sub_field('text_' . $i); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
