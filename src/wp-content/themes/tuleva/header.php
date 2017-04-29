<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<?php get_template_part('templates/head'); ?>

<body <?php body_class(''); ?>>

<!--[if lt IE 9]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<header class="header">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-menu">
                    <span class="sr-only">Toggle navigation</span>
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
                        <a href="#" class="btn btn-primary navbar-btn">Sisene Tuleva äppi</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
