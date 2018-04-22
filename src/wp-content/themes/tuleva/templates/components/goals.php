<div id="<?php the_sub_field('component_id'); ?>" class="container">

    <?php if (have_rows('goals')) { ?>
        <div class="row row-spacing-half">
            <?php while (have_rows('goals')) { the_row();
                $status = get_sub_field('status');
                $label = __('Done', TEXT_DOMAIN);

                if ($status === 'partially-done') {
                    $label = __('Partially done', TEXT_DOMAIN);
                } else if ($status === 'in-progress') {
                   $label = __('In progress', TEXT_DOMAIN);
                } ?>
                <div class="col-sm-6 col-md-4">
                    <div class="goal-box goal-box--<?php echo $status ?>">
                        <span class="goal-box__title goal-box__title--<?php echo $status ?>"><?php echo $label ?></span>
                        <div class="goal-box__content"><?php the_sub_field('description'); ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
