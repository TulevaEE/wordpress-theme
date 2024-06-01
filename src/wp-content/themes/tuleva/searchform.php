<form role="search" method="get" class="search-form d-flex align-items-center" action="<?php echo home_url('/'); ?>">
    <label class="mb-0 mr-1">
        <span class="screen-reader-text"><?php echo _x('Search for:', ''); ?></span>
        <input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x('Search', ''); ?>…" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x('Search for:', 'label'); ?>" />
    </label>
    <button type="submit" class="search-submit btn btn-outline-primary">
        <span class="icon-search"><?php echo esc_html_x('Search', ''); ?></span>
    </button>
</form>
