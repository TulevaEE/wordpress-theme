<?php $bg_class = get_component_background_color_class(); ?>
<section id="<?php the_sub_field('component_id'); ?>" class="read-blog py-6 <?php echo $bg_class; ?>">
<div class="container">
  <h2 class="mb-5 text-center"><?php the_sub_field('heading'); ?></h2>
  <div class="card-deck">
    <?php for( $i = 1; $i <= 3; $i++ ) { ?>
        <a href="<?php the_permalink(get_sub_field('article_' . $i)); ?>">
          <div class="card shadow-sm">
            <img src="<?php echo get_the_post_thumbnail_url(get_sub_field('article_' . $i), 'max-width-500'); ?>" alt="<?php echo get_the_title(get_sub_field('article_' . $i)); ?>" class="card-img-top rounded-top">
            <div class="card-body">
              <a href="<?php the_permalink(get_sub_field('article_' . $i)); ?>" class="card-text">
                <?php echo get_the_title(get_sub_field('article_' . $i)); ?>
              </a>
            </div>
          </div>
        </a>
    <?php  } ?>
  </div>
</div>
</section>
