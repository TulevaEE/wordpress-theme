<?php

/**
 * Fetches funds from the onboarding service, cached so that a slow or
 * unreachable API cannot block page rendering on every request.
 * @param  string|null $fund_manager_name Optional fund manager to filter by
 * @return array|null                     Decoded funds, or null when the API could not be read
 */
function get_funds_from_api($fund_manager_name = null)
{
    $url = 'https://onboarding-service.tuleva.ee/v1/funds';
    if ($fund_manager_name) {
        $url .= '?fundManager.name=' . rawurlencode($fund_manager_name);
    }

    $cache_key = 'tuleva_funds_' . md5($url);
    $unavailable = 'unavailable';

    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }
    if ($cached === $unavailable) {
        return null;
    }

    $response = wp_remote_get($url, ['timeout' => 3]);
    if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
        set_transient($cache_key, $unavailable, MINUTE_IN_SECONDS);

        return null;
    }

    $funds = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($funds)) {
        set_transient($cache_key, $unavailable, MINUTE_IN_SECONDS);

        return null;
    }

    set_transient($cache_key, $funds, 10 * MINUTE_IN_SECONDS);

    return $funds;
}
