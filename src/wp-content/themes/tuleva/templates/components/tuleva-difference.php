<section id="<?php the_sub_field('component_id'); ?>" class="py-6">
<div class="container">
  <div class="row text-center">
    <div class="col-md-10 m-auto">
      <h2 class="mb-4"><?php the_sub_field('heading'); ?></h2>
      <p class="lead"><?php the_sub_field('lead_text'); ?></p>
      <img src="<?php the_sub_field('image'); ?>" alt="" class="img-fluid mb-5">
    </div>
  </div>
  <div class="row">
    <div class="col-md-8 m-auto">
      <div class="mb-5">
          <?php echo get_sub_field('text'); ?>
      </div>
      <div class="text-center">
        <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-outline-primary btn-lg"><?php the_sub_field('button_text'); ?></a>
      </div>
    </div>
  </div>
</div>
</section>
