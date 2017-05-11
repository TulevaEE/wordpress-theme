<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes(); ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-rows-box<?php
                    if (!get_sub_field('has_padding')) {
                        echo ' no-padding';
                    } ?>">
                    <?php if (have_rows('text_rows')) {
                        while (have_rows('text_rows')) { the_row(); ?>
                        <div class="text-rows-box__row"><?php the_sub_field('text'); ?></div>
                    <?php  }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
