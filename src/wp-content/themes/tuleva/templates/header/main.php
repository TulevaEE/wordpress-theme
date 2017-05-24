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
                    <div class="navbar-buttons">
                        <?php language_picker(); ?>
                        <?php if(get_field('button_text', 'option')) { ?>
                            <a href="<?php the_field('button_url', 'option'); ?>" class="btn btn-primary navbar-btn"><?php the_field('button_text', 'option'); ?></a>
                        <?php } ?>
                    </div>
                    <?php get_template_part('templates/header/menu'); ?>
                    <?php language_picker(true); ?>
                </div>
            </div>
        </div>
    </nav>
</header>
