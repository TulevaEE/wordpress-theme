/**
 * @jest-environment jsdom
 */

function renderCalculator() {
    document.body.innerHTML = `
        <input id="calcSalary" type="number" value="2000" max="99999">
        <span id="resNetSalary"></span>
        <span id="resTaxWin"></span>
        <span id="resTotal"></span>
        <span id="resTenYears"></span>
        <span id="taxWinInfo"
              data-income-tax-label="Tulumaksuvõit"
              data-social-tax-label="4% sotsiaalmaksust"></span>
    `;
    jest.isolateModules(() => require('./second-pillar-rejoin-calculator'));
}

function shown(id) {
    return document.getElementById(id).textContent.replace(/\s/g, ' ');
}

function typeSalary(value) {
    const salary = document.getElementById('calcSalary');
    salary.value = value;
    salary.dispatchEvent(new Event('input', { bubbles: true }));
    salary.dispatchEvent(new Event('change', { bubbles: true }));
}

describe('II pillar rejoin calculator on the page', () => {
    beforeEach(renderCalculator);

    it('shows the 2000 € example as soon as the page loads', () => {
        expect(shown('resNetSalary')).toBe('94 €');
        expect(shown('resTaxWin')).toBe('106 €');
        expect(shown('resTotal')).toBe('200 €');
        expect(shown('resTenYears')).toBe('24 000 €');
    });

    it('recalculates when the salary changes, grouping thousands only from five digits', () => {
        typeSalary('3000');

        expect(shown('resNetSalary')).toBe('140 €');
        expect(shown('resTaxWin')).toBe('160 €');
        expect(shown('resTotal')).toBe('300 €');
        expect(shown('resTenYears')).toBe('36 000 €');
    });

    it('explains the tax win as the income tax saving and the 4% from social tax', () => {
        expect(document.getElementById('taxWinInfo').getAttribute('title').replace(/\s/g, ' ')).toBe(
            'Tulumaksuvõit: 26 € · 4% sotsiaalmaksust: 80 €'
        );
    });

    it('caps a mistyped salary at 99 999 €', () => {
        typeSalary('1000000');

        expect(shown('resTotal')).toBe('10 000 €');
        expect(document.getElementById('calcSalary').value).toBe('99999');
    });

    it('reads an emptied salary field as zero', () => {
        typeSalary('');

        expect(shown('resTotal')).toBe('0 €');
    });
});
