<main id="main" class="page-container section-spacing">
    <div class="container">
        <div class="row">
            <div class="mx-auto col-lg-9 col-xl-8">
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
</main>
