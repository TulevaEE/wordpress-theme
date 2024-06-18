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
                    if (get_row_layout() === 'mutual_hero') {
                        get_template_part('templates/components/mutual-hero');
                    } else if (get_row_layout() === 'mutual_proposal') {
                        get_template_part('templates/components/mutual-proposal');
                    } else if (get_row_layout() === 'team_hero') {
                        get_template_part( 'templates/components/team-hero' );
                    } else if (get_row_layout() === 'fees_hero') {
                        get_template_part( 'templates/components/fees-hero' );
                    } elseif (get_row_layout() === 'cta_block') {
                        get_template_part('templates/components/cta-change');
                    } elseif (get_row_layout() === 'emphasis_cta_block') {
                        get_template_part('templates/components/emphasis-cta-block');
                    } else if (get_row_layout() === 'story') {
                        get_template_part('templates/components/story');
                    } else if (get_row_layout() === 'goals') {
                        get_template_part('templates/components/goals');
                    } else if (get_row_layout() === 'middle_cta') {
                        get_template_part('templates/components/middle-cta');
                    } else if (get_row_layout() === 'founder_carousel') {
                        get_template_part('templates/components/founder-carousel');
                    } else if (get_row_layout() === 'signup_block') {
                        get_template_part('templates/components/signup-block');
                    } else if (get_row_layout() === 'login_block') {
                        get_template_part('templates/components/login-block');
                    } else if (get_row_layout() === 'steps_block') {
                        get_template_part('templates/components/steps-block');
                    } else if (get_row_layout() === 'text_block') {
                        get_template_part('templates/components/text-block');
                    } else if (get_row_layout() === 'rich_text_block') {
                        get_template_part('templates/components/rich-text-block');
                    } else if (get_row_layout() === 'qa_block') {
                        get_template_part('templates/components/qa-block');
                    } else if (get_row_layout() === 'accordion_block') {
                        get_template_part('templates/components/accordion-block');
                    } else if (get_row_layout() === 'third_pillar_hero') {
                        get_template_part('templates/components/third-pillar-hero');
                    } else if (get_row_layout() === 'second_pillar_payment_rate_hero') {
                        get_template_part('templates/components/second-pillar-payment-rate-hero');
                    } else if (get_row_layout() === 'featured_articles') {
                        get_template_part('templates/components/featured-articles');
                    } else if (get_row_layout() === 'media_block_with_cta') {
                        get_template_part('templates/components/tuleva-difference');
                    } else if (get_row_layout() === 'media_block') {
                        get_template_part('templates/components/media-block/media-block');
                    } if (get_row_layout() === 'login_cta') {
                        get_template_part( 'templates/components/login-cta' );
                    } else if (get_row_layout() === 'calculator_cta') {
                        get_template_part('templates/components/calculator-cta');
                    }
                }
            }
        ?>
        <?php if( have_rows('contacts_block') ) while ( have_rows('contacts_block') ) : the_row(); ?>
            <section class="d-flex flex-column <?php echo get_component_background_color_class(); ?>">
                <div class="container">
                    <div class="row my-6">
                        <div class="col">
                            <h2 class="text-center">
                                <?php the_sub_field('block_title'); ?>
                            </h2>
                        </div>
                    </div>
                    <?php if (have_rows('contact_categories')) { ?>
                        <?php while (have_rows('contact_categories')) { the_row(); ?>
                            <div class="row my-6">
                                <div class="col-md-12">
                                    <h3 class="text-center">
                                        <?php the_sub_field('category_title'); ?>
                                    </h3>
                                    <div class="row contacts-block">
                                        <?php if (have_rows('contacts')) { ?>
                                            <?php while (have_rows('contacts')) { the_row(); ?>
                                                <div class="contacts-block__item px-4">
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
                                            <?php  } ?>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        <?php  } ?>
                    <?php  } ?>
                    </div>
                </div>
            </section>
        <?php endwhile; ?>
    <?php endwhile; ?>
</div>
