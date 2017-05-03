<?php
    $page_id = get_template_post('page_blog.php');
    $term_id = get_field('important_articles_category', $page_id);
    $current_object_id = $wp_query->get_queried_object_id();

    if ($term_id) {
?>
    <nav class="submenu">
        <ul class="submenu__list">
            <li class="submenu__item<?php if ($page_id === $current_object_id) {
                    echo ' submenu__item--current';
                } ?>">
                <a href="<?php echo get_permalink($page_id); ?>"><?php _e('All articles', TEXT_DOMAIN); ?></a>
            </li>
            <li class="submenu__item<?php if ($term_id === $current_object_id) {
                    echo ' submenu__item--current';
                } ?>">
                <a href="<?php echo get_term_link($term_id); ?>"><?php _e('Featured articles', TEXT_DOMAIN); ?></a>
            </li>
        </ul>
        <a class="pull-right hidden-xs" href="<?php bloginfo('url'); ?>/feed/rss2/"><?php _e('RSS feed', TEXT_DOMAIN); ?></a>
    </nav>
<?php } ?>
