<footer class="footer">
    <?php if (isset($_GET['development']) && $_GET['development'] === 'true') : ?>
        <div class="d-flex align-items-center justify-content-center">
            <?php get_template_part('templates/footer/not-logged-in-mailing-list-form'); ?>
        </div>
    <?php endif; ?>
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
