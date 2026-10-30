const {
    computeRejoinContributions,
    computeFirstPillarComparison,
    MAX_GROSS_SALARY
} = require('./second-pillar-rejoin-calculator');

describe('computeRejoinContributions', () => {
    it('splits the 2000 € example into 94 € from net salary and a 106 € tax win', () => {
        expect(computeRejoinContributions(2000, 6)).toEqual({
            netSalaryCost: 94,
            incomeTaxSaved: 26,
            state: 80,
            taxWin: 106,
            intoPillar: 200
        });
    });

    it('rounds each part to whole euros so the parts add up to the monthly amount', () => {
        const result = computeRejoinContributions(1005, 6);

        expect(result.intoPillar).toBe(100);
        expect(result.netSalaryCost + result.taxWin).toBe(result.intoPillar);
    });

    it('leaves only the 4% from social tax in the tax win when the basic exemption covers the salary', () => {
        expect(computeRejoinContributions(500, 6)).toEqual({
            netSalaryCost: 30,
            incomeTaxSaved: 0,
            state: 20,
            taxWin: 20,
            intoPillar: 50
        });
    });

    it('is all zeros at a zero salary', () => {
        expect(computeRejoinContributions(0, 6).intoPillar).toBe(0);
    });

    it('caps the salary at 99 999 €, as the app does', () => {
        expect(MAX_GROSS_SALARY).toBe(99999);
        expect(computeRejoinContributions(MAX_GROSS_SALARY, 6).intoPillar).toBe(10000);
    });
});

describe('computeFirstPillarComparison', () => {
    it('weighs a year of the 2000 € example at 6%, unrounded: 2.04 € a month of I pillar pension, or 1276.80 € from your taxes into the II pillar', () => {
        const comparison = computeFirstPillarComparison(2000, 6);

        expect(comparison.firstPillarMonthly).toBeCloseTo(2.0352, 4);
        expect(comparison.secondPillarYearly).toBeCloseTo(1276.8, 6);
    });

    it('shows its working: 0.194 of a pension unit lost a year, and 960 € of the 1276.80 € from social tax', () => {
        const comparison = computeFirstPillarComparison(2000, 6);

        expect(comparison.firstPillarUnitsLost).toBeCloseTo(0.19425, 5);
        expect(comparison.firstPillarMonthly).toBeCloseTo(comparison.firstPillarUnitsLost * 10.477, 10);
        expect(comparison.socialTaxYearly).toBeCloseTo(960, 6);
    });

    it('counts a year of social tax and of income tax saved on the contribution, which is smaller at a smaller contribution', () => {
        expect(computeFirstPillarComparison(2000, 2).secondPillarYearly).toBeCloseTo(12 * (80 + 8.8), 6);
    });

    it('takes less from the I pillar per euro of salary as the salary grows, because half of a year in the I pillar is the same for everyone', () => {
        expect(computeFirstPillarComparison(1000, 6).firstPillarMonthly).toBeCloseTo(1.5414, 4);
        expect(computeFirstPillarComparison(5000, 6).firstPillarMonthly).toBeCloseTo(3.5164, 4);
    });

    it('shrinks the equal half below the minimum wage of 946 €, in proportion to the salary', () => {
        expect(computeFirstPillarComparison(946, 6).firstPillarMonthly).toBeCloseTo(1.5148, 4);
        expect(computeFirstPillarComparison(500, 6).firstPillarMonthly).toBeCloseTo(0.8006, 4);
    });

    it('leaves out the income tax a salary under the basic exemption does not pay', () => {
        expect(computeFirstPillarComparison(500, 6).secondPillarYearly).toBeCloseTo(12 * 20, 6);
    });

    it.each([500, 946, 1000, 2000, 10000, MAX_GROSS_SALARY])(
        'puts more into the II pillar in a year than the I pillar would pay for it over the 19 years an average retiree lives, at a salary of %d €',
        (grossSalary) => {
            const comparison = computeFirstPillarComparison(grossSalary, 2);

            expect(comparison.secondPillarYearly).toBeGreaterThan(19 * 12 * comparison.firstPillarMonthly);
        }
    );
});
