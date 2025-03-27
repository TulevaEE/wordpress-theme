<?php if (get_sub_field('image')) { ?>
    <img src="<?php the_sub_field('image'); ?>" width="96" height="96" class="rounded-circle mb-2" alt="<?php the_sub_field('source'); ?>" />
<?php } ?>
<p class="mb-0 fw-bold"><?php the_sub_field('source'); ?></p>
<?php if (get_sub_field('source_description')) { ?>
    <p class="m-0 small text-secondary"><?php the_sub_field('source_description'); ?></p>
<?php } ?>
