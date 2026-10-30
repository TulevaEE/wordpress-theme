/**
 * II pillar 2-4-6 landing page calculators. The card splits the monthly
 * contribution the same way the rejoin calculator does, and the projection
 * grows those parts with the salary until retirement age.
 */

var DEFAULT_PAYMENT_RATE_PERCENT = 6;
var PAYMENT_RATES_PERCENT = [2, 4, 6];
var STATE_CONTRIBUTION_PERCENT = 4;
var INCOME_TAX_RATE = 0.22;
var MONTHLY_BASIC_EXEMPTION = 700;
var UNEMPLOYMENT_INSURANCE_RATE = 0.016;
var MAX_GROSS_SALARY = 99999;
var SALARY_GROWTH_RATE = 0.03;
var RETIREMENT_AGE = 65;
var MONTHS_IN_YEAR = 12;
var RATE_COLORS = { 2: '#5C738C', 4: '#00AEEA', 6: '#002F63' };
var CHART_WIDTH = 360;
var CHART_HEIGHT = 230;
var CHART_PADDING = { left: 50, right: 16, top: 12, bottom: 26 };
var AXIS_IN_THOUSANDS_FROM = 10000;

function incomeTaxSaved(grossSalary, ownContribution) {
    var taxableBeforeContribution =
        grossSalary * (1 - UNEMPLOYMENT_INSURANCE_RATE) - MONTHLY_BASIC_EXEMPTION;
    return Math.min(ownContribution, Math.max(0, taxableBeforeContribution)) * INCOME_TAX_RATE;
}

/**
 * Each part is rounded to whole euros before anything is added up, so the
 * figures on the card always sum exactly.
 *
 * @param {number} grossSalary - Gross monthly salary in euros
 * @param {number} paymentRatePercent - Own contribution, e.g. 6
 */
function computeContributions(grossSalary, paymentRatePercent) {
    var exactOwnContribution = (grossSalary * paymentRatePercent) / 100;
    var ownContribution = Math.round(exactOwnContribution);
    var state = Math.round((grossSalary * STATE_CONTRIBUTION_PERCENT) / 100);
    var saved = Math.round(incomeTaxSaved(grossSalary, exactOwnContribution));
    return {
        ownContribution: ownContribution,
        netSalaryCost: ownContribution - saved,
        incomeTaxSaved: saved,
        yearlyIncomeTaxSaved: saved * MONTHS_IN_YEAR,
        state: state,
        intoPillar: ownContribution + state
    };
}

function monthlyRate(annualRate) {
    return Math.pow(1 + annualRate, 1 / MONTHS_IN_YEAR) - 1;
}

function savedWithGrowingSalary(firstMonthlyAmount, annualReturn, years) {
    var months = years * MONTHS_IN_YEAR;
    var growth = monthlyRate(annualReturn);
    var salaryGrowth = monthlyRate(SALARY_GROWTH_RATE);
    if (Math.abs(growth - salaryGrowth) < 1e-12) {
        return firstMonthlyAmount * months * Math.pow(1 + growth, months - 1);
    }
    return (
        (firstMonthlyAmount * (Math.pow(1 + growth, months) - Math.pow(1 + salaryGrowth, months))) /
        (growth - salaryGrowth)
    );
}

function yearsUntilRetirement(age) {
    return Math.max(0, RETIREMENT_AGE - age);
}

function monthlyIntoPillar(grossSalary, paymentRatePercent) {
    return (grossSalary * (paymentRatePercent + STATE_CONTRIBUTION_PERCENT)) / 100;
}

function incomeTaxSavedUntilRetirement(grossSalary, paymentRatePercent, years) {
    var salaryGrowth = monthlyRate(SALARY_GROWTH_RATE);
    var saved = 0;
    for (var month = 0; month < years * MONTHS_IN_YEAR; month++) {
        var salary = grossSalary * Math.pow(1 + salaryGrowth, month);
        saved += incomeTaxSaved(salary, (salary * paymentRatePercent) / 100);
    }
    return saved;
}

function wealthByYear(grossSalary, paymentRatePercent, age, annualReturn) {
    var intoPillar = monthlyIntoPillar(grossSalary, paymentRatePercent);
    var years = [];
    for (var year = 0; year <= yearsUntilRetirement(age); year++) {
        years.push(savedWithGrowingSalary(intoPillar, annualReturn, year));
    }
    return years;
}

/**
 * Income tax is counted month by month, because the basic exemption does not
 * grow with the salary. The net salary part is what is left of the rounded
 * total, so the legend always adds up to the column.
 */
function projectWealth(grossSalary, paymentRatePercent, age, annualReturn) {
    var years = yearsUntilRetirement(age);
    var intoPillar = monthlyIntoPillar(grossSalary, paymentRatePercent);
    var exactTotal = savedWithGrowingSalary(intoPillar, annualReturn, years);
    var total = Math.round(exactTotal);
    var fromReturns = Math.max(0, Math.round(exactTotal - savedWithGrowingSalary(intoPillar, 0, years)));
    var fromIncomeTax = Math.round(incomeTaxSavedUntilRetirement(grossSalary, paymentRatePercent, years));
    var fromSocialTax = Math.round(
        savedWithGrowingSalary((grossSalary * STATE_CONTRIBUTION_PERCENT) / 100, 0, years)
    );
    return {
        fromNetSalary: total - fromReturns - fromIncomeTax - fromSocialTax,
        fromIncomeTax: fromIncomeTax,
        fromSocialTax: fromSocialTax,
        fromReturns: fromReturns,
        total: total
    };
}

(function () {
    if (typeof document === 'undefined') {
        return;
    }
    var page = document.querySelector('.second-pillar-246-page');
    if (!page) {
        return;
    }
    var salary = page.querySelector('#gross');
    var age = page.querySelector('#age');
    var annualReturn = page.querySelector('#ret');
    var rateButtons = page.querySelectorAll('#segRate button');

    function show(id, text) {
        page.querySelector('#' + id).textContent = text;
    }

    function eur(value) {
        return value.toLocaleString('et-EE') + ' €';
    }

    function axisLabel(value) {
        if (value < AXIS_IN_THOUSANDS_FROM) {
            return eur(Math.round(value));
        }
        return Math.round(value / 1000).toLocaleString('et-EE') + ' k€';
    }

    function readSalary() {
        var raw = Number(salary.value);
        if (!Number.isFinite(raw) || raw < 0) {
            return 0;
        }
        return Math.min(raw, MAX_GROSS_SALARY);
    }

    function selectedRate() {
        var pressed = page.querySelector('#segRate button[aria-pressed="true"]');
        return pressed ? Number(pressed.dataset.r) : DEFAULT_PAYMENT_RATE_PERCENT;
    }

    function inputs() {
        return {
            salary: readSalary(),
            rate: selectedRate(),
            age: Number(age.value),
            annualReturn: Number(annualReturn.value) / 100
        };
    }

    function showCard(input) {
        var monthly = computeContributions(input.salary, input.rate);
        show('cNet', eur(monthly.netSalaryCost));
        show('cPil', eur(monthly.ownContribution));
        show('cState', eur(monthly.state));
        show('cInflow', eur(monthly.intoPillar));
        show('cTax', eur(monthly.yearlyIncomeTaxSaved));
    }

    function showColumns(input) {
        var totals = PAYMENT_RATES_PERCENT.map(function (rate) {
            return projectWealth(input.salary, rate, input.age, input.annualReturn).total;
        });
        var highest = Math.max.apply(null, totals) || 1;
        PAYMENT_RATES_PERCENT.forEach(function (rate, index) {
            var column = page.querySelector('#vc' + rate);
            show('vv' + rate, eur(totals[index]));
            column.style.height = (totals[index] / highest) * 100 + '%';
            column.closest('.vbar').classList.toggle('dim', rate !== input.rate);
        });
    }

    function chart(input) {
        var years = Math.max(1, yearsUntilRetirement(input.age));
        var plotWidth = CHART_WIDTH - CHART_PADDING.left - CHART_PADDING.right;
        var plotHeight = CHART_HEIGHT - CHART_PADDING.top - CHART_PADDING.bottom;
        var lines = PAYMENT_RATES_PERCENT.map(function (rate) {
            return wealthByYear(input.salary, rate, input.age, input.annualReturn);
        });
        var highest = Math.max(lines[lines.length - 1][years] || 0, 1);
        var x = function (year) {
            return CHART_PADDING.left + (year / years) * plotWidth;
        };
        var y = function (value) {
            return CHART_PADDING.top + plotHeight - (value / highest) * plotHeight;
        };
        var svg =
            '<svg viewBox="0 0 ' + CHART_WIDTH + ' ' + CHART_HEIGHT +
            '" role="img" aria-label="Vara kasv vanusega 2%, 4% ja 6% määraga (näitlik prognoos)">';
        [0, 0.5, 1].forEach(function (share) {
            var lineY = y(highest * share).toFixed(1);
            svg +=
                '<line class="grid" x1="' + CHART_PADDING.left + '" y1="' + lineY + '" x2="' + (CHART_WIDTH - CHART_PADDING.right) +
                '" y2="' + lineY + '"/>' +
                '<text class="cy" x="' + (CHART_PADDING.left - 7) + '" y="' + (Number(lineY) + 3).toFixed(1) + '" text-anchor="end">' +
                (share === 0 ? '0' : axisLabel(highest * share)) + '</text>';
        });
        [0, 0.5, 1].forEach(function (share) {
            var year = Math.round(years * share);
            svg +=
                '<text class="cx" x="' + x(year).toFixed(1) + '" y="' + (CHART_HEIGHT - 9) + '" text-anchor="middle">' +
                (input.age + year) + ' a</text>';
        });
        PAYMENT_RATES_PERCENT.forEach(function (rate, index) {
            var selected = rate === input.rate;
            var points = lines[index].map(function (value, year) {
                return x(year).toFixed(1) + ',' + y(value).toFixed(1);
            });
            svg +=
                '<polyline points="' + points.join(' ') + '" fill="none" stroke="' + RATE_COLORS[rate] +
                '" stroke-width="' + (selected ? 3.6 : 2) + '" opacity="' + (selected ? 1 : 0.5) +
                '" stroke-linejoin="round" stroke-linecap="round"/>';
        });
        PAYMENT_RATES_PERCENT.forEach(function (rate, index) {
            var end = lines[index][years] || 0;
            svg +=
                '<text class="cend" x="' + (x(years) - 3).toFixed(1) + '" y="' + (y(end) - 5).toFixed(1) +
                '" text-anchor="end" fill="' + RATE_COLORS[rate] + '" opacity="' + (rate === input.rate ? 1 : 0.65) + '">' +
                rate + '%</text>';
        });
        return svg + '</svg>';
    }

    function showBreakdown(input) {
        var wealth = projectWealth(input.salary, input.rate, input.age, input.annualReturn);
        var whole = wealth.total || 1;
        [
            ['Net', wealth.fromNetSalary],
            ['State', wealth.fromSocialTax],
            ['Tax', wealth.fromIncomeTax],
            ['Grow', wealth.fromReturns]
        ].forEach(function (part) {
            page.querySelector('#s' + part[0]).style.width = (part[1] / whole) * 100 + '%';
            show('l' + part[0], eur(part[1]));
        });
    }

    function update() {
        var input = inputs();
        show('ageVal', String(input.age));
        show('retVal', (input.annualReturn * 100).toLocaleString('et-EE') + '%');
        showCard(input);
        showColumns(input);
        page.querySelector('#chart').innerHTML = chart(input);
        showBreakdown(input);
    }

    rateButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            rateButtons.forEach(function (other) {
                other.setAttribute('aria-pressed', String(other === button));
            });
            update();
        });
    });
    [salary, age, annualReturn].forEach(function (field) {
        field.addEventListener('input', update);
    });
    salary.addEventListener('change', function () {
        if (Number(salary.value) > MAX_GROSS_SALARY) {
            salary.value = MAX_GROSS_SALARY;
        }
    });
    window.addEventListener('pageshow', update);
    update();
})();

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        computeContributions: computeContributions,
        projectWealth: projectWealth,
        wealthByYear: wealthByYear,
        MAX_GROSS_SALARY: MAX_GROSS_SALARY
    };
}
