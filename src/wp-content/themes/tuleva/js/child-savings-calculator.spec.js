const {
    futureValue,
    childTaxWin,
    computeChildSavings,
    TAX_RATE,
    CHILD_TAX_FREE_INCOME,
    SALE_YEARS
} = require('./child-savings-calculator');

describe('futureValue', () => {
    it('returns contributions only at zero rate', () => {
        expect(futureValue(80, 0, 216)).toBe(17280);
    });

    it('compounds monthly contributions at a positive rate', () => {
        expect(futureValue(80, 0.07 / 12, 216)).toBeCloseTo(34457.68212827983, 6);
    });

    it('shrinks below contributions at a negative rate', () => {
        expect(futureValue(80, -0.10 / 12, 216)).toBeCloseTo(8025.053710734891, 6);
    });

    it('returns zero for zero months', () => {
        expect(futureValue(80, 0.07 / 12, 0)).toBe(0);
    });
});

describe('childTaxWin', () => {
    it('is zero when there is no gain', () => {
        expect(childTaxWin(0)).toBe(0);
    });

    it('is zero for a negative gain', () => {
        expect(childTaxWin(-9254.95)).toBe(0);
    });

    it('equals the full parent tax when the gain fits within the tax-free allowance', () => {
        const gain = 17177.682128279834;
        expect(childTaxWin(gain)).toBeCloseTo(TAX_RATE * gain, 6);
        expect(childTaxWin(gain)).toBeCloseTo(3779.0900682215633, 6);
    });

    it('subtracts the child tax when yearly gains exceed the tax-free allowance', () => {
        expect(childTaxWin(107360.51330174896)).toBeCloseTo(7392, 6);
    });

    it('plateaus at the tax on the total tax-free allowance once yearly gains exceed it', () => {
        const cap = TAX_RATE * SALE_YEARS * CHILD_TAX_FREE_INCOME;
        expect(childTaxWin(SALE_YEARS * CHILD_TAX_FREE_INCOME)).toBeCloseTo(cap, 6);
        expect(childTaxWin(SALE_YEARS * CHILD_TAX_FREE_INCOME + 100)).toBeCloseTo(cap, 6);
        expect(childTaxWin(1000000)).toBeCloseTo(cap, 6);
    });
});

describe('computeChildSavings', () => {
    it('computes the default example: newborn, 80 euros per month, zero return', () => {
        expect(computeChildSavings({ age: 0, monthly: 80, annualRatePercent: 0 })).toEqual({
            months: 216,
            paid: 17280,
            total: 17280,
            gain: 0,
            win: 0
        });
    });

    it('computes a positive-return example: newborn, 80 euros per month, 7 percent', () => {
        const result = computeChildSavings({ age: 0, monthly: 80, annualRatePercent: 7 });
        expect(result.months).toBe(216);
        expect(result.paid).toBe(17280);
        expect(result.total).toBeCloseTo(34457.68212827983, 6);
        expect(result.gain).toBeCloseTo(17177.682128279834, 6);
        expect(result.win).toBeCloseTo(3779.0900682215633, 6);
    });

    it('computes a large-contribution example where the child pays some tax', () => {
        const result = computeChildSavings({ age: 0, monthly: 500, annualRatePercent: 7 });
        expect(result.paid).toBe(108000);
        expect(result.total).toBeCloseTo(215360.51330174896, 6);
        expect(result.gain).toBeCloseTo(107360.51330174896, 6);
        expect(result.win).toBeCloseTo(7392, 6);
    });

    it('reports a loss without any tax win at a negative return', () => {
        const result = computeChildSavings({ age: 0, monthly: 80, annualRatePercent: -10 });
        expect(result.total).toBeCloseTo(8025.053710734891, 6);
        expect(result.gain).toBeCloseTo(-9254.94628926511, 6);
        expect(result.win).toBe(0);
    });

    it('returns all zeros for an 18-year-old', () => {
        expect(computeChildSavings({ age: 18, monthly: 80, annualRatePercent: 7 })).toEqual({
            months: 0,
            paid: 0,
            total: 0,
            gain: 0,
            win: 0
        });
    });
});
