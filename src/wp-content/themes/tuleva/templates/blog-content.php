<div class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="submenu">
                    <ul class="submenu__list">
                        <li class="submenu__item submenu__item--current">
                            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Kõik artiklid</a>
                        </li>
                        <li class="submenu__item">
                            <a href="#">Olulisemad artiklid</a>
                        </li>
                    </ul>
                    <a class="pull-right hidden-xs" href="<?php bloginfo('url'); ?>/feed/rss2/">RSS voog</a>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="content-area">
                <?php get_template_part('templates/blog/post-list'); ?>
                <?php get_template_part('templates/blog/pagination'); ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <div class="widget-area">
                <div class="cta-widget cta-widget--primary">
                    <h3 class="cta-widget__title">Kuidas saad sina Tulevast kasu?</h3>
                    <a href="#" class="btn btn-primary btn-lg">Sisene Tuleva äppi</a>
                    <p>Näitame sulle, kui palju sa säästaksid Tuleva pensioni-fondi kliendina ja/või Tuleva liikmena.</p>
                </div>
                <div class="cta-widget cta-widget--secondary">
                    <h3 class="cta-widget__title--link"><a href="#">Too pension Tulevasse</a></h3>
                    <p>Juhend, kuidas vahetada fondi internetipangas või pensionikeskuses.</p>
                </div>
            </div>
        </div>
    </div>
</div>
