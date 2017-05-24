<footer class="footer">
    <div class="container">
        <div class="row">
            <?php if ( is_active_sidebar( 'footer_widget_area' ) ) : ?>
               <?php dynamic_sidebar( 'footer_widget_area' ); ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if ( is_active_sidebar( 'footer_bottom_widget_area' ) ) : ?>
               <?php dynamic_sidebar( 'footer_bottom_widget_area' ); ?>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php get_template_part('templates/footer/beacon-toggle'); ?>

<?php wp_footer(); ?>

<?php get_template_part('templates/footer/beacon-translations'); ?>

<?php get_template_part('templates/footer/analytics'); ?>

</body>
</html>
