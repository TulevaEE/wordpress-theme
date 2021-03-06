<div class="page-container">
    <div class="container">
        <div class="row row-spacing-bottom">
            <div class="col-md-8 offset-md-2">
                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                    <?php if (get_the_title()) { ?>
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php } ?>
                    <?php if (get_the_content()) { ?>
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    <?php } ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
