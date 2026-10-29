const {
    computeRejoinContributions,
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
