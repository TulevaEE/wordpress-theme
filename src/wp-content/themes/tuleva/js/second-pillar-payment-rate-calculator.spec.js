const {
    computePaymentRateFigures,
    isWithinTaxFreeAllowance,
    salaryDigits,
    formatEuros,
    MAX_GROSS_SALARY
} = require('./second-pillar-payment-rate-calculator');

describe('computePaymentRateFigures', () => {
    it.each([
        [2000, 2, { netSalary: 1657, contribution: 40, yearlyTaxWin: 105 }],
        [2000, 4, { netSalary: 1626, contribution: 80, yearlyTaxWin: 211 }],
        [2000, 6, { netSalary: 1595, contribution: 120, yearlyTaxWin: 316 }],
        [1700, 6, { netSalary: 1379, contribution: 102, yearlyTaxWin: 269 }],
        [1300, 4, { netSalary: 1111, contribution: 52, yearlyTaxWin: 137 }],
        [4500, 6, { netSalary: 3397, contribution: 270, yearlyTaxWin: 712 }]
    ])('matches what the front page calculator showed for %i € at %i%%', (gross, rate, figures) => {
        expect(computePaymentRateFigures(gross, rate)).toEqual(figures);
    });

    it('wins only the income tax there is, when the contribution is larger than the taxable part', () => {
        expect(computePaymentRateFigures(740, 6)).toEqual({ netSalary: 683, contribution: 44, yearlyTaxWin: 74 });
    });

    it('wins nothing on a salary the tax-free allowance covers', () => {
        expect(computePaymentRateFigures(650, 6)).toEqual({ netSalary: 600, contribution: 39, yearlyTaxWin: 0 });
    });
});

describe('isWithinTaxFreeAllowance', () => {
    it.each([
        [650, true],
        [711, true],
        [712, false],
        [2000, false]
    ])('for %i € is %s', (gross, covered) => {
        expect(isWithinTaxFreeAllowance(gross)).toBe(covered);
    });
});

describe('salaryDigits', () => {
    it.each([
        ['2500', '2500'],
        ['2 500', '2500'],
        ['2 500,00 €', '2500'],
        ['2500.50', '2500'],
        ['25a00', '2500'],
        ['', ''],
        ['150000', String(MAX_GROSS_SALARY)]
    ])('keeps the salary in %p as %p', (typed, digits) => {
        expect(salaryDigits(typed)).toBe(digits);
    });
});

describe('formatEuros', () => {
    it('groups thousands only from five digits, with the separator of the language', () => {
        expect([formatEuros(1595, 'et'), formatEuros(18172, 'et'), formatEuros(18172, 'en')])
            .toEqual(['1595\u00a0€', '18 172\u00a0€', '18,172\u00a0€']);
    });
});
