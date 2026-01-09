/**
 * Currency input validation and parsing utilities.
 * Allows decimal values with comma or period as separator.
 */

var EURO_REGEX = /^\d+([.,]\d{0,2})?$/;

/**
 * Validates and normalizes currency input.
 * - Allows digits with optional decimal (comma or period)
 * - Limits decimal places to 2
 * - Normalizes comma to period
 * - Enforces max value if specified
 *
 * @param {string} raw - The raw input value
 * @param {Object} options - Options object
 * @param {number} [options.max] - Maximum allowed value
 * @returns {{ value: string, valid: boolean }} - Normalized value and validity
 */
function handleCurrencyInput(raw, options) {
    options = options || {};
    var max = options.max;

    if (raw === '') {
        return { value: '', valid: true };
    }

    if (!EURO_REGEX.test(raw)) {
        return { value: raw.slice(0, -1), valid: false };
    }

    var normalized = raw.replace(',', '.');

    if (max !== undefined) {
        var num = parseFloat(normalized);
        if (!isNaN(num) && num > max) {
            return { value: max.toFixed(2), valid: true };
        }
    }

    return { value: normalized, valid: true };
}

/**
 * Parses a currency value string to a number.
 * - Handles empty/null/undefined values
 * - Normalizes comma to period
 * - Returns defaultValue for invalid input
 *
 * @param {string|null|undefined} val - The value to parse
 * @param {*} defaultValue - Value to return if input is empty or invalid
 * @returns {number|*} - Parsed number or defaultValue
 */
function parseCurrencyValue(val, defaultValue) {
    if (val === '' || val === undefined || val === null) {
        return defaultValue;
    }
    var normalized = String(val).replace(',', '.');
    var num = parseFloat(normalized);
    return isNaN(num) ? defaultValue : num;
}

// Export for testing (CommonJS)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        handleCurrencyInput: handleCurrencyInput,
        parseCurrencyValue: parseCurrencyValue,
        EURO_REGEX: EURO_REGEX
    };
}