<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('container'); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="media">
                <?php if (get_sub_field('image_alignment') === 'left') { ?>
                    <div class="media-left">
                        <img class="media-object" src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('title'); ?>">
                    </div>
                <?php } ?>
                <div class="media-body">
                    <h2 class="media-heading"><?php the_sub_field('title'); ?></h2>
                    <?php echo get_sub_field('text'); ?>
                </div>
                <?php if (get_sub_field('image_alignment') === 'right') { ?>
                    <div class="media-right">
                        <img class="media-object" src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('title'); ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
