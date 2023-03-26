<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <div class="container row-spacing-half">
            <div class="offset-md-1 col-md-10">
                <?php if (get_field('heading')) { ?>
                    <h1 class="text-center landing-page-headline"><?php the_field('heading'); ?></h1>
                <?php } ?>
                <div><?php the_content(); ?></div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
