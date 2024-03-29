<footer class="footer">
    <div class="container">
        <div class="row">
            <?php if (is_active_sidebar('footer_widget_area')) : ?>
                <?php dynamic_sidebar('footer_widget_area'); ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if (is_active_sidebar('footer_bottom_widget_area')) : ?>
                <?php dynamic_sidebar('footer_bottom_widget_area'); ?>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php get_template_part('templates/components/modal'); ?>

<?php if (ICL_LANGUAGE_CODE == 'et') {
    // Include newsletter subscription form toggle button
    // get_template_part('templates/footer/beacon-toggle-newsletter');
} ?>

<?php wp_footer(); ?>

<?php get_template_part('templates/footer/cookie-bar'); ?>

<?php get_template_part('templates/footer/beacon-toggle-help'); ?>
<?php get_template_part('templates/footer/beacon-translations'); ?>

<?php get_template_part('templates/footer/analytics'); ?>

</body>

</html>
