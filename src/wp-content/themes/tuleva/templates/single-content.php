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
            </div>
        </div>
        <div class="row">
            <div class="col-md-1 col-md-offset-1">
                <div class="post-social">
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri() ?>/img/icon-facebook-gray.svg" alt="Facebook">
                    </a>
                    <a href="#">
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
                <div class="recent-articles">
                    <h5 class="recent-articles__heading">Eelmised artiklid</h5>
                    <ul class="recent-articles__list">
                        <li><a href="#">Valmis tegime! Tuleva käivitab II samba pensionifondid valitsemistasuga 0,34%</a></li>
                        <li><a href="#">Õiguskantsler: II samba paindlikkust tuleks suurendada kindlustusfirmade kasumi arvel</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
