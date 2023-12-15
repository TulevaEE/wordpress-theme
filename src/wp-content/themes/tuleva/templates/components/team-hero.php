
<?php
$image = get_sub_field('image');
$image_url = wp_get_attachment_image_url($image['ID'], 'large');
$image_srcset = wp_get_attachment_image_srcset($image['ID'],'large');
$button_color_class = get_component_button_color_class();
$members_count = get_field('members_count', 'option');
$members_count_description = get_sub_field('members_count_description');
$security_text = get_sub_field('security_text');
$security_link_text = get_sub_field('security_link_text');
$security_link_url = get_sub_field('security_link_url');
$shortcode = get_sub_field('shortcode');
$lead_text = get_sub_field('lead_text');
?>
<section id="<?php the_sub_field('component_id'); ?>" class="bg-hero-team d-flex flex-column">
    <div class="container my-auto">
        <div class="row align-items-center py-5">
            <div class="col-lg-6 pr-lg-4 mx-auto">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>
                    <p class="lead text-navy mb-3"><?php echo do_shortcode($lead_text); ?></p>
                <div class="text-navy mb-5"><?php the_sub_field('text'); ?></div>
                <?php if (get_sub_field('button_text')) { ?>
                    <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-lg btn-block mb-3 <?php echo $button_color_class; ?>"><?php the_sub_field('button_text'); ?></a>
                    <?php if (get_sub_field('small_text')): ?>
                        <p class="small text-navy mb-md-5 mb-lg-0">
                            <?php the_sub_field('small_text'); ?>
                        </p>
                    <?php endif; ?>
                <?php } ?>
            </div>
            <div class="col-lg-6 pl-lg-4">
                <?php if ($shortcode) { ?>
                    <?php echo do_shortcode($shortcode); ?>
                <?php } else if ($image_url) { ?>
                    <img class="img-fluid" src="<?php echo $image_url; ?>" srcset="<?php echo $image_srcset; ?> alt="">
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Credentials -->
    <?php if ($security_text || ($members_count && $members_count_description)) { ?>
        <div class="container-fluid bg-blueish-gray d-none d-sm-block py-4">
            <div class="container">
                <div class="row">
                    <?php if ($security_text) { ?>
                        <div class="col col-md-6 d-flex">
                            <img src="<?php echo get_template_directory_uri() ?>/img/icon-lock.svg" alt="<?php echo $security_text; ?>" class="mr-4" />
                            <div class="d-flex flex-column justify-content-center">
                                <p class="mb-2 text-navy"><?php echo $security_text; ?></p>
                                <?php if ($security_link_url && $security_link_text) { ?>
                                    <a id="security" href="<?php echo $security_link_url; ?>" class="text-uppercase text-medium"><?php echo $security_link_text; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($members_count && $members_count_description) { ?>
                        <div class="col-md-6 d-none d-md-flex align-items-center">
                            <span class="membercount mr-4"><?php echo $members_count; ?></span>
                            <p class="mb-0 text-navy"><?php echo $members_count_description; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</section>
