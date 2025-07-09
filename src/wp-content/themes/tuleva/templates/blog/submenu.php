<?php
    $page_id = get_template_post('page_blog.php');
    $term_id = get_field('important_articles_category', $page_id);
    $secondary_term_id = get_field('secondary_articles_category', $page_id);
    $current_object_id = $wp_query->get_queried_object_id();
?>
<nav class="submenu">
    <ul class="submenu__list">
        <li class="submenu__item<?php if ($page_id === $current_object_id) {
                echo ' submenu__item--current';
            } ?>">
            <a href="<?php echo get_permalink($page_id); ?>"><?php _e('All articles', TEXT_DOMAIN); ?></a>
        </li>

        <?php if ($term_id && get_term($term_id)) { ?>
            <li class="submenu__item<?php if ($term_id === $current_object_id) {
                    echo ' submenu__item--current';
                } ?>">
                <a href="<?php echo get_term_link($term_id); ?>"><?php echo get_term($term_id)->name; ?></a>
            </li>
        <?php } ?>

        <?php if ($secondary_term_id && get_term($secondary_term_id)) { ?>
            <li class="submenu__item<?php if ($secondary_term_id === $current_object_id) {
                    echo ' submenu__item--current';
                } ?>">
                <a href="<?php echo get_term_link($secondary_term_id); ?>"><?php echo get_term($secondary_term_id)->name; ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>
