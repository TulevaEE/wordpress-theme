<?php
$bg_class     = get_component_background_color_class();
$image_pos    = strtolower( get_sub_field( 'image_alignment' ) );
$img_url      = wp_get_attachment_image_url(
                    attachment_url_to_postid( get_sub_field( 'image' ) ),
                    'large'
                );
$has_img      = ! empty( $img_url );
$content_col  = $has_img
                ? 'col-lg-7 col-xl-6'
                : 'col-lg-12';
$row_dir      = ( $has_img && $image_pos === 'right' )
                ? 'flex-lg-row-reverse'
                : '';
?>
<section class="section-media d-flex flex-column section-spacing <?php echo esc_attr( $bg_class ); ?>">
  <div class="container my-auto">
    <div class="row align-items-start align-items-xl-center gy-5 gx-xl-5 <?php echo esc_attr( $row_dir ); ?>">
      <?php if ( $has_img ) : ?>
        <div class="section-media-slot col-lg-5 col-xl-6">
          <?php get_template_part( 'templates/components/media-block/media-block-image' ); ?>
        </div>
      <?php endif; ?>

      <div class="<?php echo esc_attr( $content_col ); ?> mx-auto">
        <h2 class="mb-4"><?php the_sub_field( 'heading' ); ?></h2>

        <?php if ( get_sub_field( 'lead_text' ) ) : ?>
          <p class="lead mb-3"><?php the_sub_field( 'lead_text' ); ?></p>
        <?php endif; ?>

        <?php echo get_sub_field( 'text' ); ?>
      </div>
    </div>
  </div>
</section>