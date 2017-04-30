<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('bg-alt'); ?>">
    <div class="container tabs">
        <h2 class="h1-xs text-center"><?php the_sub_field('title'); ?></h2>
        <?php get_template_part('templates/components/tabs/tabs'); ?>
    </div>
</div>

<div class="<?php echo get_component_classes('container'); ?>">
    <?php get_template_part('templates/components/tabs/tab-content'); ?>
</div>
