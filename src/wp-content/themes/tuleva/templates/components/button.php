<div class="container<?php
    if (get_sub_field('spacing') === 'half') {
        echo ' row-spacing-half';
    } else {
        echo ' row-spacing';
    } ?>">
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="<?php the_sub_field('url'); ?>" class="btn btn-primary btn-xl"><?php the_sub_field('text'); ?></a>
        </div>
    </div>
</div>
