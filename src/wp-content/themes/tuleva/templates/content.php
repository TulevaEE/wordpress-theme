<div class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                    <?php if (get_the_title()) { ?>
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php } ?>
                    <div class="content">
                        <?php if (get_the_content()) { ?>
                            <?php the_content(); ?>
                        <?php } ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
