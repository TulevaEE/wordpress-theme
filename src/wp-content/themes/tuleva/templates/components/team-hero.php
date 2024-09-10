
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
                <div class="text-navy mb-5"><?php echo get_sub_field('text'); ?></div>
                <?php if (get_sub_field('button_text')) { ?>
                    <a href="<?php echo get_sub_field('button_url'); ?>" class="btn btn-lg btn-block mb-3 <?php echo $button_color_class; ?>"><?php the_sub_field('button_text'); ?></a>
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
</section>
