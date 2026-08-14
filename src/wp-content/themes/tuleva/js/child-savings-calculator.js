/**
 * Child savings calculator for the child savings landing page.
 * Projects the future value of monthly contributions until the child turns 18
 * and the tax win from the child selling units within the yearly tax-free
 * income (EMTA 2026 assumptions), compared to a parent paying full income tax.
 */

var TAX_RATE = 0.22;
var CHILD_TAX_FREE_INCOME = 8400; // euros per year
var SALE_YEARS = 4; // sales spread over four years

/**
 * Future value of a monthly annuity. Works for negative rates;
 * only a zero rate needs the special case.
 *
 * @param {number} monthlyPayment - Contribution per month
 * @param {number} monthlyRate - Monthly rate as a decimal (annual / 12)
 * @param {number} months - Number of monthly contributions
 * @returns {number} - Future value
 */
function futureValue(monthlyPayment, monthlyRate, months) {
    if (Math.abs(monthlyRate) < 1e-10) {
        return monthlyPayment * months;
    }
    return monthlyPayment * ((Math.pow(1 + monthlyRate, months) - 1) / monthlyRate);
}

/**
 * Tax win of holding units in the child's name: a parent pays income tax on
 * the whole gain, the child spreads sales over SALE_YEARS years and uses the
 * yearly tax-free income for each.
 *
 * @param {number} gain - Total gain at age 18 (may be negative)
 * @returns {number} - Tax saved, never negative
 */
function childTaxWin(gain) {
    var taxableGain = Math.max(0, gain);
    var parentTax = TAX_RATE * taxableGain;
    var gainPerYear = taxableGain / SALE_YEARS;
    var childTax = SALE_YEARS * TAX_RATE * Math.max(0, gainPerYear - CHILD_TAX_FREE_INCOME);
    return Math.max(0, parentTax - childTax);
}

/**
 * Full projection for the calculator card.
 *
 * @param {Object} input
 * @param {number} input.age - Child's current age in years (0-18)
 * @param {number} input.monthly - Monthly contribution in euros
 * @param {number} input.annualRatePercent - Expected yearly return, e.g. 7
 * @returns {{months: number, paid: number, total: number, gain: number, win: number}}
 */
function computeChildSavings(input) {
    var months = (18 - input.age) * 12;
    var paid = input.monthly * months;
    var total = futureValue(input.monthly, input.annualRatePercent / 100 / 12, months);
    var gain = total - paid;
    return {
        months: months,
        paid: paid,
        total: total,
        gain: gain,
        win: childTaxWin(gain)
    };
}

(function () {
    if (typeof document === 'undefined') {
        return;
    }
    var age = document.getElementById('calcAge');
    if (!age) {
        return;
    }
    var sum = document.getElementById('calcSum');
    var rate = document.getElementById('calcRate');
    var resTax = document.getElementById('resTax');
    var resTotal = document.getElementById('resTotal');

    var locale = typeof LANGCODE !== 'undefined' && LANGCODE === 'en' ? 'en-GB' : 'et-EE';

    function eur(value) {
        return Math.round(value).toLocaleString(locale) + ' €';
    }

    function readAge() {
        // An empty field falls back to the placeholder default, like the pension calculator
        var raw = age.value === '' ? Number(age.placeholder) : Number(age.value);
        if (!Number.isFinite(raw)) {
            return null;
        }
        if (raw > 18) {
            age.value = 18;
        }
        return Math.min(18, Math.max(0, Math.floor(raw)));
    }

    function readMonthly() {
        var raw = sum.value === '' ? Number(sum.placeholder) : Number(sum.value);
        if (!Number.isFinite(raw)) {
            return null;
        }
        if (raw > 9999) {
            sum.value = 9999;
        }
        return Math.min(9999, Math.max(0, raw));
    }

    function update() {
        var ageValue = readAge();
        var monthly = readMonthly();
        if (ageValue === null || monthly === null) {
            return;
        }

        var result = computeChildSavings({
            age: ageValue,
            monthly: monthly,
            annualRatePercent: Number(rate.value)
        });
        // A dash instead of "0 €" — at 0% return the tax win is zero only
        // because there is no gain yet, not because the benefit is missing
        var roundedWin = Math.round(result.win / 10) * 10;
        resTax.textContent = roundedWin > 0 ? eur(roundedWin) : '–';
        resTotal.textContent = eur(result.total);
    }

    [age, sum, rate].forEach(function (el) {
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
        futureValue: futureValue,
        childTaxWin: childTaxWin,
        computeChildSavings: computeChildSavings,
        TAX_RATE: TAX_RATE,
        CHILD_TAX_FREE_INCOME: CHILD_TAX_FREE_INCOME,
        SALE_YEARS: SALE_YEARS
    };
}
