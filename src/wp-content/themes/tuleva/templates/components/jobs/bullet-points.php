<?php
$class = strtolower(get_sub_field('background_color')) === 'blue' ? 'bg-blue-washed' : '';
?>
<section class="py-6 <?php echo $class ?>">
    <div class="container">
    <h2 class="mb-5"><?php the_sub_field('heading'); ?></h2>
    <?php if( have_rows('bullet_points') ): ?>
        <ul class="list-style-arrow mb-5 lead">
            <?php while( have_rows('bullet_points') ): the_row(); ?>
                <li class="mb-3"><?php the_sub_field('text'); ?></li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
    </div>
</section>
