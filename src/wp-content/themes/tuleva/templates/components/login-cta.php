<?php
$bg_class = get_component_background_color_class();
?>
<section id="<?php the_sub_field('component_id'); ?>" class="<?php echo $bg_class; ?>">
    <div class="d-flex flex-column">
        <div class="container my-auto">
            <div class="row align-items-center py-5">
                <div class="col-lg-12 text-center text-lg-left pr-lg-5 pr-lg-6">
                    <h1><?php the_sub_field('heading'); ?></h1>
                </div>
                <div class="col-lg-6">
                    <p class="lead text-navy"><?php the_sub_field('lead_text'); ?></p>
                    <?php the_sub_field('text'); ?>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center mb-2">
                        <div class="p-2">
                            <img src="<?php echo get_template_directory_uri() ?>/img/id-card-logo.png" style="max-height:32px;" class="img-fluid" alt="Logo 1">
                        </div>
                        <div class="p-2">
                            <img src="<?php echo get_template_directory_uri() ?>/img/mobile-id-logo.png" style="max-height:32px;" class="img-fluid" alt="Logo 2">
                        </div>
                        <div class="p-2">
                            <img src="<?php echo get_template_directory_uri() ?>/img/smart-id-logo.png" style="max-height: 32px;" class="img-fluid" alt="Logo 3">
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-primary btn-lg mb-3"><?php the_sub_field('button_text'); ?></a>
                        <p class="small text-navy mb-md-5 mb-lg-0">
                            <?php the_sub_field('small_text'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
