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
        expect(futureValue(5000, 200, 0, PROJECTION_MONTHS)).toBe(53000);
    });

    it('grows the lump sum alone by the annual rate', () => {
        expect(futureValue(5000, 0, monthlyRate(7), PROJECTION_MONTHS)).toBeCloseTo(5000 * Math.pow(1.07, 20), 6);
    });

    it('returns the lump sum untouched for zero months', () => {
        expect(futureValue(5000, 200, monthlyRate(7), 0)).toBe(5000);
    });
});

describe('computeCompanySavings', () => {
    it('projects twenty years', () => {
        expect(PROJECTION_YEARS).toBe(20);
        expect(computeCompanySavings({ initial: 5000, monthly: 200, annualRatePercent: 7 }).months).toBe(240);
    });

    // Pins the numbers the calculator card shows for its default inputs
    it('matches the default of 53 000 € at zero return', () => {
        const result = computeCompanySavings({ initial: 5000, monthly: 200, annualRatePercent: 0 });
        expect(result.total).toBe(53000);
        expect(result.paid).toBe(53000);
        expect(result.gain).toBe(0);
    });

    it('matches the historic 7% return', () => {
        const result = computeCompanySavings({ initial: 5000, monthly: 200, annualRatePercent: 7 });
        expect(result.total).toBeCloseTo(120855.69762510528, 6);
        expect(result.paid).toBe(53000);
        expect(result.gain).toBeCloseTo(67855.69762510528, 6);
    });

    it('matches the slider maximum', () => {
        expect(computeCompanySavings({ initial: 5000, monthly: 200, annualRatePercent: 10 }).total)
            .toBeCloseTo(177289.34536914172, 6);
    });

    it('shrinks below contributions at the slider minimum', () => {
        const result = computeCompanySavings({ initial: 5000, monthly: 200, annualRatePercent: -10 });
        expect(result.total).toBeCloseTo(20705.399622327073, 6);
        expect(result.gain).toBeLessThan(0);
    });

    it('handles a lump sum with no monthly contribution', () => {
        expect(computeCompanySavings({ initial: 5000, monthly: 0, annualRatePercent: 7 }).total)
            .toBeCloseTo(19348.42231243088, 6);
    });

    it('handles a monthly contribution with no lump sum', () => {
        expect(computeCompanySavings({ initial: 0, monthly: 200, annualRatePercent: 7 }).total)
            .toBeCloseTo(101507.2753126744, 6);
    });

    it('returns zero when nothing is invested', () => {
        expect(computeCompanySavings({ initial: 0, monthly: 0, annualRatePercent: 7 }).total).toBe(0);
    });
});
