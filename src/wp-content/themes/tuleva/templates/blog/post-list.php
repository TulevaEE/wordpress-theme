<?php
    $category = get_queried_object();
    $category_id = $wp_query->get_queried_object_id();
    $is_category = is_category();
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
?>

<ul class="post-list<?php if ($paged > 1) { echo ' post-list--paged'; } ?>">
    <?php
    $temp = $wp_query;
    $wp_query = null;
    $is_paged = $paged > 1;
    $posts_number = 9;
    $i = 0;
    $args = [
        'posts_per_page' => $posts_number,
        'paged' => $paged,
        'post_type' => 'post',
    ];

    if ($is_category) {
        $args['cat'] = $category_id;
    }

    if ($category && $category->slug === 'laura-rikkaks') {
        $args['orderby'] = 'publish_date';
        $args['order'] = 'ASC';
    }

    if (isset( $_REQUEST['s'] )) {
        $args['s'] = sanitize_text_field( $_REQUEST['s'] );
    }

    $wp_query = new WP_Query($args);

    while ($wp_query->have_posts()) {
        $wp_query->the_post();
        $i++;
        $is_featured = !$is_paged && $i === 1;
        ?>
        <li class="post-list__item<?php if ($is_featured) { echo ' post-list__item--wide'; } ?>">
            <a class="post-list__item__title<?php echo ($is_featured ? ' h3' : ' h4'); ?>" href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) { ?>
                    <div class="post-list__item__image">
                        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="">
                    </div>
                <?php } ?>
                <h2 class="text-serif<?php echo ($is_featured ? ' h3' : ' h4'); ?>"><?php the_title(); ?></h2>
            </a>
            <div class="post-list__item__meta">
                <span class="post-list__item__author text-navy text-medium"><?php echo get_the_author(); ?></span> <?php the_date(); ?>
            </div>
            <div class="post-list__item__description"><?php the_excerpt(); ?></div>
        </li>
    <?php } ?>
</ul>
