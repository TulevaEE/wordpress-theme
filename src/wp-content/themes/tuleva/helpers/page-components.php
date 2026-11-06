<?php

function page_uses_component($post_id, $layout) {
    $layouts = get_post_meta($post_id, 'page_components', true);

    return is_array($layouts) && in_array($layout, $layouts, true);
}

function current_page_uses_component($layout) {
    return is_singular() && page_uses_component(current_page_components_source(), $layout);
}

function current_page_components_source() {
    return acf_get_valid_post_id(get_queried_object_id());
}
