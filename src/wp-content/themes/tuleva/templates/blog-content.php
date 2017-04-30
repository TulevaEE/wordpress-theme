<div class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php get_template_part('templates/blog/submenu'); ?>
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
