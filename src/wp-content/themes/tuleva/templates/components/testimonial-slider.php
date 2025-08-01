<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes(); ?>">
    <div class="container">
        <div class="row">
            <div class="testimonial-slider">
                <ul>
                    <?php if (have_rows('testimonials')) {
                        while (have_rows('testimonials')) { the_row(); ?>
                        <li>
                            <div class="row testimonial">
                                <div class="col-sm-2 offset-sm-1 d-none d-sm-block testimonial__media">
                                    <img class="testimonial__image" src="<?php the_sub_field('image'); ?>" alt="">
                                </div>
                                <div class="col-sm-7 col-xs-10 offset-1 testimonial__body">
                                    <div class="testimonial__text"><?php echo get_sub_field('text'); ?></div>
                                    <div class="testimonial__author">
                                        <?php if (get_sub_field('url')) { ?>
                                            <a href="<?php the_sub_field('url'); ?>">
                                        <?php }
                                            the_sub_field('name');
                                        if (get_sub_field('url')) { ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php  }
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
