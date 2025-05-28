<div id="<?php the_sub_field('component_id'); ?>"
     class="<?php echo get_component_classes('qa-block container'); ?>">
    <div class="row">
        <div class="mx-auto col-lg-9 col-xl-8">
            <?php if (have_rows('accordion')) $i = 0;
            {
                while (have_rows('accordion')) {
                    $i++;
                    the_row(); ?>
                    <div class="qa__question-wrapper" id="accordion-block-<?php echo $i; ?>">
                        <a href="#collapsed-text-<?php echo $i; ?>"
                           class="qa__question collapsed"
                           data-bs-toggle="collapse"
                           data-bs-target="#collapsed-text-<?php echo $i; ?>"
                           aria-expanded="true"
                           aria-controls="collapsed-text-<?php echo $i; ?>">
                            <p class="m-0 fs-4 lh-sm"><?php the_sub_field('title'); ?></p>
                            <?php if (get_sub_field('lead_text')) { ?>
                                <p class="m-0 mt-2 fs-6 fw-normal"><?php the_sub_field('lead_text'); ?></p>
                            <?php } ?>
                        </a>
                        <div id="collapsed-text-<?php echo $i; ?>" class="collapse" data-parent="#accordion">
                            <?php echo get_sub_field('text'); ?>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>
