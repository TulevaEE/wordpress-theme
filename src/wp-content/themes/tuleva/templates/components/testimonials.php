<section id="<?php the_sub_field('component_id'); ?>" class="d-none d-md-block py-6 bg-blue-washed">
    <div class="container text-center">
        <?php if (get_sub_field('heading')) { ?>
            <h2 class="mb-5"><?php the_sub_field('heading'); ?></h2>
        <?php } ?>
        <!-- CAROUSEL -->
        <div id="carouselControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
            <div class="carousel-inner">
                <?php if (have_rows('testimonials')) $i = 0; {
                    while (have_rows('testimonials')) { $i++; the_row(); ?>
                        <!-- min-height hack so that slides with different heights wouldn’t push content below the carousel (such as FAQ) upwards/downwards -->
                        <div class="carousel-item<?php echo $i === 1 ? ' active' : ''; ?>" style="min-height: 374px;">
                            <div class="col-md-10 col-lg-8 bg-white m-auto p-5 shadow-sm quote">
                                <div class="d-flex flex-column">
                                    <p class="blockquote text-start mb-5"><?php echo get_sub_field('text'); ?></p>
                                    <div class="d-flex align-items-center">
                                        <?php if (get_sub_field('image')) { ?>
                                            <img src="<?php the_sub_field('image'); ?>" width="80" height="80" class="rounded-circle me-3">
                                        <?php } ?>
                                        <div class="text-start"><?php the_sub_field('source'); ?>
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
            <a class="carousel-control-prev" href="#carouselControls" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselControls" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>
</section>
