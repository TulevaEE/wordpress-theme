<div id="comparison-block" class="row row-spacing-quarter"></div>
<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('container'); ?>">
    <div class="row columns-block comparison-component">
        <div class="col-md-6">
            <h2 class="columns-block__heading"><?php the_sub_field('title_1'); ?></h2>
            <?php the_sub_field('text_1'); ?>
        </div>
        <div class="col-md-6">
            <h2 class="columns-block__heading"><?php the_sub_field('title_2'); ?></h2>
            <?php the_sub_field('text_2'); ?>
        </div>
    </div>
</div>
