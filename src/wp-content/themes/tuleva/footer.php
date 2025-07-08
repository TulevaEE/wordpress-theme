<footer id="footer" class="footer">
    <?php if (isset($_GET['development'])) : ?>
        <div class="px-3 d-flex align-items-center justify-content-center">
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
        <div class="row">
            <div class="form-check form-switch">
                <input class="form-check-input high-contrast-toggle" type="checkbox" role="switch" id="high-contrast-toggle">
                <label class="form-check-label" for="high-contrast-toggle">Suurenda kontrasti</label>
            </div>
        </div>
    </div>
</footer>

<?php get_template_part('templates/components/modal'); ?>

<?php wp_footer(); ?>

<?php get_template_part('templates/footer/beacon-toggle-help'); ?>
<?php get_template_part('templates/footer/beacon-translations'); ?>

<?php get_template_part('templates/footer/analytics'); ?>

</body>

</html>
