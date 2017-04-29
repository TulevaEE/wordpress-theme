<div class="container<?php
    if (get_sub_field('spacing') === 'half') {
        echo ' row-spacing-half';
    } else {
        echo ' row-spacing';
    } ?>">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center text-lg">
            <p class="no-margin"><?php the_sub_field('text'); ?></p>
        </div>
    </div>
</div>
