<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="post-cover">
        <div class="post-cover__image" style="background-image: url('<?php echo get_template_directory_uri() ?>/img/cover.jpg');"></div>
        <img class="post-cover__author" src="<?php echo get_template_directory_uri() ?>/img/tonu-pekk-lg.png" alt="Tõnu Pekk">
    </div>
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
