<?php
$class = strtolower(get_sub_field('background_color')) === 'blue' ? 'bg-blue-washed' : '';
?>
<section class="d-flex flex-column section-spacing <?php echo $class ?>">
    <div class="container container my-auto">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center px-6">
                <p class="lead"><?php echo get_sub_field('text'); ?></p>
            </div>
        </div>
    </div>
</section>
