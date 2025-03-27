<div id="<?php the_sub_field('component_id'); ?>" class="py-6 bg-blue-washed">
    <div class="container">

        <?php /* Taimar: replace hardcoded values with field values from Story component; delete reasons.php file? */ ?>
        <div class="row text-center pb-6">
            <div class="mx-auto col-lg-9 col-xl-8 text-center">
                <h2 class="mb-4"><?php _e('This is not all', TEXT_DOMAIN) ?></h2>
                <p class="m-0 lead text-balance"><?php _e('As an association of pension savers Tuleva helps to make Estonian laws better so that pension system would be useful first of all to people not banks and insurance companies.', TEXT_DOMAIN) ?></p>
            </div>
        </div>

        <?php if (have_rows('goals')) { ?>
            <div class="d-flex flex-wrap align-items-stretch" style="gap: 24px;">
                <?php while (have_rows('goals')) { the_row();
                    $status = get_sub_field('status');
                    $label = __('Done', TEXT_DOMAIN);
                    $badge_class = 'badge-success';

                    if ($status === 'partially-done') {
                        $label = __('Partially done', TEXT_DOMAIN);
                        $badge_class = 'badge-success-outline';
                    } else if ($status === 'in-progress') {
                        $label = __('In progress', TEXT_DOMAIN);
                        $badge_class = 'badge-primary';
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
