<h1 class="h1-sm text-center"><?php _e('Who manages Tuleva funds?', TEXT_DOMAIN); ?></h1>

<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes(); ?>">
    <div class="container">
        <div class="row single-quote-box">
            <div class="col-md-4 single-quote-box__media">
                <img class="single-quote-box__image" src="<?php the_sub_field('image'); ?>" alt="">
                <h3><?php the_sub_field('name'); ?></h3>
            </div>
            <div class="col-md-8 single-quote-box__body">
                <blockquote><?php the_sub_field('quote'); ?></blockquote>
                <?php if (get_sub_field('description')) { ?>
                    <p><?php the_sub_field('description'); ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
