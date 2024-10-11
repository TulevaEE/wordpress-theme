<section id="<?php the_sub_field('component_id'); ?>" class="py-6">
  <div class="container">
    <div class="row text-center">
      <div class="mx-auto col-lg-11 col-xl-9">
        <h2><?php the_sub_field('heading'); ?></h2>
        <p class="lead m-0"><?php the_sub_field('lead_text'); ?></p>
      </div>
    </div>
    <div class="row py-5 text-center">
      <div class="mx-auto col-lg-11 col-xl-9">
        <img src="<?php the_sub_field('image'); ?>" alt="" class="img-fluid">
      </div>
    </div>
    <div class="row">
      <div class="mx-auto col-md-11 col-lg-8 col-xl-7">
        <div>
          <?php echo get_sub_field('text'); ?>
        </div>
        <div class="mt-5 text-center">
          <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-outline-primary btn-lg"><?php the_sub_field('button_text'); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>
