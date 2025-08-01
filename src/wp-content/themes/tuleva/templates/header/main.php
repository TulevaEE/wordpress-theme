<header class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="navbar-wrap d-flex justify-content-between">
            <a class="navbar-brand" href="<?php bloginfo('url') ?>">
                <img class="brand-logo" alt="Tuleva" src="<?php echo get_template_directory_uri() ?>/img/tuleva-logo.svg">
            </a>
            <div class="d-flex align-items-center">
                <span class="d-block d-lg-none me-2"><?php language_picker(); ?></span>
                <a href="<?php _e('https://pension.tuleva.ee/?language=en', TEXT_DOMAIN); ?>" class="d-block d-lg-none me-3 btn btn-outline-primary"><?php _e('Log in', TEXT_DOMAIN); ?></a>
                <button class="navbar-toggler focus-ring collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                    aria-expanded="false" aria-label="<?php _e('Toggle navigation', TEXT_DOMAIN); ?>">
                    <span class="burger-icon"></span>
                </button>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <?php get_template_part('templates/header/menu'); ?>
            <div class="nav-helpers d-none d-lg-flex flex-row ms-lg-auto">
                <span class="d-none d-lg-block"><?php language_picker(); ?></span>
                <a href="<?php _e('https://pension.tuleva.ee/?language=en', TEXT_DOMAIN); ?>" class="d-none d-lg-block btn btn-outline-primary"><?php _e('Log in', TEXT_DOMAIN); ?></a>
                <a href="<?php _e('/en/transfer-pension-tuleva/', TEXT_DOMAIN); ?>" class="btn btn-primary"><?php _e('Bring II pillar to Tuleva', TEXT_DOMAIN); ?></a>
            </div>
        </div>
    </nav>
</header>
