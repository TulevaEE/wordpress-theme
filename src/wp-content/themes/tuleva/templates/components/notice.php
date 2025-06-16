<section id="<?php the_sub_field('component_id'); ?>" class="py-2 notice bg-notice" role="alert">
    <div class="container">
        <div class="row text-center">
            <div class="d-flex flex-row w-100 justify-content-center align-items-center">
            <span class="d-none d-md-inline badge rounded-pill badge-notice">
                <?php _e('New', TEXT_DOMAIN); ?>
            </span>
                <span class="ms-2">
                    <a href="<?php the_sub_field('url'); ?>"><?php echo get_sub_field('text'); ?></a>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     class="bi bi-chevron-right ms-1" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                          d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"></path>
                </svg>
            </div>
        </div>
    </div>
</section>
