const {
    monthlyRate,
    futureValue,
    computeCompanySavings,
    PROJECTION_YEARS,
    PROJECTION_MONTHS
} = require('./company-savings-calculator');

describe('monthlyRate', () => {
    it('is zero at a zero annual rate', () => {
        expect(monthlyRate(0)).toBe(0);
    });

    it('compounds back to the annual rate over twelve months', () => {
        expect(Math.pow(1 + monthlyRate(7), 12)).toBeCloseTo(1.07, 12);
    });

    it('is negative for a negative annual rate', () => {
        expect(monthlyRate(-10)).toBeCloseTo(-0.008741610954696721, 12);
    });
});

describe('futureValue', () => {
    it('returns the lump sum plus contributions at zero rate', () => {
        expect(futureValue(20000, 200, 0, PROJECTION_MONTHS)).toBe(44000);
    });

    it('grows the lump sum alone by the annual rate', () => {
        expect(futureValue(20000, 0, monthlyRate(7), PROJECTION_MONTHS)).toBeCloseTo(20000 * Math.pow(1.07, 10), 6);
    });

    it('returns the lump sum untouched for zero months', () => {
        expect(futureValue(20000, 200, monthlyRate(7), 0)).toBe(20000);
    });
});

describe('computeCompanySavings', () => {
    it('projects ten years', () => {
        expect(PROJECTION_YEARS).toBe(10);
        expect(computeCompanySavings({ initial: 20000, monthly: 200, annualRatePercent: 7 }).months).toBe(120);
    });

    // Pins the numbers of the static prototype at /vaata/kuhu-investeerida-ettevotte-raha/
    it('matches the prototype default of 44 000 € at zero return', () => {
        const result = computeCompanySavings({ initial: 20000, monthly: 200, annualRatePercent: 0 });
        expect(result.total).toBe(44000);
        expect(result.paid).toBe(44000);
        expect(result.gain).toBe(0);
    });

    it('matches the prototype at the historic 7% return', () => {
        const result = computeCompanySavings({ initial: 20000, monthly: 200, annualRatePercent: 7 });
        expect(result.total).toBeCloseTo(73553.37339701838, 6);
        expect(result.paid).toBe(44000);
        expect(result.gain).toBeCloseTo(29553.37339701838, 6);
    });

    it('matches the prototype at the slider maximum', () => {
        expect(computeCompanySavings({ initial: 20000, monthly: 200, annualRatePercent: 10 }).total)
            .toBeCloseTo(91847.62054480694, 6);
    });

    it('shrinks below contributions at the slider minimum', () => {
        const result = computeCompanySavings({ initial: 20000, monthly: 200, annualRatePercent: -10 });
        expect(result.total).toBeCloseTo(21875.205657619976, 6);
        expect(result.gain).toBeLessThan(0);
    });

    it('handles a lump sum with no monthly contribution', () => {
        expect(computeCompanySavings({ initial: 20000, monthly: 0, annualRatePercent: 7 }).total)
            .toBeCloseTo(39343.02714579129, 6);
    });

    it('handles a monthly contribution with no lump sum', () => {
        expect(computeCompanySavings({ initial: 0, monthly: 200, annualRatePercent: 7 }).total)
            .toBeCloseTo(34210.346251227085, 6);
    });

    it('returns zero when nothing is invested', () => {
        expect(computeCompanySavings({ initial: 0, monthly: 0, annualRatePercent: 7 }).total).toBe(0);
    });
});
