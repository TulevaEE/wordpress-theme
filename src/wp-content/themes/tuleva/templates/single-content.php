<div class="page-container">
    <script>
        var LANGCODE = '<?php echo apply_filters( '
        wpml_current_language ', NULL );  ?>';

    </script>
    <div id="fb-root"></div>
    <!-- FB share button -->
    <script>
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- Twitter share button -->
    <script>
        window.twttr = (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0],
                t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);

            t._e = [];
            t.ready = function (f) {
                t._e.push(f);
            };

            return t;
        }(document, "script", "twitter-wjs"));
    </script>

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
                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>" class="post-meta__author">
                        <?php echo get_the_author(); ?>
                    </a>
                    <span class="post-meta__separator">·</span>
                    <span class="post-meta__date">
                        <?php the_date(); ?>
                    </span>
                    <span class="post-meta__separator">·</span>
                    <span><?php _e('Category:', TEXT_DOMAIN); ?></span>
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
                <div class="post-social">
                    <span><?php _e('Share with a friend:', TEXT_DOMAIN); ?></span>
                    <div class="fb-share-button" data-layout="button_count"></div>
                    <a class="twitter-share-button" href="https://twitter.com/intent/tweet">
                    Tweet</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="content-area">
                <img class="post-image" src="<?php echo the_post_thumbnail_url('full'); ?>" alt="">
                <?php if (get_the_content()) { ?>
                <div class="content">
                    <?php the_content(); ?>
                </div>
                <?php } ?>
                <?php get_template_part('templates/blog/recent-articles'); ?>
            </div>
            <div class="widget-area">
                <div class="article-widget d-none d-md-block">
                    <h4 class="article-widget__title">
                        <?php _e('3 important posts', TEXT_DOMAIN); ?>
                    </h4>
                    <?php
                        if (ICL_LANGUAGE_CODE=='et') {
                            get_template_part('templates/blog/sidebar-articles-et');
                        } elseif (ICL_LANGUAGE_CODE=='en') {
                            get_template_part('templates/blog/sidebar-articles');
                        }
                        ?>
                        <?php _e('<a class="d-block text-uppercase text-medium text-center" href="/en/blog/">See more posts</a>', TEXT_DOMAIN); ?>
                </div>
                <div class="widget-area__cta">
                    <div class="widget">
                        <div class="cta-widget cta-widget--primary">
                            <h4 class="cta-widget__title">
                                <?php _e('How much would you lose to high fees?', TEXT_DOMAIN); ?>
                            </h4>
                            <?php _e('<a class="btn btn-primary btn-lg btn-block" href="https://tuleva.ee/en/">See calculation</a>', TEXT_DOMAIN); ?>
                        </div>
                    </div>
                    <div class="widget">
                        <div class="cta-widget cta-widget--secondary">
                            <h5 class="cta-widget__title--link">
                                <?php _e('<a href="/en/transfer-pension-tuleva/">Transfer pension to Tuleva</a>', TEXT_DOMAIN); ?>
                            </h5>
                            <p>
                                <?php _e('Our guide makes transferring your pension easy via your internet bank. 5 minutes, no costs involved.', TEXT_DOMAIN); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
