<section id="<?php the_sub_field('component_id'); ?>">
    <div class="container">
        <div class="row">
            <div class="col-xl-10 m-auto">
                <div class="proposal">
                    <div class="proposal__body">
                        <?php if (get_sub_field('heading')) { ?>
                            <p class="mb-3 lead fw-bold"><?php the_sub_field('heading'); ?></p>
                        <?php } ?>
                        <?php if (have_rows('list')) { ?>
                            <ul class="list-style-checkmark">
                                <?php while (have_rows('list')) { the_row();
                                    remove_filter('acf_the_content', 'wpautop'); ?>
                                    <li><?php echo get_sub_field('text'); ?><a href="<?php the_sub_field('info_icon_link_url'); ?>" class="icon-info" aria-label="<?php _e('Read more', TEXT_DOMAIN) ?>"></a>
                                    </li>
                                <?php add_filter('acf_the_content', 'wpautop');
                                } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <div class="proposal__cta text-center">
                        <?php if (get_sub_field('price')) { ?>
                            <strong class="d-block mb-1 fs-2 lh-1"><?php the_sub_field('price'); ?></strong>
                        <?php } ?>
                        <?php if (get_sub_field('price_description')) { ?>
                            <span class="d-block">
                                <?php the_sub_field('price_description'); ?>
                            </span>
                        <?php } ?>
                        <?php if (get_sub_field('button_text') && get_sub_field('button_url')) { ?>
                            <div class="d-grid">
                                <a href="<?php echo get_sub_field('button_url'); ?>" class="mt-4 mb-3 btn btn-primary btn-lg">
                                <?php the_sub_field('button_text'); ?>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if (get_sub_field('link_text') && get_sub_field('link_url')) { ?>
                            <a href="<?php the_sub_field('link_url'); ?>" class="small">
                                <?php the_sub_field('link_text'); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
