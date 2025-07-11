<section id="<?php the_sub_field('component_id'); ?>">
    <div class="container section-spacing">
        <div class="row">
            <div class="mx-auto col-lg-9 col-xl-8 text-center">
                <h2><?php the_sub_field('heading'); ?></h2>
                <?php echo get_sub_field('text'); ?>
            </div>
        </div>
        <div class="row">
            <div class="mx-auto col-md-10 col-lg-8 col-xl-7">
                <?php if (have_rows('list')) { $i = 0; ?>
                    <?php while (have_rows('list')) { the_row(); $i++; ?>
                    <div class="inline-register__item mb-0">
                        <span class="inline-register__number"><?php echo $i; ?></span><span class="inline-register__title"><?php the_sub_field('heading'); ?></span>
                    </div>
                    <p class="inline-register__content">
                        <?php remove_filter('acf_the_content', 'wpautop'); ?>
                        <?php echo get_sub_field('text'); ?>
                        <?php add_filter('acf_the_content', 'wpautop'); ?>
                    </p>
                    <?php } ?>
                <?php } ?>
                <div id="inline-signup" class="card p-4 bg-blue-2 text-center">
                    <p class="mb-4"><?php _e('To become a member please log in to your pension account.', TEXT_DOMAIN); ?></p>
                    <a href="https://pension.tuleva.ee/join" class="btn btn-primary btn-lg"><?php _e('Become a member', TEXT_DOMAIN); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
