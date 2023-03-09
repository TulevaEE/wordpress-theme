<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div>
        <div class="container py-6">
            <div class="row">
                <div class="col">
                    <?php if (get_field('heading')) { ?>
                    <h1 class="text-center">
                        <?php the_field('heading'); ?>
                    </h1>
                    <?php } ?>
                    <?php if (get_field('subheading')) { ?>
                    <h4 class="text-center text-normal">
                        <?php the_field('subheading'); ?>
                    </h4>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
        if (have_rows('page_components')) {
            while (have_rows('page_components')) { the_row();
                if (get_row_layout() === 'cta_block') {
                    get_template_part('templates/components/cta-change');
                } else if (get_row_layout() === 'middle_cta') {
                    get_template_part('templates/components/middle-cta');
                }
            }
        }
    ?>
    <div class="container pb-6">
        <?php if( have_rows('contacts_block') ) while ( have_rows('contacts_block') ) : the_row(); ?>
        <div class="row pt-6">
            <div class="col">
                <h2 class="text-center">
                    <?php the_sub_field('block_title'); ?>
                </h2>
                <div class="row contacts-block">
                    <?php if (have_rows('contacts')) {
                            while (have_rows('contacts')) { the_row(); ?>
                    <div class="contacts-block__item">
                        <?php if(get_sub_field('image')) { ?>
                        <img class="contacts-block__image" src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('name'); ?>">
                        <?php } ?>

                        <h4 class="contacts-block__title">
                            <?php the_sub_field('name'); ?>
                        </h4>

                        <?php if(get_sub_field('role')) { ?>
                        <div class="contacts-block__row">
                            <?php the_sub_field('role'); ?>
                        </div>
                        <?php } ?>

                        <?php if(get_sub_field('email')) { ?>
                        <div class="contacts-block__row">
                            <a href="mailto:<?php the_sub_field('email'); ?>">
                                <?php the_sub_field('email'); ?>
                            </a>
                        </div>
                        <?php } ?>

                        <?php if(get_sub_field('phone')) { ?>
                        <div class="contacts-block__row">
                            <?php the_sub_field('phone'); ?>
                        </div>
                        <?php } ?>
                    </div>
                    <?php  }
                        } ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endwhile; ?>
</div>
