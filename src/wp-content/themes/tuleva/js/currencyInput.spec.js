const { handleCurrencyInput, parseCurrencyValue, EURO_REGEX } = require('./currencyInput');

describe('handleCurrencyInput', () => {
    it('accepts empty input', () => {
        expect(handleCurrencyInput('')).toEqual({ value: '', valid: true });
    });

    it('accepts whole numbers', () => {
        expect(handleCurrencyInput('123')).toEqual({ value: '123', valid: true });
        expect(handleCurrencyInput('0')).toEqual({ value: '0', valid: true });
        expect(handleCurrencyInput('999999')).toEqual({ value: '999999', valid: true });
    });

    it('accepts decimal with period', () => {
        expect(handleCurrencyInput('123.45')).toEqual({ value: '123.45', valid: true });
        expect(handleCurrencyInput('0.99')).toEqual({ value: '0.99', valid: true });
    });

    it('accepts decimal with comma and normalizes to period', () => {
        expect(handleCurrencyInput('123,45')).toEqual({ value: '123.45', valid: true });
        expect(handleCurrencyInput('0,99')).toEqual({ value: '0.99', valid: true });
    });

    it('accepts single decimal digit', () => {
        expect(handleCurrencyInput('123.4')).toEqual({ value: '123.4', valid: true });
        expect(handleCurrencyInput('123,4')).toEqual({ value: '123.4', valid: true });
    });

    it('accepts value with just decimal separator', () => {
        expect(handleCurrencyInput('123.')).toEqual({ value: '123.', valid: true });
        expect(handleCurrencyInput('123,')).toEqual({ value: '123.', valid: true });
    });

    it('rejects more than 2 decimal places', () => {
        expect(handleCurrencyInput('123.456')).toEqual({ value: '123.45', valid: false });
        expect(handleCurrencyInput('123,456')).toEqual({ value: '123,45', valid: false });
    });

    it('rejects non-numeric input', () => {
        expect(handleCurrencyInput('abc')).toEqual({ value: 'ab', valid: false });
        expect(handleCurrencyInput('12a')).toEqual({ value: '12', valid: false });
        expect(handleCurrencyInput('12.3a')).toEqual({ value: '12.3', valid: false });
    });

    it('rejects multiple decimal separators', () => {
        expect(handleCurrencyInput('12.34.56')).toEqual({ value: '12.34.5', valid: false });
        expect(handleCurrencyInput('12,34,56')).toEqual({ value: '12,34,5', valid: false });
    });

    it('rejects negative numbers', () => {
        expect(handleCurrencyInput('-123')).toEqual({ value: '-12', valid: false });
    });

    it('enforces max value', () => {
        expect(handleCurrencyInput('150', { max: 100 })).toEqual({ value: '100.00', valid: true });
        expect(handleCurrencyInput('99.99', { max: 100 })).toEqual({ value: '99.99', valid: true });
        expect(handleCurrencyInput('100', { max: 100 })).toEqual({ value: '100', valid: true });
        expect(handleCurrencyInput('100.01', { max: 100 })).toEqual({ value: '100.00', valid: true });
    });

    it('rounds max value to 2 decimal places', () => {
        expect(handleCurrencyInput('50', { max: 32.12345 })).toEqual({ value: '32.12', valid: true });
    });
});

describe('parseCurrencyValue', () => {
    it('returns default for empty string', () => {
        expect(parseCurrencyValue('', 0)).toBe(0);
        expect(parseCurrencyValue('', 100)).toBe(100);
        expect(parseCurrencyValue('', null)).toBe(null);
    });

    it('returns default for undefined', () => {
        expect(parseCurrencyValue(undefined, 0)).toBe(0);
        expect(parseCurrencyValue(undefined, 50)).toBe(50);
    });

    it('returns default for null', () => {
        expect(parseCurrencyValue(null, 0)).toBe(0);
        expect(parseCurrencyValue(null, 25)).toBe(25);
    });

    it('parses whole numbers', () => {
        expect(parseCurrencyValue('123', 0)).toBe(123);
        expect(parseCurrencyValue('0', 100)).toBe(0);
    });

    it('parses decimals with period', () => {
        expect(parseCurrencyValue('123.45', 0)).toBe(123.45);
        expect(parseCurrencyValue('0.99', 0)).toBe(0.99);
    });

    it('parses decimals with comma (normalizes to period)', () => {
        expect(parseCurrencyValue('123,45', 0)).toBe(123.45);
        expect(parseCurrencyValue('0,99', 0)).toBe(0.99);
    });

    it('returns default for non-numeric input', () => {
        expect(parseCurrencyValue('abc', 0)).toBe(0);
    });

    it('parses partial numbers (parseFloat behavior)', () => {
        // parseFloat('12.34.56') returns 12.34 - this is expected JS behavior
        // handleCurrencyInput prevents such values from being entered
        expect(parseCurrencyValue('12.34.56', 0)).toBe(12.34);
    });

    it('handles string numbers', () => {
        expect(parseCurrencyValue('2000', 0)).toBe(2000);
    });
});

describe('EURO_REGEX', () => {
    it('matches valid currency formats', () => {
        expect(EURO_REGEX.test('123')).toBe(true);
        expect(EURO_REGEX.test('123.45')).toBe(true);
        expect(EURO_REGEX.test('123,45')).toBe(true);
        expect(EURO_REGEX.test('123.4')).toBe(true);
        expect(EURO_REGEX.test('123.')).toBe(true);
        expect(EURO_REGEX.test('0')).toBe(true);
        expect(EURO_REGEX.test('0.00')).toBe(true);
    });

    it('rejects invalid formats', () => {
        expect(EURO_REGEX.test('123.456')).toBe(false);
        expect(EURO_REGEX.test('abc')).toBe(false);
        expect(EURO_REGEX.test('-123')).toBe(false);
        expect(EURO_REGEX.test('12.34.56')).toBe(false);
        expect(EURO_REGEX.test('')).toBe(false);
        expect(EURO_REGEX.test('.99')).toBe(false);
    });
});