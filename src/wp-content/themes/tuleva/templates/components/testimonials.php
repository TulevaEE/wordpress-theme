<section id="<?php the_sub_field('component_id'); ?>" class="d-none d-md-block py-6 bg-blue-washed">
    <div class="container text-center">
        <?php if (get_sub_field('heading')) { ?>
            <h2 class="mb-5"><?php the_sub_field('heading'); ?></h2>
        <?php } ?>
        <!-- CAROUSEL -->
        <div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="10000">
            <div class="carousel-inner">
                <?php if (have_rows('testimonials')) $i = 0; {
                    while (have_rows('testimonials')) { $i++; the_row(); ?>
                        <div class="carousel-item<?php echo $i === 1 ? ' active' : ''; ?>">
                            <div class="col-md-10 col-lg-8 bg-white m-auto p-5 shadow-sm quote">
                                <div class="d-flex flex-column">
                                    <p class="blockquote text-left mb-5"><?php the_sub_field('text'); ?></p>
                                    <div class="d-flex align-items-center">
                                        <?php if (get_sub_field('image')) { ?>
                                            <img src="<?php the_sub_field('image'); ?>" width="80" height="80" class="rounded-circle mr-3">
                                        <?php } ?>
                                        <div class="text-left"><?php the_sub_field('source'); ?>
                                            <?php if (get_sub_field('description')) { ?>
                                            <br><?php the_sub_field('description'); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php  }
                } ?>
            </div>
            <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>
