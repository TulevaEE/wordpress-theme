<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php if (get_the_title()) { ?>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                <?php } ?>
                <div class="post-meta">
                    <a href="#" class="post-meta__author">Tõnu Pekk</a>  11. aprill 2017
                </div>
                <?php if (get_the_content()) { ?>
                <div class="content">
                    <?php the_content(); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>