<?php
global $post;
$current_post_id = $post->ID;
?>
<div class="recent-articles">
    <h5 class="recent-articles__heading">Eelmised artiklid</h5>
    <ul class="recent-articles__list">
        <?php
            $args = [
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
