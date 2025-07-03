<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('container'); ?>">
    <div class="row contacts-block">
            <?php for( $i = 1; $i < 5; $i++ ) { ?>
                <div class="col-md-3 col-sm-6 col-xs-12 contacts-block__item">
                    <img class="contacts-block__image" src="<?php the_sub_field('image_' . $i); ?>" alt="">
                    <h2 class="contacts-block__title"><?php the_sub_field('name_' . $i); ?></h2>
                    <div class="contacts-block__row"><strong><?php the_sub_field('company_' . $i); ?></strong></div>
                    <div class="contacts-block__row"><?php the_sub_field('group_' . $i); ?></div>
                    <div class="contacts-block__row"><?php the_sub_field('role_' . $i); ?></div>
                </div>
            <?php } ?>
    </div>
    <div class="row section-spacing-half-top">
        <div class="col-md-6 offset-md-3 text-center">
            <?php echo get_sub_field('text'); ?>
        </div>
    </div>
</div>
