<?php
$bg_class = get_component_background_color_class();
$image_alignment = strtolower(get_sub_field('image_alignment'));
$original_image_url = get_sub_field('image');
$image_id = attachment_url_to_postid($original_image_url);
$image_url = wp_get_attachment_image_url($image_id, 'large');
$content_class = $image_url ? 'col-lg-6' : 'col-lg-12';
$content_spacing_class = $image_alignment === 'right' ? 'pe-lg-4' : 'ps-lg-4';
$image_spacing_class = $image_alignment === 'right' ? 'ps-lg-4' : 'pe-lg-4';
$left_image_visibility_class = $image_url && $image_alignment === 'right' ? 'd-block d-lg-none' : '';
$right_image_visibility_class = $image_url && $image_alignment === 'right' ? 'd-none d-lg-block' : '';
?>
<section class="d-flex flex-column <?php echo $bg_class; ?>">
    <div class="container my-auto">
        <div class="row align-items-center py-5">
            <?php if ($image_url) { ?>
                <div class="col-lg-6 <?php echo $left_image_visibility_class; ?> <?php echo $image_spacing_class; ?>">
                    <?php get_template_part('templates/components/media-block/media-block-image'); ?>
                </div>
            <?php } ?>

            <div class="<?php echo $content_class ?> <?php echo $content_spacing_class; ?> mx-auto">
                <h3 class="mb-4"><?php the_sub_field('heading') ?></h3>
                <?php if (get_sub_field('lead_text')): ?>
                    <p class="lead mb-3"><?php the_sub_field('lead_text') ?></p>
                <?php endif; ?>
                <?php echo get_sub_field('text'); ?>
            </div>

            <?php if ($image_url && $image_alignment === 'right'): ?>
                <div class="col-lg-6 <?php echo $right_image_visibility_class; ?> <?php echo $image_spacing_class; ?>">
                    <?php get_template_part('templates/components/media-block/media-block-image'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
