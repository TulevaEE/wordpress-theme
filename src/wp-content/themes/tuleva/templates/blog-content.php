<main id="main" class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php // The submenu shows which list this is; the heading names it for search engines and screen readers ?>
                <h1 class="visually-hidden"><?php
                    if (is_search()) {
                        echo esc_html(get_search_query());
                    } elseif (is_category()) {
                        single_cat_title();
                    } else {
                        single_post_title();
                    }
                ?></h1>
                <?php get_template_part('templates/blog/submenu'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 content-area">
                <?php get_template_part('templates/blog/post-list'); ?>
                <?php get_template_part('templates/blog/pagination'); ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <div class="col-lg-4 widget-area ps-lg-5">
                <?php if ( is_active_sidebar( 'blog_sidebar_widget_area' ) ) : ?>
                   <?php dynamic_sidebar( 'blog_sidebar_widget_area' ); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
