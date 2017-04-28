<div class="container row-spacing-half">
    <div class="row">
        <div class="contacts-block">
            <?php for( $i = 1; $i < 5; $i++ ) { ?>
                <div class="col-md-3 col-sm-4 col-xs-6 contacts-block__item">
                    <img class="contacts-block__image" src="<?php the_sub_field('image_' . $i); ?>" alt="<?php the_sub_field('name_' . $i); ?>">
                    <h2 class="contacts-block__title"><?php the_sub_field('name_' . $i); ?></h2>
                    <div class="contacts-block__row"><strong><?php the_sub_field('company_' . $i); ?></strong></div>
                    <div class="contacts-block__row"><?php the_sub_field('group_' . $i); ?></div>
                    <div class="contacts-block__row"><?php the_sub_field('role_' . $i); ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row row-spacing-top-half">
        <div class="col-md-6 col-md-offset-3 text-center">
            <?php the_sub_field('text'); ?>
        </div>
    </div>
</div>
