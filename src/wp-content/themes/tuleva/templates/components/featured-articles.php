<?php $bg_class = get_component_background_color_class(); ?>
<section id="<?php the_sub_field('component_id'); ?>" class="read-blog section-spacing <?php echo $bg_class; ?>">
<div class="container">
  <h2 class="mb-5 text-center"><?php the_sub_field('heading'); ?></h2>
  <div class="d-flex flex-column flex-lg-row justify-content-center gap-3 gap-sm-4">
    <?php for( $i = 1; $i <= 3; $i++ ) { ?>
        <?php if (get_sub_field('article_' . $i)) { ?>
        <a class="card shadow-sm" href="<?php the_permalink(get_sub_field('article_' . $i)); ?>">
            <img src="<?php echo get_the_post_thumbnail_url(get_sub_field('article_' . $i), 'max-width-500'); ?>" alt="" class="card-img-top rounded-top">
            <div class="card-body text-navy fw-medium">
              <p class="m-0 card-text">
                <?php echo get_the_title(get_sub_field('article_' . $i)); ?>
              </p>
            </div>
        </a>
        <?php } ?>
    <?php  } ?>
  </div>
</div>
</section>
