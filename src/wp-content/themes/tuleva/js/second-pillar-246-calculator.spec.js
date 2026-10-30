const {
    computeContributions,
    projectWealth,
    wealthByYear,
    MAX_GROSS_SALARY
} = require('./second-pillar-246-calculator');
const { computeRejoinContributions } = require('./second-pillar-rejoin-calculator');

describe('computeContributions', () => {
    it('splits the 3000 € example at 6% into 140 € from net salary, 40 € of income tax and 120 € from social tax', () => {
        expect(computeContributions(3000, 6)).toEqual({
            ownContribution: 180,
            netSalaryCost: 140,
            incomeTaxSaved: 40,
            yearlyIncomeTaxSaved: 480,
            state: 120,
            intoPillar: 300
        });
    });

    it.each([0, 500, 730, 1005, 2000, 3000, MAX_GROSS_SALARY].flatMap((salary) => [2, 4, 6].map((rate) => [salary, rate])))(
        'splits %d € at %d% the same way the rejoin calculator does',
        (salary, rate) => {
            const rejoin = computeRejoinContributions(salary, rate);

            expect(computeContributions(salary, rate)).toMatchObject({
                netSalaryCost: rejoin.netSalaryCost,
                incomeTaxSaved: rejoin.incomeTaxSaved,
                state: rejoin.state,
                intoPillar: rejoin.intoPillar
            });
        }
    );

    it('saves no income tax when the basic exemption covers the salary', () => {
        expect(computeContributions(700, 6)).toEqual({
            ownContribution: 42,
            netSalaryCost: 42,
            incomeTaxSaved: 0,
            yearlyIncomeTaxSaved: 0,
            state: 28,
            intoPillar: 70
        });
    });

    it('saves income tax only on the part of the contribution above the basic exemption', () => {
        expect(computeContributions(730, 6).incomeTaxSaved).toBe(4);
    });

    it('rounds each part to whole euros so the card adds up to what goes into the II pillar', () => {
        const result = computeContributions(1005, 6);

        expect(result.netSalaryCost + result.incomeTaxSaved).toBe(result.ownContribution);
        expect(result.ownContribution + result.state).toBe(result.intoPillar);
    });
});

describe('projectWealth', () => {
    it('grows a year of 60 € a month with the salary to 730 € at a zero return', () => {
        expect(projectWealth(1000, 2, 64, 0)).toEqual({
            fromNetSalary: 189,
            fromIncomeTax: 54,
            fromSocialTax: 487,
            fromReturns: 0,
            total: 730
        });
    });

    it('puts what a 7% return adds on top of the contributions into its own part', () => {
        expect(projectWealth(1000, 2, 64, 0.07)).toEqual({
            fromNetSalary: 189,
            fromIncomeTax: 54,
            fromSocialTax: 487,
            fromReturns: 23,
            total: 753
        });
    });

    it('starts saving income tax once a salary the basic exemption covers grows past it', () => {
        expect(projectWealth(700, 6, 35, 0).fromIncomeTax).toBe(5161);
    });

    it('still compounds when the return equals the salary growth', () => {
        expect(projectWealth(1000, 2, 64, 0.03).total).toBe(740);
    });

    it('rounds each part to whole euros so the legend adds up to the column', () => {
        const result = projectWealth(3000, 6, 35, 0.07);

        expect(result.fromNetSalary + result.fromIncomeTax + result.fromSocialTax + result.fromReturns).toBe(
            result.total
        );
    });

    it('saves more at a higher payment rate', () => {
        const totals = [2, 4, 6].map((rate) => projectWealth(3000, rate, 35, 0).total);

        expect(totals).toEqual([...totals].sort((a, b) => a - b));
        expect(new Set(totals).size).toBe(3);
    });
});

describe('wealthByYear', () => {
    it('runs from nothing today to the projected total at 65', () => {
        const years = wealthByYear(3000, 6, 35, 0.07);

        expect(years).toHaveLength(31);
        expect(years[0]).toBe(0);
        expect(Math.round(years[30])).toBe(projectWealth(3000, 6, 35, 0.07).total);
    });
});
