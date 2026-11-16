<?php require_once __DIR__ . '/../../helpers/second-pillar-rejoin.php'; ?>
<section id="<?php the_sub_field('component_id'); ?>" class="py-2 bg-gray-2 text-navy text-center">
    <div class="container">
        <?php echo get_sub_field('text'); ?>
        <a href="<?php the_sub_field('url'); ?>" class="text-nowrap"><?php the_sub_field('link_text'); ?></a><?php echo with_second_pillar_rejoin_deadline((string) get_sub_field('text_after_link'), get_current_time_tallinn()); ?>
    </div>
</section>
