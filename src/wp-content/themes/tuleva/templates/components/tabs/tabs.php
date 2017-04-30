<ul class="nav nav-tabs" role="tablist">
    <?php if (have_rows('tabs')) { $i = 0;
        while (have_rows('tabs')) { $i++; the_row(); ?>
            <li<?php
                if ($i === 1) {
                    echo ' class="active"';
                }
            ?>>
                <a href="#<?php echo to_slug(get_sub_field('name')); ?>" role="tab" data-toggle="tab"><?php the_sub_field('name'); ?></a>
            </li>
    <?php  }
    } ?>
</ul>