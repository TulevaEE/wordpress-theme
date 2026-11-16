<?php

function components_include_layout($components, string $layout): bool {
    if (!is_array($components)) {
        return false;
    }
    foreach ($components as $component) {
        if (($component['acf_fc_layout'] ?? '') === $layout) {
            return true;
        }
    }
    return false;
}

function current_page_has_component(string $layout): bool {
    if (!function_exists('get_field') || !is_singular()) {
        return false;
    }
    return components_include_layout(get_field('page_components', get_queried_object_id()), $layout);
}

/**
 * Pages whose own call to action the header's "Bring II pillar to Tuleva" button would compete
 * with: the rejoin, Future Me and 2-4-6 landing pages, and the front page while it leads with
 * the II pillar contribution hero.
 */
function page_has_its_own_call_to_action(): bool {
    return is_page_template(['page_second-pillar-rejoin.php', 'page_future-me.php', 'page_second-pillar-246.php'])
        || current_page_has_component('second_pillar_payment_rate_hero');
}
