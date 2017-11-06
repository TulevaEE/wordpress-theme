<div class="tab-content">
    <?php if (have_rows('tabs')) { $i = 0;
        while (have_rows('tabs')) { $i++; the_row(); ?>
            <div
                role="tabpanel"
                class="tab-pane<?php
                    if ($i === 1) {
                        echo ' active';
                    }
                ?>"
                id="<?php echo to_slug(get_sub_field('name')); ?>">
                <?php if (get_sub_field('columns') == 2) { ?>
                    <div class="col-md-6">
                        <?php the_sub_field('content'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php the_sub_field('content_2'); ?>
                    </div>
                <?php } else { ?>
                    <div class="col-lg-8 mx-md-auto">
                        <?php the_sub_field('content'); ?>
                    </div>
                <?php } ?>
            </div>
    <?php  }
    } ?>
</div>
