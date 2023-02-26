<?php
$bg_class = strtolower(get_sub_field('background_color')) === 'blue' ? 'bg-blue-washed' : '';
$image_alignment = strtolower(get_sub_field('image_alignment'));
$image_url = get_sub_field('image');
$content_class = $image_url ? 'col-lg-6' : 'col-lg-12';
?>
<section class="d-flex flex-column <?php echo $bg_class; ?>">
    <div class="container container my-auto">
        <div class="row align-items-center py-5">
            <?php if ($image_url && $image_alignment === 'left'): ?>
                <div class="col-lg-6">
                    <img class="img-fluid mb-4" src="<?php echo $image_url; ?>" alt="<?php the_sub_field('heading') ?>">
                </div>
            <?php endif; ?>

            <div class="<?php echo $content_class ?> mx-auto">
                <h3 class="mb-4"><?php the_sub_field('heading') ?></h3>
                <?php if (get_sub_field('lead_text')): ?>
                    <p class="lead mb-3"><?php the_sub_field('lead_text') ?></p>
                <?php endif; ?>
                <?php the_sub_field('text') ?>
            </div>

            <?php if ($image_url && $image_alignment === 'right'): ?>
                <div class="col-lg-6">
                    <img class="img-fluid mb-4" src="<?php echo $image_url; ?>" alt="<?php the_sub_field('heading') ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
