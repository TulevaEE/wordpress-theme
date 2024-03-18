<div id="<?php the_sub_field('component_id'); ?>" class="<?php echo get_component_classes('qa-block qa-block--collapsed container'); ?>">
    <div class="row">
        <div class="mx-md-auto col-md-10 col-lg-8">
            <?php if (have_rows('accordion')) $i = 0; {
                while (have_rows('accordion')) { $i++; the_row(); ?>
                    <div class="qa__question-wrapper" id="accordion-block-<?php echo $i; ?>">
                        <div class="principle__item">
                            <button class="btn btn-link btn-block principle__item--link" data-toggle="collapse" data-target="#collapsed-text-<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne">
                                <?php the_sub_field('title'); ?>
                            </button>
                            <?php if (get_sub_field('lead_text')) { ?>
                                <p><?php the_sub_field('lead_text'); ?></p>
                            <?php } ?>
                        </div>
                        <div id="collapsed-text-<?php echo $i; ?>" class="collapse mt-2 mb-5" data-parent="#accordion">
                            <?php the_sub_field('text'); ?>
                        </div>
                    </div>
                    <hr />
                <?php  }
            } ?>
        </div>
    </div>
</div>
