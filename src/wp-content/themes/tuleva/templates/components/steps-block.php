<section id="<?php the_sub_field('component_id'); ?>">
    <div class="container section-spacing">
        <div class="row">
            <div class="mx-auto col-lg-9 col-xl-8">
            <h2 class="mb-4 text-center"><?php the_sub_field('heading'); ?></h2>
                <?php if (have_rows('list')) { $i = 0; ?>
                    <?php while (have_rows('list')) { the_row(); $i++; ?>
                       <div class="inline-register__item">
                           <span class="inline-register__number"><?php echo $i; ?></span><span class="inline-register__title"><?php the_sub_field('heading'); ?></span>
                       </div>
                       <p class="inline-register__content">
                            <?php remove_filter('acf_the_content', 'wpautop'); ?>
                            <?php echo get_sub_field('text'); ?>
                            <?php add_filter('acf_the_content', 'wpautop'); ?>
                       </p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
