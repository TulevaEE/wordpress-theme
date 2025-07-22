<?php
$bg_class = get_component_background_color_class();
?>
<section id="<?php the_sub_field('component_id'); ?>" class="d-flex flex-column section-spacing <?php echo $bg_class; ?>">
    <div class="container my-auto">
        <div class="row align-items-center gx-xl-5">
            <div class="col-lg-12">
                <h1><?php the_sub_field('heading'); ?></h1>
            </div>
            <div class="col-lg-6">
                <?php if (get_sub_field('lead_text')): ?>
                    <p class="lead text-navy"><?php the_sub_field('lead_text'); ?></p>
                <?php endif; ?>
                <?php echo get_sub_field('text'); ?>
            </div>
            <?php if (get_sub_field('button_text') && get_sub_field('button_url')): ?>
                <div class="col-lg-6">
                    <p class="d-flex justify-content-center mt-5 mt-lg-0 mb-4 gap-4">
                        <img src="<?php echo get_template_directory_uri() ?>/img/id-card-logo.png" style="max-height:32px;" class="img-fluid" alt="<?php esc_attr_e( 'ID-card', '' ); ?>">
                        <img src="<?php echo get_template_directory_uri() ?>/img/mobile-id-logo.png" style="max-height:32px;" class="img-fluid" alt="<?php esc_attr_e( 'Mobile-ID', '' ); ?>">
                        <img src="<?php echo get_template_directory_uri() ?>/img/smart-id-logo.png" style="max-height: 32px;" class="img-fluid" alt="<?php esc_attr_e( 'Smart-ID', '' ); ?>">
                    </p>
                    <p class="d-flex flex-column m-0 text-center">
                        <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-primary btn-lg mb-4"><?php the_sub_field('button_text'); ?></a>
                        <?php if (get_sub_field('small_text')): ?>
                            <span class="small text-navy">
                                <?php the_sub_field('small_text'); ?>
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
