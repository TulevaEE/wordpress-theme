<header class="header">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-menu">
                    <span class="sr-only"><?php _e('Toggle navigation', TEXT_DOMAIN); ?></span>
                    <span class="burger-icon"></span>
                </button>
                <a class="navbar-brand" href="<?php bloginfo('url') ?>">
                    <img class="brand-logo" alt="Tuleva" src="<?php echo get_template_directory_uri() ?>/img/tuleva-logo.svg">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="header-menu">
                <div class="navbar-right">
                    <?php get_template_part('templates/header/menu'); ?>
                    <?php language_picker(true); ?>
                    <div class="navbar-buttons">
                        <?php language_picker(); ?>
                        <a href="https://pension.tuleva.ee/" class="btn btn-primary navbar-btn"><?php _e('Enter Tuleva app', TEXT_DOMAIN); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
