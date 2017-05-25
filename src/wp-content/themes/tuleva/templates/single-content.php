<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?php if (get_the_title()) { ?>
                    <h1 class="page-title post-title"><?php the_title(); ?></h1>
                <?php } ?>
                <div class="post-meta">
                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>" class="post-meta__author"><?php echo get_the_author(); ?></a><span class="post-meta__separator">·</span><span class="post-meta__date"><?php the_date(); ?></span>
                </div>
                <img class="post-image" src="<?php echo the_post_thumbnail_url('full'); ?>" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-1 col-md-offset-1">
                <div class="post-social">
                    <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-facebook-gray.svg" alt="Facebook">
                    </a>
                    <a href="https://twitter.com/share?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-twitter-gray.svg" alt="Twitter">
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <?php if (get_the_content()) { ?>
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                <?php } ?>
                <?php get_template_part('templates/blog/recent-articles'); ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
