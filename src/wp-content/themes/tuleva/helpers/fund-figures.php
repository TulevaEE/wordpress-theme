<?php

/*
 * The management fee, the fund manager's units and the ongoing charges on the fund pages
 * (and the ongoing charges on the homepage calculator and the savings fund landing pages),
 * read from the onboarding service's fund list rather than typed into the templates. The
 * service takes the management fee from the rate in force in its fee table, the units from
 * each fund's unit register at the last month end, and the ongoing charges from the fund's
 * key information document in force. While the list cannot be read, the last figures it
 * returned stay on the pages. Plan: "TODO — Fund page figures from the database", in the
 * private tuleva repo under work/investeerimistegevus/docs/.
 */

const TULEVA_FUND_FIGURES_OPTION_PREFIX = 'tuleva_fund_figures_last_good_';

const TULEVA_FUND_ISINS = [
    'TUK75' => 'EE3600109435',
    'TUK00' => 'EE3600109443',
    'TUV100' => 'EE3600001707',
    'TKF100' => 'EE0000003283',
];

const TULEVA_NO_FUND_FIGURES = [
    'managementFeeRate' => null,
    'fundManagerUnits' => null,
    'fundManagerUnitsDate' => null,
    'ongoingChargesFigure' => null,
    'ongoingChargesPercentDecimals' => null,
];

/*
 * A figure the list does not carry for the fund keeps its last-good value rather than
 * disappearing; with no last-good value either, it is null and the page leaves the row out.
 * A last-good value stored before the list carried a figure has no key for it, and counts as
 * none.
 */
function tuleva_fund_figures(string $isin, ?array $funds, ?array $lastGood): array
{
    $previous = ($lastGood ?? []) + TULEVA_NO_FUND_FIGURES;
    $listed = tuleva_listed_fund($funds ?? [], $isin);
    if ($listed === null) {
        return $previous;
    }

    $unitsDate = $listed['fundManagerUnitsDate'] ?? null;
    $hasUnits = is_numeric($listed['fundManagerUnits'] ?? null) && tuleva_is_iso_date($unitsDate);
    // The decimals say how the key information document prints the figure, so the two are
    // only taken together. The list leaves the decimals out when no document is in force.
    $hasOngoingCharges = is_numeric($listed['ongoingChargesFigure'] ?? null);
    $listedDecimals = $listed['ongoingChargesPercentDecimals'] ?? null;

    return [
        'managementFeeRate' => is_numeric($listed['managementFeeRate'] ?? null)
            ? (float) $listed['managementFeeRate']
            : $previous['managementFeeRate'],
        'fundManagerUnits' => $hasUnits ? (float) $listed['fundManagerUnits'] : $previous['fundManagerUnits'],
        'fundManagerUnitsDate' => $hasUnits ? $unitsDate : $previous['fundManagerUnitsDate'],
        'ongoingChargesFigure' => $hasOngoingCharges
            ? (float) $listed['ongoingChargesFigure']
            : $previous['ongoingChargesFigure'],
        'ongoingChargesPercentDecimals' => $hasOngoingCharges
            ? (is_int($listedDecimals) && $listedDecimals >= 0 && $listedDecimals <= 4 ? $listedDecimals : null)
            : $previous['ongoingChargesPercentDecimals'],
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

/*
 * A fraction as the percentage a page prints: rounded half up to $decimals decimals, or,
 * without them, to at most four, printed with at least two. Every fee the pages print, and
 * every figure worked out from a printed fee, comes from here, so nothing on a page can
 * disagree with the percentage beside it. The rounding is done on whole hundred-millionths
 * of a percent, because 0.0028 * 100 is 0.27999… as a float. The result is the whole number
 * of ten-thousandths of a percent, and the decimals to print it with.
 *
 * @return array{tenThousandths: int, decimals: int}
 */
function tuleva_printed_percent(float $fraction, ?int $decimals): array
{
    $roundTo = $decimals ?? 4;
    $step = 10 ** (8 - $roundTo);
    $hundredMillionths = (int) round($fraction * 10 ** 10);
    $tenThousandths = intdiv($hundredMillionths + intdiv($step, 2), $step) * 10 ** (4 - $roundTo);
    if ($decimals === null) {
        $decimals = max(2, strlen(rtrim(str_pad((string) ($tenThousandths % 10000), 4, '0', STR_PAD_LEFT), '0')));
    }

    return ['tenThousandths' => $tenThousandths, 'decimals' => $decimals];
}

function tuleva_format_percent(array $printed, string $language): string
{
    $whole = intdiv($printed['tenThousandths'], 10000);
    $fraction = substr(str_pad((string) ($printed['tenThousandths'] % 10000), 4, '0', STR_PAD_LEFT), 0, $printed['decimals']);

    return $whole . ($fraction === '' ? '' : tuleva_decimal_separator($language) . $fraction) . '%';
}

/*
 * What a printed percentage comes to on an amount of euros, in whole euros rounded half up.
 */
function tuleva_euros_at_percent(array $printed, int $euros): int
{
    return intdiv($euros * $printed['tenThousandths'] + 500000, 1000000);
}

function tuleva_format_management_fee(float $rate, string $language): string
{
    return tuleva_format_percent(tuleva_printed_percent($rate, null), $language);
}

/*
 * The ongoing charges as the fund's key information document prints them: with as many
 * decimals as it does. Without the decimals, the figure is written like the management fee.
 */
function tuleva_format_ongoing_charges(float $figure, ?int $decimals, string $language): string
{
    return tuleva_format_percent(tuleva_printed_percent($figure, $decimals), $language);
}

/*
 * Every fund's rules show a fractional unit to three decimals, rounded half up, so the
 * page does the same with the register's figure rather than printing all its decimals.
 */
function tuleva_format_units(float $units, string $language): string
{
    $separator = tuleva_decimal_separator($language);

    return rtrim(rtrim(number_format($units, 3, $separator, ' '), '0'), $separator);
}

function tuleva_format_figures_date(string $isoDate): string
{
    return DateTime::createFromFormat('!Y-m-d', $isoDate)->format('d.m.Y');
}

/*
 * The ongoing charges of each fund, as [figure, decimals] or null while unknown, each
 * printed with its own key information document's decimals: one value when every fund's
 * prints the same, otherwise the lowest and the highest printed value. A value printed with
 * different decimals (0,30% and 0,3%) is one value, written in its shorter form. Null while
 * any fund's is unknown: the sentence is about all of them.
 */
function tuleva_ongoing_charges_range(array $charges, string $language): ?array
{
    if ($charges === [] || in_array(null, $charges, true)) {
        return null;
    }

    $printed = array_map(fn($charge) => tuleva_printed_percent($charge['figure'], $charge['decimals']), $charges);
    usort($printed, fn($a, $b) => [$a['tenThousandths'], $a['decimals']] <=> [$b['tenThousandths'], $b['decimals']]);
    $byValue = [];
    foreach ($printed as $percent) {
        $byValue[$percent['tenThousandths']] ??= $percent;
    }
    $values = array_values($byValue);

    return count($values) === 1
        ? [tuleva_format_percent($values[0], $language)]
        : [tuleva_format_percent($values[0], $language), tuleva_format_percent($values[count($values) - 1], $language)];
}

/*
 * A fund's figures from the fund list, or the last-good ones while the list cannot be read
 * or does not carry them. Each fund keeps its own last-good option, so two pages rendering
 * at once cannot overwrite each other's. Only a read that lists the fund is remembered: a
 * failed one must not overwrite what another request has just stored.
 */
function tuleva_current_fund_figures(string $isin): array
{
    $option = TULEVA_FUND_FIGURES_OPTION_PREFIX . $isin;
    $stored = get_option($option);
    $funds = get_funds_from_api('Tuleva');
    $figures = tuleva_fund_figures($isin, $funds, is_array($stored) ? $stored : null);
    // The decimals are left out of the list on purpose when no key information document is in force.
    $required = array_diff_key($figures, ['ongoingChargesPercentDecimals' => null]);
    if (is_array($funds) && in_array(null, $required, true)) {
        error_log('Tuleva fund list has no management fee, fund manager units or ongoing charges for ' . $isin . ' and none is remembered, so the pages leave that figure out.');
    }
    $listed = is_array($funds) && tuleva_listed_fund($funds, $isin) !== null;
    if ($listed && $figures !== TULEVA_NO_FUND_FIGURES && $figures !== $stored) {
        update_option($option, $figures, false);
    }

    return $figures;
}

function tuleva_figures_language(): string
{
    return (string) apply_filters('wpml_current_language', null);
}

/*
 * A fund's ongoing charges as a fraction, with the decimals its key information document
 * prints the percentage with (null when the list does not say), or null while unknown.
 *
 * @return array{figure: float, decimals: ?int}|null
 */
function tuleva_fund_ongoing_charges_figure(string $isin): ?array
{
    $figures = tuleva_current_fund_figures($isin);

    return $figures['ongoingChargesFigure'] === null
        ? null
        : ['figure' => $figures['ongoingChargesFigure'], 'decimals' => $figures['ongoingChargesPercentDecimals']];
}

/*
 * A fund's ongoing charges as the page prints them (tuleva_printed_percent()), or null
 * while unknown.
 */
function tuleva_fund_printed_ongoing_charges(string $isin): ?array
{
    $charges = tuleva_fund_ongoing_charges_figure($isin);

    return $charges === null ? null : tuleva_printed_percent($charges['figure'], $charges['decimals']);
}

/*
 * A fund's ongoing charges written for the page's language, or null while unknown.
 */
function tuleva_fund_ongoing_charges(string $isin): ?string
{
    $printed = tuleva_fund_printed_ongoing_charges($isin);

    return $printed === null ? null : tuleva_format_percent($printed, tuleva_figures_language());
}

/*
 * The ongoing charges of all Tuleva funds for a sentence about them all: one value when
 * they are the same, the lowest and the highest when not, null while any is unknown.
 */
function tuleva_all_funds_ongoing_charges(): ?array
{
    return tuleva_ongoing_charges_range(
        array_map('tuleva_fund_ongoing_charges_figure', array_values(TULEVA_FUND_ISINS)),
        tuleva_figures_language()
    );
}

/*
 * The figures a fund page shows, formatted for its language: each is null while there
 * is nothing to show, and the page then leaves the row out.
 */
function tuleva_fund_page_figures(string $isin): array
{
    $figures = tuleva_current_fund_figures($isin);
    $language = tuleva_figures_language();

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
        'ongoing_charges' => $figures['ongoingChargesFigure'] === null
            ? null
            : tuleva_format_ongoing_charges(
                $figures['ongoingChargesFigure'],
                $figures['ongoingChargesPercentDecimals'],
                $language
            ),
    ];
}
