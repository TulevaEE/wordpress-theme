<?php
global $post;
$current_post_id = $post->ID;
$current_post_categories = get_the_category($current_post_id);
$current_post_cat_id = $current_post_categories[0]->term_id;
?>
<div class="recent-articles">
    <h5 class="recent-articles__heading">Eelmised artiklid</h5>
    <ul class="recent-articles__list">
        <?php
            $args = [
                        'category' => $current_post_cat_id,
                        'suppress_filters' => 0,
                        'numberposts' => '2',
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
