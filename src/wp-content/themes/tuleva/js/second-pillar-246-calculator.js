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
var YOUNGEST_AGE = 18;
var OLDEST_AGE = RETIREMENT_AGE - 1;
var MONTHS_IN_YEAR = 12;
var HISTORIC_STOCK_RETURN_PERCENT = 7;

function thousandsBelow(value) {
    return Math.floor(value / 1000) * 1000;
}

function thousandsAddingUpTo(parts) {
    var sum = function (total, part) {
        return total + part;
    };
    var floored = parts.map(thousandsBelow);
    var missingThousands = (thousandsBelow(parts.reduce(sum, 0)) - floored.reduce(sum, 0)) / 1000;
    parts
        .map(function (part, index) {
            return index;
        })
        .sort(function (a, b) {
            return parts[b] - floored[b] - (parts[a] - floored[a]);
        })
        .slice(0, missingThousands)
        .forEach(function (index) {
            floored[index] += 1000;
        });
    return floored;
}

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
    var fromReturns = Math.round(exactTotal - savedWithGrowingSalary(intoPillar, 0, years));
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
    var salary2 = page.querySelector('#gross2');
    var age = page.querySelector('#age');
    var annualReturn = page.querySelector('#ret');
    var rateOptions = page.querySelectorAll('#segRate input[name="rate"]');

    function show(id, text) {
        page.querySelector('#' + id).textContent = text;
    }

    function eur(value) {
        return value.toLocaleString('et-EE') + ' €';
    }

    function parseEuros(text) {
        return Number(text.replace(/\s/g, ''));
    }

    function readSalary() {
        var raw = parseEuros(salary.value);
        if (!Number.isFinite(raw) || raw < 0) {
            return 0;
        }
        return Math.min(raw, MAX_GROSS_SALARY);
    }

    var lastFullAge = clampAge(Number(age.value));
    var growthRate = DEFAULT_PAYMENT_RATE_PERCENT;

    function clampAge(value) {
        return Math.min(OLDEST_AGE, Math.max(YOUNGEST_AGE, value));
    }

    function typedAge() {
        var text = age.value.trim();
        return text === '' ? NaN : Number(text);
    }

    function readAge() {
        var typed = typedAge();
        if (Number.isInteger(typed) && typed >= YOUNGEST_AGE && typed <= OLDEST_AGE) {
            lastFullAge = typed;
        }
        return lastFullAge;
    }

    function settleAge() {
        var typed = typedAge();
        if (Number.isFinite(typed)) {
            lastFullAge = clampAge(Math.round(typed));
        }
        age.value = String(lastFullAge);
    }

    function selectedRate() {
        var picked = page.querySelector('#segRate input[name="rate"]:checked');
        return picked ? Number(picked.value) : DEFAULT_PAYMENT_RATE_PERCENT;
    }

    function inputs() {
        return {
            salary: readSalary(),
            rate: selectedRate(),
            age: readAge(),
            annualReturn: Number(annualReturn.value) / 100
        };
    }

    function showCard(input) {
        var monthly = computeContributions(input.salary, input.rate);
        show('cNet', eur(monthly.netSalaryCost));
        show('cTax', eur(monthly.incomeTaxSaved));
        show('cState', eur(monthly.state));
        show('cInflow', eur(monthly.intoPillar));
    }

    function showColumns(input) {
        var projections = PAYMENT_RATES_PERCENT.map(function (rate) {
            return projectWealth(input.salary, rate, input.age, input.annualReturn);
        });
        var highest = Math.max.apply(null, projections.map(function (wealth) {
            return wealth.total;
        })) || 1;
        PAYMENT_RATES_PERCENT.forEach(function (rate, index) {
            var wealth = projections[index];
            var column = page.querySelector('#vc' + rate);
            show('vv' + rate, eur(thousandsBelow(wealth.total)));
            column.style.height = (wealth.total / highest) * 100 + '%';
            var bar = column.closest('.vbar');
            bar.classList.toggle('dim', rate !== growthRate);
            bar.setAttribute('aria-pressed', String(rate === growthRate));
        });
    }

    function showBreakdown(input) {
        var wealth = projectWealth(input.salary, growthRate, input.age, input.annualReturn);
        var names = ['Net', 'Tax', 'State', 'Grow'];
        var parts = [wealth.fromNetSalary, wealth.fromIncomeTax, wealth.fromSocialTax, wealth.fromReturns];
        var shownParts = thousandsAddingUpTo(parts);
        var drawn = parts.map(function (part) {
            return Math.max(0, part);
        });
        var whole = drawn.reduce(function (total, part) {
            return total + part;
        }, 0) || 1;
        names.forEach(function (name, index) {
            page.querySelector('#s' + name).style.width = (drawn[index] / whole) * 100 + '%';
            show('l' + name, eur(shownParts[index]));
        });
        page.querySelector('#lGrow').parentElement.hidden = wealth.fromReturns === 0;
        show('lTotal', eur(thousandsBelow(wealth.total)));
    }

    function update() {
        var input = inputs();
        showCard(input);
        showColumns(input);
        showBreakdown(input);
    }

    rateOptions.forEach(function (option) {
        option.addEventListener('change', update);
    });
    // Both calculators share one gross salary: editing either salary field
    // mirrors the value to the other, so the figures never disagree.
    function mirrorSalary(source) {
        var target = source === salary ? salary2 : salary;
        if (target) {
            target.value = source.value.replace(/\s/g, '');
        }
        update();
    }
    [salary, salary2].forEach(function (field) {
        if (!field) {
            return;
        }
        field.addEventListener('input', function () {
            mirrorSalary(field);
        });
        field.addEventListener('change', function () {
            if (parseEuros(field.value) > MAX_GROSS_SALARY) {
                field.value = MAX_GROSS_SALARY;
            }
            mirrorSalary(field);
        });
    });
    [age, annualReturn].forEach(function (field) {
        field.addEventListener('input', update);
    });
    page.querySelectorAll('.vbar').forEach(function (bar) {
        bar.addEventListener('click', function () {
            growthRate = Number(bar.dataset.r);
            update();
        });
    });
    age.addEventListener('change', function () {
        settleAge();
        update();
    });
    page.querySelector('.historic-return-rate').addEventListener('click', function () {
        annualReturn.value = HISTORIC_STOCK_RETURN_PERCENT;
        annualReturn.dispatchEvent(new Event('input', { bubbles: true }));
    });
    if (salary2) {
        salary2.value = salary.value;
    }
    window.addEventListener('pageshow', update);
    update();

    window.initSalaryArrivalFocus(salary);
})();

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        thousandsAddingUpTo: thousandsAddingUpTo,
        computeContributions: computeContributions,
        projectWealth: projectWealth,
        MAX_GROSS_SALARY: MAX_GROSS_SALARY
    };
}
