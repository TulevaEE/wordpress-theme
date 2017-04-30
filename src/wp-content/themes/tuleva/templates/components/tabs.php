<div class="<?php echo get_component_classes('bg-alt'); ?>">
    <div class="container tabs">
        <h2 class="h1-xs text-center"><?php the_sub_field('title'); ?></h2>
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
    </div>
</div>

<div class="<?php echo get_component_classes('container'); ?>">
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
                        <div class="col-md-8 col-md-offset-2">
                            <?php the_sub_field('content'); ?>
                        </div>
                    <?php } ?>
                </div>
        <?php  }
        } ?>
    </div>
</div>
