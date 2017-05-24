<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 footer__column">
                <h4 class="footer__title"><?php _e('Membership fee account', TEXT_DOMAIN); ?></h4>
                <div class="footer__column__text">
                    <div class="footer__column__text__row">Tulundusühistu Tuleva</div>
                    <div class="footer__column__text__row">EE132200221064032136</div>
                    <div class="footer__column__text__row">Swedbank</div>
                </div>
            </div>
            <div class="col-md-3 footer__column">
                <h4 class="footer__title">Tulundusühistu Tuleva</h4>
                <div class="footer__column__text">
                    <div class="footer__column__text__row">Telliskivi 60, Tallinn, 10412</div>
                    <div class="footer__column__text__row">+372 644 5100</div>
                    <div class="footer__column__text__row"><?php _e('Reg. Code', TEXT_DOMAIN); ?>: 14041764</div>
                </div>
            </div>
            <div class="col-md-3 footer__column">
                <h4 class="footer__title">Tuleva Fondid AS</h4>
                <div class="footer__column__text">
                    <div class="footer__column__text__row">Telliskivi 60, Tallinn, 10412</div>
                    <div class="footer__column__text__row">+372 644 5100</div>
                    <div class="footer__column__text__row"><?php _e('Activity license nr', TEXT_DOMAIN); ?>: 4.1-1/25</div>
                </div>
            </div>

            <?php if ( is_active_sidebar( 'footer_widget_area' ) ) : ?>
               <?php dynamic_sidebar( 'footer_widget_area' ); ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-12 footer__text">
                <?php get_template_part('templates/footer/text'); ?>
            </div>
        </div>
    </div>
</footer>

<?php get_template_part('templates/footer/beacon-toggle'); ?>

<?php wp_footer(); ?>

<?php get_template_part('templates/footer/beacon-translations'); ?>

<?php get_template_part('templates/footer/analytics'); ?>

</body>
</html>
