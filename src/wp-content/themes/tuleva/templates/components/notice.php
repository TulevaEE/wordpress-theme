<section id="<?php the_sub_field('component_id'); ?>" class="py-2 bg-gray-2 text-navy text-center">
    <div class="container">
        <?php echo get_sub_field('text'); ?>
        <a href="<?php the_sub_field('url'); ?>" class="text-nowrap"><?php the_sub_field('link_text'); ?></a>
    </div>
</section>
