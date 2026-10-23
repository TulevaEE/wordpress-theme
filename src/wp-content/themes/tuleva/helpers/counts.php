<?php

/*
 * Get the number of people saving in Tuleva index funds.
 *
 * Source: onboarding-service, backed by the same analytics.mv_kpi_new figure as the
 * Metabase "kogujate arv". That view only moves once a month, so the value is cached
 * for a day: the count changes about monthly, and we are never more than a day behind
 * when it does.
 *
 * The last count the API returned successfully is also kept in an option. A failed
 * refresh then keeps showing yesterday's real number instead of dropping back to the
 * hand-entered members_count ACF field, which can be months out of date.
 *
 * Returns 0 only when there is nothing better to show, so callers can fall back to
 * the ACF field.
 */
function get_investor_count()
{
    // Same guard as the fund templates: never call the production API from local dev.
    if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
        return 0;
    }

    $cached = get_transient('tuleva_investor_count');
    if ($cached !== false) {
        $cached = (int) $cached;

        // A cached 0 is the failure sentinel, not a real count.
        return $cached > 0 ? $cached : get_last_good_investor_count();
    }

    // Timeout bounds the whole request, DNS lookup included.
    $response = wp_remote_get(
        'https://onboarding-service.tuleva.ee/v1/statistics/investor-count',
        ['timeout' => 1]
    );

    $count = 0;
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        // is_numeric, so that a malformed "70000abc" is rejected rather than cast to 70000.
        if (isset($data['count']) && is_numeric($data['count'])) {
            $count = (int) $data['count'];
        }
    }

    // Mirrors the sanity bounds the endpoint already applies in SQL.
    if ($count < 50000 || $count > 500000) {
        // Unreachable, malformed or implausible. Cache the failure briefly so an
        // outage costs one slow request per five minutes, not one per visitor.
        set_transient('tuleva_investor_count', 0, 5 * MINUTE_IN_SECONDS);

        $reason = is_wp_error($response)
            ? $response->get_error_message()
            : 'HTTP ' . wp_remote_retrieve_response_code($response) . ', count ' . $count;
        error_log('Tuleva investor count unavailable (' . $reason . '), using the last good value.');

        return get_last_good_investor_count();
    }

    set_transient('tuleva_investor_count', $count, DAY_IN_SECONDS);
    update_option('tuleva_investor_count_last_good', $count, false);

    return $count;
}

/*
 * The last count the API returned successfully, or 0 if it has never answered.
 */
function get_last_good_investor_count()
{
    return (int) get_option('tuleva_investor_count_last_good', 0);
}
