/**
 * Company savings calculator for the company savings landing page.
 * Projects what a company's idle cash plus a monthly contribution is worth
 * after PROJECTION_YEARS years in the Additional Investment Fund.
 */

var PROJECTION_YEARS = 10;
var PROJECTION_MONTHS = PROJECTION_YEARS * 12;

/**
 * Monthly rate derived from the effective annual rate, not annual / 12.
 * Compounding this over twelve months gives back exactly the annual rate,
 * which is what the slider label promises. Works for negative rates too.
 *
 * @param {number} annualRatePercent - Expected yearly return, e.g. 7
 * @returns {number} - Monthly rate as a decimal
 */
function monthlyRate(annualRatePercent) {
    return Math.pow(1 + annualRatePercent / 100, 1 / 12) - 1;
}

/**
 * Future value of an initial lump sum plus a monthly contribution paid at the
 * end of each month. Only a zero rate needs the special case.
 *
 * @param {number} initial - Lump sum invested today
 * @param {number} monthly - Contribution per month
 * @param {number} rate - Monthly rate as a decimal
 * @param {number} months - Number of monthly contributions
 * @returns {number} - Future value
 */
function futureValue(initial, monthly, rate, months) {
    if (Math.abs(rate) < 1e-10) {
        return initial + monthly * months;
    }
    var growth = Math.pow(1 + rate, months);
    return initial * growth + monthly * ((growth - 1) / rate);
}

/**
 * Full projection for the calculator card.
 *
 * @param {Object} input
 * @param {number} input.initial - Company's idle cash in euros
 * @param {number} input.monthly - Monthly contribution in euros
 * @param {number} input.annualRatePercent - Expected yearly return, e.g. 7
 * @returns {{months: number, paid: number, total: number, gain: number}}
 */
function computeCompanySavings(input) {
    var paid = input.initial + input.monthly * PROJECTION_MONTHS;
    var total = futureValue(
        input.initial,
        input.monthly,
        monthlyRate(input.annualRatePercent),
        PROJECTION_MONTHS
    );
    return {
        months: PROJECTION_MONTHS,
        paid: paid,
        total: total,
        gain: total - paid
    };
}

(function () {
    if (typeof document === 'undefined') {
        return;
    }
    var amount = document.getElementById('calcAmount');
    if (!amount) {
        return;
    }
    var monthly = document.getElementById('calcMonthly');
    var rate = document.getElementById('calcRate');
    var resTotal = document.getElementById('resTotal');

    var locale = typeof LANGCODE !== 'undefined' && LANGCODE === 'en' ? 'en-GB' : 'et-EE';

    function eur(value) {
        return Math.round(value).toLocaleString(locale) + ' €';
    }

    // An empty or nonsensical field reads as zero, and the field's own max caps it
    function read(field) {
        var raw = Number(field.value);
        if (!Number.isFinite(raw) || raw < 0) {
            return 0;
        }
        var max = Number(field.max);
        return Number.isFinite(max) ? Math.min(raw, max) : raw;
    }

    function update() {
        var result = computeCompanySavings({
            initial: read(amount),
            monthly: read(monthly),
            annualRatePercent: Number(rate.value)
        });
        resTotal.textContent = eur(result.total);
    }

    [amount, monthly, rate].forEach(function (el) {
        el.addEventListener('input', update);
    });
    var historicRate = document.querySelector('.historic-return-rate');
    if (historicRate) {
        historicRate.addEventListener('click', function () {
            rate.value = 7;
            rate.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }
    update();
})();

// Export for testing (CommonJS)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        monthlyRate: monthlyRate,
        futureValue: futureValue,
        computeCompanySavings: computeCompanySavings,
        PROJECTION_YEARS: PROJECTION_YEARS,
        PROJECTION_MONTHS: PROJECTION_MONTHS
    };
}
