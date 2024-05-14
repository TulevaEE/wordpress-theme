<div class="card-body text-center pt-5">
    <h6 class="mb-4">
        <?php the_sub_field('subtitle'); ?>
    </h6>
    <h4>
        <?php if ( have_rows('title') ) while ( have_rows('title') ) : the_row(); ?>
            <?php $link = get_sub_field('link'); ?>
            <?php if ($link) { ?>
                <a href="<?php echo $link; ?>" target="_blank" class="text-navy"><?php echo get_sub_field('text'); ?></a>
            <?php } ?>
        <?php endwhile; ?>
    </h4>
    <?php
        $image_url = get_sub_field('image');
        $title = get_sub_field('title');
    ?>
    <?php if ($image_url) { ?>
        <img src="<?php echo $image_url; ?>" class="img-fluid my-5" alt="<?php echo $title['text'] ?>">
    <?php } ?>
    <div class="bg-white mb-4">
        <?php if ( have_rows('link') ) while ( have_rows('link') ) : the_row(); ?>
            <?php $link = get_sub_field('link'); ?>
            <?php if ($link) { ?>
                <a href="<?php echo $link ?>" class="text-uppercase text-medium d-block mb-4"><?php echo get_sub_field('text'); ?></a>
            <?php } ?>
        <?php endwhile; ?>
            <?php if ( have_rows('button') ) while ( have_rows('button') ) : the_row(); ?>
            <?php $link = get_sub_field('link'); ?>
            <?php if ($link) { ?>
                <a href="<?php echo $link ?>" class="btn btn-primary btn-lg btn-block" id="fund-choose-stocks"><?php echo get_sub_field('text'); ?></a>
            <?php } ?>
        <?php endwhile; ?>
    </div>
    <?php $text = get_sub_field('text'); ?>
    <?php if ($text) { ?>
        <hr class="mb-4">
        <?php echo $text; ?>
    <?php } ?>
</div>
