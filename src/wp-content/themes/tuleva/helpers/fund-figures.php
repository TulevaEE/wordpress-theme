<?php

/*
 * The management fee and the fund manager's units on the fund pages, read from the
 * onboarding service's fund list rather than typed into the templates. The service takes
 * the management fee from the rate in force in its fee table and the units from each
 * fund's unit register at the last month end. While the list cannot be read, the last
 * figures it returned stay on the pages. Plan: "TODO — Fund page figures from the
 * database", in the private tuleva repo under work/investeerimistegevus/docs/.
 */

const TULEVA_FUND_FIGURES_OPTION_PREFIX = 'tuleva_fund_figures_last_good_';

const TULEVA_NO_FUND_FIGURES = [
    'managementFeeRate' => null,
    'fundManagerUnits' => null,
    'fundManagerUnitsDate' => null,
];

/*
 * A figure the list does not carry for the fund keeps its last-good value rather than
 * disappearing; with no last-good value either, it is null and the page leaves the row out.
 */
function tuleva_fund_figures(string $isin, ?array $funds, ?array $lastGood): array
{
    $previous = $lastGood ?? TULEVA_NO_FUND_FIGURES;
    $listed = tuleva_listed_fund($funds ?? [], $isin);
    if ($listed === null) {
        return $previous;
    }

    $unitsDate = $listed['fundManagerUnitsDate'] ?? null;
    $hasUnits = is_numeric($listed['fundManagerUnits'] ?? null) && tuleva_is_iso_date($unitsDate);

    return [
        'managementFeeRate' => is_numeric($listed['managementFeeRate'] ?? null)
            ? (float) $listed['managementFeeRate']
            : $previous['managementFeeRate'],
        'fundManagerUnits' => $hasUnits ? (float) $listed['fundManagerUnits'] : $previous['fundManagerUnits'],
        'fundManagerUnitsDate' => $hasUnits ? $unitsDate : $previous['fundManagerUnitsDate'],
    ];
}

function tuleva_listed_fund(array $funds, string $isin): ?array
{
    foreach ($funds as $fund) {
        if (($fund['isin'] ?? null) === $isin) {
            return $fund;
        }
    }

    return null;
}

function tuleva_is_iso_date($value): bool
{
    if (!is_string($value) || preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $parts) !== 1) {
        return false;
    }

    return checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
}

/*
 * Estonian unless the page is English: Estonian is the site's default language.
 */
function tuleva_decimal_separator(string $language): string
{
    return $language === 'en' ? '.' : ',';
}

function tuleva_format_management_fee(float $rate, string $language): string
{
    [$whole, $decimals] = explode('.', rtrim(number_format($rate * 100, 4, '.', ''), '0'));

    return $whole . tuleva_decimal_separator($language) . str_pad($decimals, 2, '0') . '%';
}

function tuleva_format_units(float $units, string $language): string
{
    $separator = tuleva_decimal_separator($language);

    return rtrim(rtrim(number_format($units, 5, $separator, ' '), '0'), $separator);
}

function tuleva_format_figures_date(string $isoDate): string
{
    return DateTime::createFromFormat('!Y-m-d', $isoDate)->format('d.m.Y');
}

/*
 * The figures a fund page shows, formatted for its language: each is null while there
 * is nothing to show, and the page then leaves the row out. Each fund keeps its own
 * last-good option, so two fund pages rendering at once cannot overwrite each other's.
 */
function tuleva_fund_page_figures(string $isin): array
{
    $option = TULEVA_FUND_FIGURES_OPTION_PREFIX . $isin;
    $stored = get_option($option);
    $funds = get_funds_from_api('Tuleva');
    $figures = tuleva_fund_figures($isin, $funds, is_array($stored) ? $stored : null);
    if (is_array($funds) && in_array(null, $figures, true)) {
        error_log('Tuleva fund list has no management fee or fund manager units for ' . $isin . ' and none is remembered, so the page leaves that row out.');
    }
    if ($figures !== TULEVA_NO_FUND_FIGURES && $figures !== $stored) {
        update_option($option, $figures, false);
    }

    $language = (string) apply_filters('wpml_current_language', null);

    return [
        'management_fee' => $figures['managementFeeRate'] === null
            ? null
            : tuleva_format_management_fee($figures['managementFeeRate'], $language),
        'manager_units' => $figures['fundManagerUnits'] === null
            ? null
            : tuleva_format_units($figures['fundManagerUnits'], $language),
        'manager_units_date' => $figures['fundManagerUnitsDate'] === null
            ? null
            : tuleva_format_figures_date($figures['fundManagerUnitsDate']),
    ];
}
