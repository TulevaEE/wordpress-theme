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
                <a href="<?php echo get_permalink($page_id); ?>">Kõik artiklid</a>
            </li>
            <li class="submenu__item<?php if ($term_id === $current_object_id) {
                    echo ' submenu__item--current';
                } ?>">
                <a href="<?php echo get_term_link($term_id); ?>">Olulisemad artiklid</a>
            </li>
        </ul>
        <a class="pull-right hidden-xs" href="<?php bloginfo('url'); ?>/feed/rss2/">RSS voog</a>
    </nav>
<?php } ?>
