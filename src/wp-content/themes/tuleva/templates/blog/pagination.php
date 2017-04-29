<?php if ($paged > 1) { ?>
    <div class="pagination">
        <div class="pagination__previous"><?php next_posts_link('Vanemad artiklid'); ?></div>
        <div class="pagination__next"><?php previous_posts_link('Uuemad artiklid'); ?></div>
    </div>
<?php } else { ?>
    <div class="pagination">
        <div class="pagination__previous"><?php next_posts_link('Vanemad artiklid'); ?></div>
    </div>
<?php } ?>