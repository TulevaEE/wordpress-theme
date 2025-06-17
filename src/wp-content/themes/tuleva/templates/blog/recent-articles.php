<?php
global $post;
$current_post_id = $post->ID;
$current_post_categories = get_the_category($current_post_id);
$current_post_cat_id = $current_post_categories[0]->term_id;
?>
<div class="recent-articles">
    <h2 class="recent-articles__heading h5"><?php _e('Recent articles', TEXT_DOMAIN); ?></h2>
    <ul class="recent-articles__list">
        <?php
            $args = [
                        'category' => $current_post_cat_id,
                        'suppress_filters' => 0,
                        'numberposts' => '3',
                        'exclude' => $current_post_id
                    ];

            $recent_posts = wp_get_recent_posts($args);

            foreach($recent_posts as $recent) {
                echo '<li><a href="' . get_permalink($recent['ID']) . '">' .   ( __($recent['post_title'])).'</a> </li> ';
            }

            wp_reset_query();
        ?>
    </ul>
</div>
