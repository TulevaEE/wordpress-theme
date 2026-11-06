/**
 * II pillar 2-4-6 hero calculator. Shows, for the payment rate picked, the net salary,
 * the monthly contribution and the yearly income tax win. People compare the rates by
 * clicking back and forth, so only the numbers change and they change at once.
 */

var DEFAULT_GROSS_SALARY = 2000;
var MAX_GROSS_SALARY = 99999;
var INCOME_TAX_RATE = 0.22;
var MONTHLY_BASIC_EXEMPTION = 700;
var UNEMPLOYMENT_INSURANCE_RATE = 0.016;
var MONTHS_IN_YEAR = 12;
var GROUP_THOUSANDS_FROM = 10000;
var STATUS_DELAY_MS = 500;

function incomeTax(taxableSalary) {
    return Math.max(taxableSalary - MONTHLY_BASIC_EXEMPTION, 0) * INCOME_TAX_RATE;
}

/**
 * @param {number} grossSalary - Gross monthly salary in euros
 * @param {number} paymentRatePercent - Own contribution, 2, 4 or 6
 */
function computePaymentRateFigures(grossSalary, paymentRatePercent) {
    var afterInsurance = grossSalary * (1 - UNEMPLOYMENT_INSURANCE_RATE);
    var contribution = (grossSalary * paymentRatePercent) / 100;
    var taxWithContribution = incomeTax(afterInsurance - contribution);
    var monthlyTaxWin = incomeTax(afterInsurance) - taxWithContribution;
    return {
        netSalary: Math.floor(afterInsurance - contribution - taxWithContribution),
        contribution: Math.floor(contribution),
        yearlyTaxWin: Math.floor(monthlyTaxWin * MONTHS_IN_YEAR)
    };
}

function isWithinTaxFreeAllowance(grossSalary) {
    return grossSalary * (1 - UNEMPLOYMENT_INSURANCE_RATE) <= MONTHLY_BASIC_EXEMPTION;
}

/**
 * Keeps the digits of what was typed or pasted: "2 500,00 €" from a payslip is 2500,
 * a letter is dropped, and a salary above the cap is the cap.
 */
function salaryDigits(text) {
    var digits = text.replace(/[,.]\d{1,2}\s*€?\s*$/, '').replace(/\D/g, '');
    if (digits !== '' && Number(digits) > MAX_GROSS_SALARY) {
        return String(MAX_GROSS_SALARY);
    }
    return digits;
}

function formatEuros(value, langcode) {
    var separator = langcode === 'en' ? ',' : ' ';
    var text = value >= GROUP_THOUSANDS_FROM ? String(value).replace(/\B(?=(\d{3})+(?!\d))/g, separator) : String(value);
    return text + ' €';
}

(function () {
    if (typeof document === 'undefined') {
        return;
    }
    var calculator = document.querySelector('.payment-rate-calculator');
    if (!calculator) {
        return;
    }
    var langcode = typeof LANGCODE === 'undefined' ? 'et' : LANGCODE;
    var salary = calculator.querySelector('#paymentRateSalary');
    var note = calculator.querySelector('[data-tax-win-note]');
    var status = calculator.querySelector('[role="status"]');
    var rateLabel = calculator.querySelector('#paymentRateLabel').textContent.trim();
    var statusTimer = null;

    function grossSalary() {
        var parsed = parseInt(salary.value, 10);
        return isNaN(parsed) ? DEFAULT_GROSS_SALARY : parsed;
    }

    function selectedRate() {
        return Number(calculator.querySelector('input[name="paymentRate"]:checked').value);
    }

    function keepDigits() {
        var digitsAfterCaret = salaryDigits(salary.value.slice(salary.selectionEnd || salary.value.length)).length;
        var digits = salaryDigits(salary.value);
        if (digits === salary.value) {
            return;
        }
        salary.value = digits;
        var caret = Math.max(0, digits.length - digitsAfterCaret);
        salary.setSelectionRange(caret, caret);
    }

    function announce(rate) {
        clearTimeout(statusTimer);
        statusTimer = setTimeout(function () {
            var rows = [].map.call(calculator.querySelectorAll('[data-figure]'), function (figure) {
                return figure.dataset.label + ' ' + figure.textContent;
            });
            status.textContent = rateLabel + ' ' + rate + '%: ' + rows.join(', ') + '.';
        }, STATUS_DELAY_MS);
    }

    function showFigures() {
        var gross = grossSalary();
        var figures = computePaymentRateFigures(gross, selectedRate());
        calculator.querySelectorAll('[data-figure]').forEach(function (figure) {
            figure.textContent = formatEuros(figures[figure.dataset.figure], langcode);
        });
        var taxFree = isWithinTaxFreeAllowance(gross);
        calculator.classList.toggle('is-tax-free', taxFree);
        note.textContent = taxFree ? note.dataset.taxFree : note.dataset.taxWin;
    }

    function showChangedFigures() {
        showFigures();
        announce(selectedRate());
    }

    salary.addEventListener('input', function () {
        keepDigits();
        showChangedFigures();
    });
    calculator.querySelectorAll('input[name="paymentRate"]').forEach(function (rate) {
        rate.addEventListener('change', showChangedFigures);
    });
    salary.parentNode.addEventListener('click', function () {
        salary.focus();
    });
    window.addEventListener('pageshow', showFigures);
    showFigures();

    window.initSalaryArrivalFocus(salary);
})();

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        computePaymentRateFigures: computePaymentRateFigures,
        isWithinTaxFreeAllowance: isWithinTaxFreeAllowance,
        salaryDigits: salaryDigits,
        formatEuros: formatEuros,
        MAX_GROSS_SALARY: MAX_GROSS_SALARY
    };
}
