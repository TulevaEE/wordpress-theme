<main id="main" class="page-container">
    <script>
        var LANGCODE = '<?php echo apply_filters( '
        wpml_current_language ', NULL );  ?>';

    </script>

    <!-- FB share button -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v23.0">
    </script>

    <!-- Twitter share button -->
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 post-header">
                <?php if (get_the_title()) { ?>
                <h1 class="page-title post-title">
                    <?php the_title(); ?>
                </h1>
                <?php } ?>
                <div class="post-meta">
                    <span class="post-meta__author">
                        <?php echo get_the_author(); ?>
                    </span>
                    <span class="post-meta__separator">·</span>
                    <span class="post-meta__date">
                        <?php the_date(); ?>
                    </span>
                    <span class="post-meta__separator">·</span>
                    <?php
                        $categories = get_the_category();
                        $copy = $categories;
                        if ( ! empty( $categories ) ) {
                            foreach($categories as $category) {
                                echo '<a class="post-meta__category" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                                if (next($copy)) {
                                    echo ', '; // Add comma for all elements instead of last
                                }
                            }
                        }
                    ?>
                </div>
                <div class="post-social d-flex flex-wrap justify-content-center align-items-center gap-3">
                    <span><?php _e('Share with a friend:', TEXT_DOMAIN); ?></span>
                    <div class="fb-share-button" data-href="<?php echo esc_url( get_permalink() ); ?>" data-layout="button_count"></div>
                    <a class="twitter-share-button" href="https://twitter.com/intent/tweet">
                    Tweet</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 content-area">
                <img class="post-image" src="<?php echo the_post_thumbnail_url('full'); ?>" alt="">
                <?php if (get_the_content()) { ?>
                <div class="content">
                    <?php the_content(); ?>
                </div>
                <?php } ?>
                <?php get_template_part('templates/blog/recent-articles'); ?>
            </div>
            <div class="col-lg-4 widget-area ps-lg-5">
                <div class="widget">
                    <div class="cta-widget cta-widget--secondary d-none d-md-block">
                        <h2 class="cta-widget__title h4">
                            <?php _e('3 important posts', TEXT_DOMAIN); ?>
                        </h2>
                        <?php
                            if (ICL_LANGUAGE_CODE=='et') {
                                get_template_part('templates/blog/sidebar-articles-et');
                            } elseif (ICL_LANGUAGE_CODE=='en') {
                                get_template_part('templates/blog/sidebar-articles');
                            }
                        ?>
                        <p class="widget-more"><?php _e('<a href="/en/blog/">See more posts</a>', TEXT_DOMAIN); ?></p>
                    </div>
                </div>
                <div class="widget">
                    <div class="cta-widget cta-widget--primary">
                        <h2 class="cta-widget__title h4 text-navy">
                            <?php _e('How much would you lose to high fees?', TEXT_DOMAIN); ?>
                        </h2>
                        <p class="cta-widget__title--link h4">
                            <?php _e('<a href="https://tuleva.ee/en/pension-calculator/">See calculation</a>', TEXT_DOMAIN); ?>
                        </p>
                    </div>
                </div>
                <div class="widget">
                    <div class="cta-widget cta-widget--secondary">
                        <h2 class="cta-widget__title--link h4">
                            <?php _e('<a href="/en/transfer-pension-tuleva/">Transfer pension to Tuleva</a>', TEXT_DOMAIN); ?>
                        </h2>
                        <p>
                            <?php _e('Our guide makes transferring your pension easy via your internet bank. 5 minutes, no costs involved.', TEXT_DOMAIN); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</main>
