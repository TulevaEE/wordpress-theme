/**
 * II pillar rejoin calculator for the rejoin landing page. Splits what goes
 * into the II pillar each month into what leaves the net salary and the tax
 * win, the same way the rejoin page in the app does.
 */

var DEFAULT_PAYMENT_RATE_PERCENT = 6;
var HIGHEST_PAYMENT_RATE_PERCENT = 6;
var STATE_CONTRIBUTION_PERCENT = 4;
var INCOME_TAX_RATE = 0.22;
var MONTHLY_BASIC_EXEMPTION = 700;
var UNEMPLOYMENT_INSURANCE_RATE = 0.016;
var MAX_GROSS_SALARY = 99999;
var AUTOFOCUS_MEDIA = '(min-width: 992px) and (pointer: fine)';
var FIGURE_FOLLOWS_SALARY_FROM = 1000;
var FIGURE_BASELINE_Y = 470;
var FIGURE_PILLAR_TOP_Y = 190;
var FIGURE_BAR_CORNER_EDGE = 12;
var FIGURE_AMOUNT_BASELINE_SHIFT = 9;
var MONTHS_IN_YEAR = 12;
// The I pillar from 1 April 2026: the value of one pension unit a month, the minimum wage,
// and the average salary that earns one unit, from the 2026 average social tax for pensions
var PENSION_UNIT_VALUE = 10.477;
var MINIMUM_WAGE = 946;
var AVERAGE_INSURED_SALARY = 5092.7 / 0.2 / MONTHS_IN_YEAR;
// A II pillar saver earns 16% instead of 20% of social tax and 0.8 instead of 1 of the equal half
var SHARE_OF_PENSION_UNIT_TO_SECOND_PILLAR = 0.2;

function incomeTaxSaved(grossSalary, ownContribution) {
    var taxableBeforeContribution =
        grossSalary * (1 - UNEMPLOYMENT_INSURANCE_RATE) - MONTHLY_BASIC_EXEMPTION;
    return Math.min(ownContribution, Math.max(0, taxableBeforeContribution)) * INCOME_TAX_RATE;
}

/**
 * Each part is rounded to whole euros before anything is added up, so the
 * figures on the page always sum exactly.
 *
 * @param {number} grossSalary - Gross monthly salary in euros
 * @param {number} paymentRatePercent - Own contribution, e.g. 6
 * @returns {{netSalaryCost: number, incomeTaxSaved: number, state: number, taxWin: number, intoPillar: number}}
 */
function computeRejoinContributions(grossSalary, paymentRatePercent) {
    var exactOwnContribution = (grossSalary * paymentRatePercent) / 100;
    var ownContribution = Math.round(exactOwnContribution);
    var state = Math.round((grossSalary * STATE_CONTRIBUTION_PERCENT) / 100);
    var saved = Math.round(incomeTaxSaved(grossSalary, exactOwnContribution));
    return {
        netSalaryCost: ownContribution - saved,
        incomeTaxSaved: saved,
        state: state,
        taxWin: saved + state,
        intoPillar: ownContribution + state
    };
}

/**
 * What one year in the II pillar is worth in each pillar, unrounded so that each figure on the
 * page is rounded only once. The II pillar gets a year of the tax win. The I pillar would have
 * paid for the social tax part with pension units: half of a unit follows the salary against
 * the average, the other half is the same for everyone earning at least the minimum wage.
 *
 * @param {number} grossSalary - Gross monthly salary in euros
 * @param {number} paymentRatePercent - Own contribution, e.g. 6
 * @returns {{firstPillarUnitsLost: number, firstPillarMonthly: number, socialTaxYearly: number, secondPillarYearly: number}}
 */
function computeFirstPillarComparison(grossSalary, paymentRatePercent) {
    var units =
        0.5 * Math.min(1, grossSalary / MINIMUM_WAGE) + 0.5 * (grossSalary / AVERAGE_INSURED_SALARY);
    var unitsLost = SHARE_OF_PENSION_UNIT_TO_SECOND_PILLAR * units;
    var state = (grossSalary * STATE_CONTRIBUTION_PERCENT) / 100;
    var ownContribution = (grossSalary * paymentRatePercent) / 100;
    return {
        firstPillarUnitsLost: unitsLost,
        firstPillarMonthly: unitsLost * PENSION_UNIT_VALUE,
        socialTaxYearly: MONTHS_IN_YEAR * state,
        secondPillarYearly: MONTHS_IN_YEAR * (state + incomeTaxSaved(grossSalary, ownContribution))
    };
}

(function () {
    if (typeof document === 'undefined') {
        return;
    }
    var salary = document.getElementById('calcSalary');
    if (!salary) {
        return;
    }
    var netSalary = document.getElementById('resNetSalary');
    var taxWin = document.getElementById('resTaxWin');
    var total = document.getElementById('resTotal');
    var taxWinInfo = document.getElementById('taxWinInfo');
    var rates = document.querySelectorAll('input[name="calcRate"]');

    function selectedRate() {
        var checked = document.querySelector('input[name="calcRate"]:checked');
        return checked ? Number(checked.value) : DEFAULT_PAYMENT_RATE_PERCENT;
    }

    var locale = typeof LANGCODE !== 'undefined' && LANGCODE === 'en' ? 'en-GB' : 'et-EE';

    function eur(value) {
        return value.toLocaleString(locale) + ' €';
    }

    function fixed(value, digits) {
        return value.toLocaleString(locale, { minimumFractionDigits: digits, maximumFractionDigits: digits });
    }


    function parseEuros(text) {
        return Number(text.replace(/\s/g, ''));
    }

    function read(field) {
        var raw = parseEuros(field.value);
        if (!Number.isFinite(raw) || raw < 0) {
            return 0;
        }
        return Math.min(raw, MAX_GROSS_SALARY);
    }

    function breakdownRow(label, amount) {
        return '<span>' + label + '</span><span class="text-end">' + eur(amount) + '</span>';
    }

    function describeTaxWin(result) {
        return (
            '<div class="rejoin-tax-win-breakdown">' +
            breakdownRow(taxWinInfo.dataset.socialTaxLabel, result.state) +
            breakdownRow(taxWinInfo.dataset.incomeTaxLabel, result.incomeTaxSaved) +
            '</div>'
        );
    }

    function showTaxWinBreakdown(trigger, result) {
        var html = describeTaxWin(result);
        var tooltip = window.bootstrap && window.bootstrap.Tooltip.getInstance(trigger);
        if (tooltip) {
            tooltip.setContent({ '.tooltip-inner': html });
        } else {
            trigger.setAttribute('title', html);
        }
    }

    var figure = document.getElementById('rejoinFigure');
    var figureExampleSalary = parseEuros(salary.defaultValue);

    function inFigure(id) {
        return document.getElementById('rejoinFigure' + id);
    }

    function oneDecimal(value) {
        return String(Math.round(value * 10) / 10);
    }

    function barHeight(amount, intoPillarAtHighestRate) {
        return ((FIGURE_BASELINE_Y - FIGURE_PILLAR_TOP_Y) * amount) / intoPillarAtHighestRate;
    }

    function placeBar(bar, bottom, height) {
        bar.setAttribute('y', oneDecimal(bottom - height));
        bar.setAttribute('height', oneDecimal(height));
    }

    function placeBarWithSquareBottom(bar, edge, bottom, height) {
        placeBar(bar, bottom, height);
        placeBar(edge, bottom, FIGURE_BAR_CORNER_EDGE);
    }

    function stretchFigureBars(result, intoPillarAtHighestRate) {
        var takeHomeHeight = barHeight(result.netSalaryCost, intoPillarAtHighestRate);
        var takeHomeTop = FIGURE_BASELINE_Y - takeHomeHeight;
        var taxWinHeight = barHeight(result.taxWin, intoPillarAtHighestRate);
        var taxWinLabel = inFigure('TaxWinLabel');
        placeBarWithSquareBottom(inFigure('TakeHomeBar'), inFigure('TakeHomeBarEdge'), FIGURE_BASELINE_Y, takeHomeHeight);
        placeBar(inFigure('PillarBaseBar'), FIGURE_BASELINE_Y, takeHomeHeight);
        placeBarWithSquareBottom(inFigure('TaxWinBar'), inFigure('TaxWinBarEdge'), takeHomeTop, taxWinHeight);
        taxWinLabel.setAttribute('transform', 'translate(0 ' + oneDecimal(takeHomeTop - taxWinHeight / 2) + ')');
        // The take-home amount sits in the middle of its bar, which is shorter at a lower contribution
        var takeHomeAmountY = oneDecimal(takeHomeTop + takeHomeHeight / 2 + FIGURE_AMOUNT_BASELINE_SHIFT);
        inFigure('TakeHomeLabel').setAttribute('y', takeHomeAmountY);
        inFigure('PillarBaseLabel').setAttribute('y', takeHomeAmountY);
        inFigure('TakeHomeArrow').setAttribute('d', 'M340 51 C240 51 220 150 220 ' + oneDecimal(takeHomeTop - 16));
        inFigure('TakeHomeArrowHead').setAttribute(
            'd',
            'M208 ' + oneDecimal(takeHomeTop - 28) + ' L220 ' + oneDecimal(takeHomeTop - 14) + ' L232 ' + oneDecimal(takeHomeTop - 28)
        );
    }

    function showFigure(grossSalary, rate) {
        var figureSalary = grossSalary < FIGURE_FOLLOWS_SALARY_FROM ? figureExampleSalary : grossSalary;
        var result = computeRejoinContributions(figureSalary, rate);
        var intoPillarAtHighestRate = computeRejoinContributions(figureSalary, HIGHEST_PAYMENT_RATE_PERCENT).intoPillar;
        inFigure('Salary').textContent = eur(figureSalary);
        inFigure('Rate').textContent = rate + '%';
        inFigure('Contribution').textContent = eur(result.netSalaryCost + result.incomeTaxSaved);
        inFigure('IncomeTax').textContent = eur(result.incomeTaxSaved);
        inFigure('TakeHome').textContent = eur(result.netSalaryCost);
        inFigure('PillarBase').textContent = eur(result.netSalaryCost);
        inFigure('TaxWin').textContent = eur(result.taxWin);
        inFigure('TakeHomeTotal').textContent = eur(result.netSalaryCost);
        inFigure('PillarTotal').textContent = eur(result.intoPillar);
        stretchFigureBars(result, intoPillarAtHighestRate);
        showTaxWinBreakdown(inFigure('TaxWinInfo'), result);
    }

    function showFirstPillarComparison(grossSalary, rate) {
        var figureSalary = grossSalary < FIGURE_FOLLOWS_SALARY_FROM ? figureExampleSalary : grossSalary;
        var comparison = computeFirstPillarComparison(figureSalary, rate);
        var values = {
            salary: eur(figureSalary),
            rate: rate + '%',
            firstPillarMonthly: eur(Math.round(comparison.firstPillarMonthly)),
            secondPillarYearly: eur(Math.round(comparison.secondPillarYearly)),
            socialTaxYearly: eur(Math.round(comparison.socialTaxYearly)),
            incomeTaxYearly: eur(Math.round(comparison.secondPillarYearly) - Math.round(comparison.socialTaxYearly)),
            firstPillarUnitsLost: fixed(comparison.firstPillarUnitsLost, 3),
            firstPillarMonthlyExact: fixed(comparison.firstPillarMonthly, 2) + ' €',
            pensionUnitValue: eur(PENSION_UNIT_VALUE)
        };
        document.querySelectorAll('[data-pillar-value]').forEach(function (element) {
            element.textContent = values[element.dataset.pillarValue];
        });
    }

    function update() {
        var grossSalary = read(salary);
        var rate = selectedRate();
        var result = computeRejoinContributions(grossSalary, rate);
        netSalary.textContent = eur(result.netSalaryCost);
        taxWin.textContent = eur(result.taxWin);
        total.textContent = eur(result.intoPillar);
        showTaxWinBreakdown(taxWinInfo, result);
        if (figure) {
            showFigure(grossSalary, rate);
        }
        showFirstPillarComparison(grossSalary, rate);
    }

    salary.addEventListener('input', update);
    rates.forEach(function (rate) {
        rate.addEventListener('change', update);
    });
    window.addEventListener('pageshow', update);
    var replaceOnFirstKey = false;

    salary.addEventListener('focus', function () {
        if (replaceOnFirstKey) {
            return;
        }
        setTimeout(function () {
            salary.select();
        });
    });
    // After the arrival focus the caret blinks at the end of the example; the first digit
    // typed still replaces the example, while deleting or clicking edits it as usual
    salary.addEventListener('beforeinput', function (event) {
        if (replaceOnFirstKey && event.inputType.indexOf('insert') === 0) {
            salary.value = '';
        }
        replaceOnFirstKey = false;
    });
    ['mousedown', 'blur'].forEach(function (type) {
        salary.addEventListener(type, function () {
            replaceOnFirstKey = false;
        });
    });
    salary.addEventListener('change', function () {
        if (parseEuros(salary.value) > MAX_GROSS_SALARY) {
            salary.value = MAX_GROSS_SALARY;
        }
    });
    update();

    // Testers didn't see that the salary can be changed. On a desktop the field takes the
    // focus on arrival with a blinking caret, as a search box does. Not on touch screens,
    // where it would open the keyboard over the page, and not when the visitor came for a
    // section further down
    if (window.matchMedia && window.matchMedia(AUTOFOCUS_MEDIA).matches && !window.location.hash) {
        replaceOnFirstKey = true;
        salary.focus({ preventScroll: true });
        salary.setSelectionRange(salary.value.length, salary.value.length);
    }
})();

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        computeRejoinContributions: computeRejoinContributions,
        computeFirstPillarComparison: computeFirstPillarComparison,
        MAX_GROSS_SALARY: MAX_GROSS_SALARY
    };
}
