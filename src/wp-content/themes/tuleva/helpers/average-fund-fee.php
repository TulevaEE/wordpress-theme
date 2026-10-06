<?php

/*
 * The homepage calculator's "Estonian average II pillar fund": the volume-weighted
 * ongoing charges of every active II pillar fund, Tuleva's included, recalculated once
 * a month from the onboarding service's fund list. The method is the one the figure was
 * worked out with by hand before: SUMPRODUCT(fee, volume) / SUM(volume), rounded to the
 * hundredth of a percent the calculator shows.
 */

const TULEVA_AVERAGE_FUND_FEE_OPTION = 'tuleva_average_fund_fee';

/*
 * Null when there is nothing to average. A fund the list gives no fee or no volume for is
 * left out and named in the log: a fund too new to have statistics, or one Pensionikeskus
 * no longer lists, would otherwise stop the average from ever updating again.
 */
function tuleva_weighted_average_fund_fee(array $funds): ?float
{
    $totalVolume = 0.0;
    $weightedFees = 0.0;
    foreach ($funds as $fund) {
        if (($fund['status'] ?? null) !== 'ACTIVE' || ($fund['pillar'] ?? null) !== 2) {
            continue;
        }
        $fee = $fund['ongoingChargesFigure'] ?? null;
        $volume = $fund['volume'] ?? null;
        if (!is_numeric($fee) || !is_numeric($volume) || $volume <= 0) {
            error_log('Average II pillar fund fee leaves out ' . ($fund['isin'] ?? 'a fund') . ': no fee or volume in the fund list.');
            continue;
        }
        $totalVolume += (float) $volume;
        $weightedFees += (float) $fee * (float) $volume;
    }

    return $totalVolume > 0 ? round($weightedFees / $totalVolume, 4) : null;
}

/*
 * The average stored for $month, or a fresh one from $fetchFunds when the month has
 * turned. While the funds cannot be read, the previous month's average stays in use.
 */
function tuleva_monthly_average_fund_fee(callable $fetchFunds, string $month): ?float
{
    $stored = get_option(TULEVA_AVERAGE_FUND_FEE_OPTION);
    $storedFee = is_array($stored) && is_numeric($stored['fee'] ?? null) ? (float) $stored['fee'] : null;
    if ($storedFee !== null && ($stored['month'] ?? null) === $month) {
        return $storedFee;
    }

    $funds = $fetchFunds();
    $fee = is_array($funds) ? tuleva_weighted_average_fund_fee($funds) : null;
    if ($fee === null) {
        error_log('Average II pillar fund fee not recalculated for ' . $month . ', keeping the previous one.');
        return $storedFee;
    }

    update_option(TULEVA_AVERAGE_FUND_FEE_OPTION, ['month' => $month, 'fee' => $fee], true);

    return $fee;
}

function tuleva_format_fee_percent(float $fee, string $language): string
{
    return number_format($fee * 100, 2, $language === 'et' ? ',' : '.', '') . '%';
}

/*
 * The calculator reads this twice on one page (the script variable and the first
 * render of the fee), so it is worked out once per request.
 */
function tuleva_calculator_average_fund_fee(): ?float
{
    static $fee = null;
    static $resolved = false;
    if (!$resolved) {
        $fee = tuleva_monthly_average_fund_fee('get_funds_from_api', wp_date('Y-m'));
        $resolved = true;
    }

    return $fee;
}
