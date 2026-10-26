<?php

/*
 * Get the number of people saving in Tuleva index funds.
 *
 * Source: onboarding-service, backed by the same analytics.mv_kpi_new figure as the
 * Metabase "kogujate arv". That view only moves once a month, so a day's cache never
 * leaves us more than a day behind when it does.
 *
 * Returns 0 only when there is nothing better to show, so callers can fall back to
 * the hand-entered members_count ACF field.
 */
function get_investor_count()
{
    return tuleva_count_from_api(
        'tuleva_investor_count',
        fn() => wp_remote_get('https://onboarding-service.tuleva.ee/v1/statistics/investor-count', ['timeout' => 1]),
        function ($response) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            // is_numeric, so that a malformed "70000abc" is rejected rather than cast to 70000.
            return isset($data['count']) && is_numeric($data['count']) ? (int) $data['count'] : 0;
        },
        // Mirrors the sanity bounds the endpoint already applies in SQL.
        50000,
        500000
    );
}

/*
 * Get the number of Tuleva members: the total the members endpoint gives a HEAD request.
 * Returns 0 only when the endpoint has never answered.
 */
function get_member_count()
{
    return tuleva_count_from_api(
        'tuleva_member_count',
        fn() => wp_remote_head('https://onboarding-service.tuleva.ee/v1/members', ['timeout' => 1]),
        function ($response) {
            $total = wp_remote_retrieve_header($response, 'x-total-count');
            return is_numeric($total) ? (int) $total : 0;
        },
        5000,
        100000
    );
}

/*
 * A count from onboarding-service, cached for a day under $name.
 *
 * The last count the API returned successfully is also kept in an option. A failed
 * refresh then keeps showing yesterday's real number instead of dropping back to a
 * hand-entered figure, which can be months out of date.
 */
function tuleva_count_from_api(string $name, callable $request, callable $read, int $min, int $max): int
{
    if (tuleva_is_local_development()) {
        return 0;
    }

    $cached = get_transient($name);
    if ($cached !== false) {
        $cached = (int) $cached;

        // A cached 0 is the failure sentinel, not a real count.
        return $cached > 0 ? $cached : tuleva_last_good_count($name);
    }

    // Timeout bounds the whole request, DNS lookup included.
    $response = $request();

    $count = 0;
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $count = $read($response);
    }

    if ($count < $min || $count > $max) {
        // Unreachable, malformed or implausible. Cache the failure briefly so an
        // outage costs one slow request per five minutes, not one per visitor.
        set_transient($name, 0, 5 * MINUTE_IN_SECONDS);

        $reason = is_wp_error($response)
            ? $response->get_error_message()
            : 'HTTP ' . wp_remote_retrieve_response_code($response) . ', count ' . $count;
        error_log('Tuleva count unavailable, using the last good value: name=' . $name . ', reason=' . $reason);

        return tuleva_last_good_count($name);
    }

    set_transient($name, $count, DAY_IN_SECONDS);
    update_option($name . '_last_good', $count, false);

    return $count;
}

/*
 * Same guard as the fund templates: never call the production API from local dev.
 */
function tuleva_is_local_development(): bool
{
    return isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost';
}

/*
 * The last count the API returned successfully, or 0 if it has never answered.
 */
function tuleva_last_good_count(string $name): int
{
    return (int) get_option($name . '_last_good', 0);
}

/*
 * Copy claims a figure as "over N", so N is rounded down to a whole step and stays below
 * the figure even when the figure is a round step. Null when there is nothing to claim,
 * and the page then leaves the claim out.
 */
function tuleva_format_over(int $value, int $step, string $language): ?string
{
    $over = intdiv($value - 1, $step) * $step;
    if ($over <= 0) {
        return null;
    }

    return number_format($over, 0, '', $language === 'en' ? ',' : "\u{00A0}");
}

function tuleva_format_count_over(int $count, string $language): ?string
{
    return tuleva_format_over($count, 1000, $language);
}

/*
 * Falls back to the hand-entered members_count ACF field, so local development without
 * the API still shows a number.
 */
function tuleva_shown_investor_count(): int
{
    return get_investor_count() ?: (int) get_field('members_count', 'option');
}

function tuleva_investor_count_over(string $language): ?string
{
    return tuleva_format_count_over(tuleva_shown_investor_count(), $language);
}

function tuleva_member_count_over(string $language): ?string
{
    return tuleva_format_count_over(get_member_count(), $language);
}

/*
 * One fund from the fund list the fund pages already read, or null while the list cannot
 * be read or does not carry the fund.
 */
function tuleva_fund_from_api(string $isin): ?array
{
    if (tuleva_is_local_development()) {
        return null;
    }

    foreach (get_funds_from_api() ?? [] as $fund) {
        if (($fund['isin'] ?? null) === $isin) {
            return $fund;
        }
    }

    return null;
}

function tuleva_fund_saver_count_over(string $isin, string $language): ?string
{
    return tuleva_format_count_over((int) (tuleva_fund_from_api($isin)['peopleCount'] ?? 0), $language);
}

/*
 * In whole tens of millions, as copy claims a fund's size: "over 600 million".
 */
function tuleva_fund_volume_millions_over(string $isin, string $language): ?string
{
    $millions = (int) ceil((tuleva_fund_from_api($isin)['volume'] ?? 0) / 1000000);

    return tuleva_format_over($millions, 10, $language);
}
