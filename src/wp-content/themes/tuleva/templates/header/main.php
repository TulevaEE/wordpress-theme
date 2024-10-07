<header class="header">
    <nav class="navbar navbar-expand-md">
        <a class="navbar-brand" href="<?php bloginfo('url') ?>">
            <img class="brand-logo" alt="Tuleva" src="<?php echo get_template_directory_uri() ?>/img/tuleva-logo.svg">
        </a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="<?php _e('Toggle navigation', TEXT_DOMAIN); ?>">
            <span class="burger-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <?php get_template_part('templates/header/menu'); ?>
            <div class="d-flex flex-column flex-md-row align-items-md-center ml-auto">
                <?php language_picker(); ?>
                <a href="<?php _e('https://pension.tuleva.ee/?language=en', TEXT_DOMAIN); ?>" class="btn btn-outline-primary ml-md-2 mt-4 mt-md-0"><?php _e('Log in', TEXT_DOMAIN); ?></a>
                <a href="<?php _e('/en/transfer-pension-tuleva/', TEXT_DOMAIN); ?>" class="btn btn-primary btn-block d-block d-md-none d-lg-block ml-md-2 mt-4 mt-md-0"><?php _e('Choose Tuleva 2nd pillar', TEXT_DOMAIN); ?></a>
            </div>
        </div>
    </nav>
</header>
