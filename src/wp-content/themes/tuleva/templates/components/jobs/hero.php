<?php if( have_rows('hero') ) while ( have_rows('hero') ) : the_row(); ?>
<?php
$image = get_sub_field('image');
$image_url = wp_get_attachment_image_url($image['ID'], 'large');
$image_srcset = wp_get_attachment_image_srcset($image['ID'],'large');
?>
<section class="hero bg-hero-team d-flex flex-column section-spacing">
    <div class="container my-auto">
        <div class="row align-items-center gy-5 gy-sm-6 gx-xl-5">
            <div class="hero-main col-lg-6 text-navy">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>
                <p class="lead mb-3"><?php the_sub_field('lead_text'); ?></p>
                <?php the_sub_field('text'); ?>
            </div>
            <div class="hero-aside col-lg-6">
                <img class="img-fluid" src="<?php echo $image_url; ?>" srcset="<?php echo $image_srcset; ?>" alt="">
            </div>
        </div>
    </div>
</section>
<?php endwhile; ?>
