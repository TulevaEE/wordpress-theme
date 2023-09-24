
<?php
$image = get_sub_field('image');
$image_url = wp_get_attachment_image_url($image['ID'], 'large');
$image_srcset = wp_get_attachment_image_srcset($image['ID'],'large');
?>
<section id="<?php the_sub_field('component_id'); ?>" class="bg-hero-team d-flex flex-column">
    <div class="container my-auto">
        <div class="row align-items-center py-5">
            <div class="col-lg-6 mx-auto">
                <h1 class="mb-5"><?php the_sub_field('heading'); ?></h1>
                    <p class="lead text-navy mb-3"><?php the_sub_field('lead_text'); ?></p>
                <div class="text-navy mb-5"><?php the_sub_field('text'); ?></div>
                <?php if (get_sub_field('button_text')) { ?>
                    <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-primary btn-lg mb-3 px-6"><?php the_sub_field('button_text'); ?></a>
                    <?php if (get_sub_field('small_text')): ?>
                        <p class="small text-navy mb-md-5 mb-lg-0">
                            <?php the_sub_field('small_text'); ?>
                        </p>
                    <?php endif; ?>
                <?php } ?>
            </div>
            <div class="col-lg-6">
                <img class="img-fluid" src="<?php echo $image_url; ?>" srcset="<?php echo $image_srcset; ?> alt="">
            </div>
        </div>
    </div>
</section>
