<?php
$heading = get_sub_field('heading') ? get_sub_field('heading') : __('Founding members', TEXT_DOMAIN);
?>
<section id="<?php the_sub_field('component_id'); ?>" class="py-6">
    <div class="container-fluid mb-4">
        <h2 class="mb-5 text-center"><?php echo $heading; ?></h2>
        <?php if (have_rows('founders')) { $i = 0; ?>
            <!-- CAROUSEL -->
            <div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="false">
                <div class="carousel-inner">
                    <?php while (have_rows('founders')) { the_row(); $i++; ?>
                        <div class="carousel-item<?php if ($i === 1) { echo ' active'; } ?>">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-10 m-auto">
                                        <div class="founder-story">
                                            <div class="founder-story__person" style="background-image: url(<?php the_sub_field('image'); ?>)"></div>
                                            <div class="founder-story__content">
                                                <h3 class="mb-0 h4"><?php the_sub_field('name'); ?></h3>
                                                <p class="mb-4"><?php the_sub_field('description'); ?></p>
                                                <p class="mb-4"><?php the_sub_field('fund'); ?></p>
                                               <?php echo get_sub_field('text'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
        <?php } ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <img src="<?php echo get_template_directory_uri() ?>/img/founder-thumbnails.jpg" alt="" class="mb-4">
                <br>
                <a id="founders" href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-outline-primary"><?php the_sub_field('button_text'); ?></a>
            </div>
        </div>
    </div>
</section>
