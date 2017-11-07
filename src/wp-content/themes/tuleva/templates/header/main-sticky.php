<header class="header header--sticky">
    <nav class="navbar navbar-expand-md">
        <div class="container">
            <a class="navbar-brand" href="<?php bloginfo('url') ?>">
                <img class="brand-logo" alt="Tuleva" src="<?php echo get_template_directory_uri() ?>/img/tuleva-logo.svg">
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavDropdownSticky" aria-controls="navbarNavDropdownSticky"
              aria-expanded="false" aria-label="<?php _e('Toggle navigation', TEXT_DOMAIN); ?>">
                <span class="burger-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdownSticky">
                <?php get_template_part('templates/header/menu'); ?>
                <?php language_picker(); ?>
            </div>
        </div>
    </nav>
</header>
