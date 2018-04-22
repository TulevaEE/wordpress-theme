<section id="<?php the_sub_field('component_id'); ?>" class="py-5 cta-change<?php echo get_sub_field('background_color') === 'blue' ? ' bg-blue-washed' : ''; ?>">
    <div class="container">
      <div class="row text-center">
        <div class="d-flex flex-column flex-md-row w-100 justify-content-center align-items-center">
          <h5 class="mb-md-0 mr-md-3"><?php the_sub_field('text'); ?></h5>
          <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-primary btn-lg"><?php the_sub_field('button_text'); ?></a>
        </div>
      </div>
    </div>
</section>
