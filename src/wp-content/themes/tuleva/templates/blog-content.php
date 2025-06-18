<main id="main" class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
