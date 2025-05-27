<div id="<?php the_sub_field('component_id'); ?>" class="py-6 bg-blue-washed">
    <div class="container">

        <div class="row text-center pb-6">
            <div class="mx-auto col-lg-9 col-xl-8 text-center">
                <h2 class="mb-4"><?php the_sub_field('goals_title'); ?></h2>
                <?php the_sub_field('goals_description'); ?>
            </div>
        </div>

        <?php if (have_rows('goals')) { ?>
            <div class="d-flex flex-column flex-md-row flex-md-wrap align-items-stretch" style="gap: 24px;">
                <?php while (have_rows('goals')) { the_row();
                    $status = get_sub_field('status');
                    $label = __('Done', TEXT_DOMAIN);
                    $badge_class = 'badge-success';

                    if ($status === 'partially-done') {
                        $label = __('Partially done', TEXT_DOMAIN);
                        $badge_class = 'text-bg-success-outline';
                    } else if ($status === 'in-progress') {
                        $label = __('In progress', TEXT_DOMAIN);
                        $badge_class = 'text-bg-primary';
                    } ?>
                    <div class="goal-box goal-box--<?php echo $status; ?> p-4 bg-white shadow-sm">
                        <span class="badge <?php echo $badge_class; ?> fs-6">
                            <?php echo $label; ?>
                        </span>
                        <div class="mt-3">
                            <?php the_sub_field('description'); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</div>
