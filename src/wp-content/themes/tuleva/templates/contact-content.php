<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <div class="container">
            <div class="row row-spacing-bottom">
                <div class="col-md-12">
                    <?php if (get_field('heading')) { ?>
                        <h1 class="page-title"><?php the_field('heading'); ?></h1>
                    <?php } ?>
                    <?php if (get_field('subheading')) { ?>
                        <h2 class="text-center text-normal"><?php the_field('subheading'); ?></h2>
                    <?php } ?>
                </div>
            </div>
            <div class="row row-spacing">
                <div class="col-md-12">
                    <div class="row contacts-block">
                        <?php if (have_rows('contacts')) {
                            while (have_rows('contacts')) { the_row(); ?>
                                <div class="col-md-4 contacts-block__item">
                                    <?php if(get_sub_field('image')) { ?>
                                        <img class="contacts-block__image" src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('name'); ?>">
                                    <?php } ?>

                                    <h2 class="contacts-block__title"><?php the_sub_field('name'); ?></h2>

                                    <?php if(get_sub_field('email')) { ?>
                                        <div class="contacts-block__row">
                                            <a href="mailto:<?php the_sub_field('email'); ?>"><?php the_sub_field('email'); ?></a>
                                        </div>
                                    <?php } ?>

                                    <?php if(get_sub_field('phone')) { ?>
                                        <div class="contacts-block__row"><?php the_sub_field('phone'); ?></div>
                                    <?php } ?>
                                </div>
                        <?php  }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
