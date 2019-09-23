<section id="<?php the_sub_field('component_id'); ?>">
    <div id="inline-signup-anchor"></div>
    <div class="container row-spacing-half">
        <div class="row text-center row-spacing-bottom-quarter">
            <div class="col">
                <h2><?php the_sub_field('heading'); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <?php if (have_rows('list')) { $i = 0; ?>
                    <?php while (have_rows('list')) { the_row(); $i++; ?>
                       <div class="inline-signup__item">
                           <span class="inline-signup__number"><?php echo $i; ?></span><span class="inline-signup__title"><?php the_sub_field('heading'); ?></span>
                       </div>
                       <p class="inline-signup__content">
                            <?php remove_filter('acf_the_content', 'wpautop'); ?>
                            <?php the_sub_field('text'); ?>
                            <?php add_filter('acf_the_content', 'wpautop'); ?>
                       </p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
