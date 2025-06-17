<form role="search" method="get" class="search-form d-flex align-items-center gap-2" action="<?php echo home_url('/'); ?>">
    <label for="searchBlog" class="form-label visually-hidden"><?php _e('Search blog', TEXT_DOMAIN) ?></label>
    <input type="search" class="search-field form-control flex-grow-1" value="<?php echo get_search_query(); ?>" name="s" id="searchBlog" />
    <button type="submit" class="search-submit btn btn-outline-primary">
        <?php echo esc_html_x('Search', ''); ?>
    </button>
</form>
