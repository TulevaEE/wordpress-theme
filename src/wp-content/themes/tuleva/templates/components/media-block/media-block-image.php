<?php
$original_image_url = get_sub_field('image');
$image_id = attachment_url_to_postid($original_image_url);
$image_url = wp_get_attachment_image_url($image_id, 'large');
$image_srcset = wp_get_attachment_image_srcset($image_id,'large');
$image_link_url = get_sub_field('image_link_url');
$image_icon_url = get_sub_field('image_icon');
$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
$image_caption = wp_get_attachment_caption($image_id);
?>

<div class="media-block__media">
    <div class="media-block__media__image">
        <?php if ($image_link_url) { ?>
            <a href="<?php echo $image_link_url; ?>">
        <?php } ?>
            <img class="img-fluid" src="<?php echo $image_url; ?>" srcset="<?php echo $image_srcset; ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php if ($image_icon_url) { ?>
                <svg class="media-block__media__icon">
                    <image xlink:href="<?php echo $image_icon_url; ?>"/>
                </svg>
            <?php } ?>
        <?php if ($image_link_url) { ?>
            </a>
        <?php } ?>
    </div>
    <?php if ($image_caption) { ?>
        <p class="m-0 mt-2 image-caption small"><?php echo esc_html($image_caption); ?></p>
    <?php } ?>
</div>
