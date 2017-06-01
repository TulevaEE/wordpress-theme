<?php if ($paged > 1) { ?>
    <div class="pagination">
        <div class="pagination__previous"><?php next_posts_link(__('Older articles', TEXT_DOMAIN)); ?></div>
        <div class="pagination__next"><?php previous_posts_link(__('Newer articles', TEXT_DOMAIN)); ?></div>
    </div>
<?php } else { ?>
    <div class="pagination">
        <?php _e('Older articles'); ?>
        <div class="pagination__previous"><?php next_posts_link(__('Older articles', TEXT_DOMAIN)); ?></div>
    </div>
<?php } ?>
