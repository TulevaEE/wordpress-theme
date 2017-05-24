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
                    <h3 class="cta-widget__title"><?php _e('How does Tuleva benefit you?', TEXT_DOMAIN); ?></h3>
                    <a href="<?php echo get_app_url(); ?>" class="btn btn-primary btn-lg"><?php _e('Enter Tuleva app', TEXT_DOMAIN); ?></a>
                    <p><?php _e('We will show you how much money you would save as our pension fund client and/or Tuleva member.', TEXT_DOMAIN); ?></p>
                </div>
                <div class="cta-widget cta-widget--secondary">
                    <h3 class="cta-widget__title--link"><a href="<?php bloginfo('url') ?>/kuidas-tuua-pension-tulevasse/"><?php _e('Transfer your pension to Tuleva', TEXT_DOMAIN); ?></a></h3>
                    <p><?php _e('A guide how to transfer your pension through your internet bank or the Pensionikeskus web page.', TEXT_DOMAIN); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
