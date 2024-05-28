<section id="<?php the_sub_field('component_id'); ?>" class="py-5 cta-emphasis">
    <div class="container">
      <div class="row text-center">
        <div class="d-flex flex-column flex-md-row w-100 justify-content-center align-items-center">
          <div class="col-md-10 col-lg-8 mx-auto">
                <div class="text-center emphasis-box p-4 p-md-5">
                    <h4 class="mb-4"><?php the_sub_field('title'); ?></h4>
                    <?php while (have_rows('buttons')) { the_row(); ?>
                        <?php
                            $button_color = get_sub_field('button_color');
                            $button_classes = 'btn btn-lg d-block d-md-inline-block mb-4 mb-md-0 mx-md-2';

                            if ($button_color === 'blue') {
                                $button_classes .= ' btn-primary';
                            } else {
                                $button_classes .= ' btn-outline-primary';
                            }
                        ?>
                        <a class="<?php echo $button_classes; ?>" href="<?php echo get_sub_field('button_url'); ?>"><?php the_sub_field('button_text'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>
