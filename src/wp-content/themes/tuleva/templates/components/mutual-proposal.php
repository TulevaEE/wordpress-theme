<section id="<?php the_sub_field('component_id'); ?>">
    <div class="container">
        <div class="row">
            <div class="col-xl-10 m-auto">
                <div class="proposal">
                    <div class="proposal__body">
                        <?php if (get_sub_field('heading')) { ?>
                            <h5 class="ml-md-3 mb-4"><?php the_sub_field('heading'); ?></h5>
                        <?php } ?>
                        <?php if (have_rows('list')) { ?>
                            <ul class="list-style-checkmark text-navy">
                                <?php while (have_rows('list')) { the_row();
                                    remove_filter('acf_the_content', 'wpautop'); ?>
                                    <li><?php echo get_sub_field('text'); ?><a href="<?php the_sub_field('info_icon_link_url'); ?>"><span class="icon-info"></span></a>
                                    </li>
                                <?php add_filter('acf_the_content', 'wpautop');
                                } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <div class="proposal__cta text-center">
                        <?php if (get_sub_field('price')) { ?>
                            <h1 class="text-sans mb-0"><?php the_sub_field('price'); ?></h1>
                        <?php } ?>
                        <?php if (get_sub_field('price_description')) { ?>
                            <p class="text-navy mb-4">
                                <?php the_sub_field('price_description'); ?>
                            </p>
                        <?php } ?>
                        <?php if (get_sub_field('button_text') && get_sub_field('button_url')) { ?>
                            <a href="<?php the_sub_field('button_url'); ?>" class="btn btn-primary btn-lg btn-block mb-3">
                                <?php the_sub_field('button_text'); ?>
                            </a>
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
