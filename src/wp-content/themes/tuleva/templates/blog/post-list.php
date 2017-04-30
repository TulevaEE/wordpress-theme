<?php
    $category_id = $wp_query->get_queried_object_id();
    $is_category = is_category();
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
?>

<ul class="post-list<?php if ($paged > 1) { echo ' post-list--paged'; } ?>">
    <?php
    $temp = $wp_query;
    $wp_query = null;
    $is_paged = $paged > 1;
    $posts_number = $is_paged ? 6 : 5;
    $i = 0;
    $args = [
        'posts_per_page' => $posts_number,
        'paged' => $paged
    ];

    if ($is_category) {
        $args['cat'] = $category_id;
    }

    $wp_query = new WP_Query($args);

    while ($wp_query->have_posts()) {
        $wp_query->the_post();
        $i++;
        $is_featured = !$is_paged && $i === 1;
        ?>

        <li class="post-list__item<?php if ($is_featured) { echo ' post-list__item--wide'; } ?>">
            <?php if (has_post_thumbnail()) { ?>
                <div class="post-list__item__image">
                    <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>"></a>
                </div>
            <?php } ?>
            <h3 class="post-list__item__title<?php if ($is_featured) { echo ' h2'; } ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-list__item__meta">
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" class="post-list__item__author"><?php echo get_the_author(); ?></a> <?php the_date(); ?>
            </div>
            <div class="post-list__item__description"><?php the_excerpt(); ?></div>
        </li>

    <?php } ?>
</ul>
