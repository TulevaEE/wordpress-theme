<div class="container<?php
    if (get_sub_field('spacing') === 'half') {
        echo ' row-spacing-half';
    } else {
        echo ' row-spacing';
    } ?>">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2 class="h1-xs heading-bottom-spacing"><?php the_sub_field('heading'); ?></h2>
        </div>
        <div class="col-md-8 col-md-offset-2 text-center text-lg"><?php the_sub_field('text'); ?></div>
    </div>
</div>