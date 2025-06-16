<?php if( have_rows('generic_modals', 'option') ): ?>
    <?php while( have_rows('generic_modals', 'option') ): the_row(); ?>
        <div id="modal-<?php the_sub_field('modal_id'); ?>" tabindex="-1" class="modal-full">
            <div class="close-button-modal-<?php the_sub_field('modal_id'); ?>">
                <img src="<?php echo get_template_directory_uri() ?>/img/icon-close.svg" alt="<?php _e('Close', TEXT_DOMAIN) ?>">
            </div>
            <div class="modal-full__container">
                <div class="container pt-3 pt-lg-6">
                    <div class="modal-full__content">
                        <div class="row">
                            <div class="col-md-10 m-auto">
                                <h2 class="text-serif text-center mb-5">
                                    <?php the_sub_field('heading'); ?>
                                </h2>
                                <?php echo get_sub_field('text'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
