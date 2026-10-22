/**
 * II pillar rejoin calculator for the rejoin landing page. Splits what goes
 * into the II pillar each month into what leaves the net salary and the tax
 * win, the same way the rejoin page in the app does.
 */

var PAYMENT_RATE_PERCENT = 6;
var STATE_CONTRIBUTION_PERCENT = 4;
var INCOME_TAX_RATE = 0.22;
var MONTHLY_BASIC_EXEMPTION = 700;
var UNEMPLOYMENT_INSURANCE_RATE = 0.016;
var MONTHS_IN_TEN_YEARS = 120;
var MAX_GROSS_SALARY = 99999;
var FIGURE_BASELINE_Y = 470;
var FIGURE_PILLAR_TOP_Y = 190;
var FIGURE_BAR_CORNER_EDGE = 12;

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
 * @returns {{netSalaryCost: number, incomeTaxSaved: number, state: number, taxWin: number, intoPillar: number, tenYearTotal: number}}
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
        intoPillar: ownContribution + state,
        tenYearTotal: (ownContribution + state) * MONTHS_IN_TEN_YEARS
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
    var tenYears = document.getElementById('resTenYears');
    var taxWinInfo = document.getElementById('taxWinInfo');

    var locale = typeof LANGCODE !== 'undefined' && LANGCODE === 'en' ? 'en-GB' : 'et-EE';

    function eur(value) {
        return value.toLocaleString(locale) + ' €';
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

    function showTaxWinBreakdown(html) {
        var tooltip = window.bootstrap && window.bootstrap.Tooltip.getInstance(taxWinInfo);
        if (tooltip) {
            tooltip.setContent({ '.tooltip-inner': html });
        } else {
            taxWinInfo.setAttribute('title', html);
        }
    }

    var figure = document.getElementById('rejoinFigure');

    function inFigure(id) {
        return document.getElementById('rejoinFigure' + id);
    }

    function oneDecimal(value) {
        return String(Math.round(value * 10) / 10);
    }

    function stretchFigureBars(result) {
        var fullHeight = FIGURE_BASELINE_Y - FIGURE_PILLAR_TOP_Y;
        var takeHomeTop = FIGURE_BASELINE_Y - (fullHeight * result.netSalaryCost) / result.intoPillar;
        [inFigure('TakeHomeBar'), inFigure('PillarBaseBar')].forEach(function (bar) {
            bar.setAttribute('y', oneDecimal(takeHomeTop));
            bar.setAttribute('height', oneDecimal(FIGURE_BASELINE_Y - takeHomeTop));
        });
        inFigure('TaxWinBar').setAttribute('height', oneDecimal(takeHomeTop - FIGURE_PILLAR_TOP_Y));
        inFigure('TaxWinBarEdge').setAttribute('y', oneDecimal(takeHomeTop - FIGURE_BAR_CORNER_EDGE));
        inFigure('TakeHomeArrow').setAttribute('d', 'M340 51 C240 51 220 150 220 ' + oneDecimal(takeHomeTop - 16));
        inFigure('TakeHomeArrowHead').setAttribute(
            'd',
            'M208 ' + oneDecimal(takeHomeTop - 28) + ' L220 ' + oneDecimal(takeHomeTop - 14) + ' L232 ' + oneDecimal(takeHomeTop - 28)
        );
    }

    function showFigure(result) {
        inFigure('Contribution').textContent = eur(result.netSalaryCost + result.incomeTaxSaved);
        inFigure('IncomeTax').textContent = eur(result.incomeTaxSaved);
        inFigure('TakeHome').textContent = eur(result.netSalaryCost);
        inFigure('PillarBase').textContent = eur(result.netSalaryCost);
        inFigure('TaxWin').textContent = eur(result.taxWin);
        inFigure('TakeHomeTotal').textContent = eur(result.netSalaryCost);
        inFigure('PillarTotal').textContent = eur(result.intoPillar);
        if (result.intoPillar > 0) {
            stretchFigureBars(result);
        }
    }

    function update() {
        var result = computeRejoinContributions(read(salary), PAYMENT_RATE_PERCENT);
        netSalary.textContent = eur(result.netSalaryCost);
        taxWin.textContent = eur(result.taxWin);
        total.textContent = eur(result.intoPillar);
        tenYears.textContent = eur(result.tenYearTotal);
        showTaxWinBreakdown(describeTaxWin(result));
        if (figure) {
            showFigure(result);
        }
    }

    salary.addEventListener('input', update);
    window.addEventListener('pageshow', update);
    salary.addEventListener('focus', function () {
        setTimeout(function () {
            salary.select();
        });
    });
    salary.addEventListener('change', function () {
        if (parseEuros(salary.value) > MAX_GROSS_SALARY) {
            salary.value = MAX_GROSS_SALARY;
        }
    });
    update();
})();

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        computeRejoinContributions: computeRejoinContributions,
        MAX_GROSS_SALARY: MAX_GROSS_SALARY
    };
}
