/**
 * @jest-environment jsdom
 */

function renderCalculator() {
    document.body.innerHTML = `
        <input id="calcSalary" type="text" inputmode="numeric" value="2000">
        <span id="resNetSalary"></span>
        <span id="resTaxWin"></span>
        <span id="resTotal"></span>
        <span id="resTenYears"></span>
        <span id="taxWinInfo"
              data-income-tax-label="Tulumaksust"
              data-social-tax-label="Sotsiaalmaksust"></span>
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
    afterEach(() => {
        delete window.bootstrap;
    });

    it('starts from a 2000 € salary the visitor can delete like any value', () => {
        expect(document.getElementById('calcSalary').value).toBe('2000');
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

    it('breaks the tax win down into what comes from income tax and from social tax, one per row', () => {
        const breakdown = document.createElement('div');
        breakdown.innerHTML = document.getElementById('taxWinInfo').getAttribute('title');

        expect(
            [...breakdown.querySelectorAll('.rejoin-tax-win-breakdown > span')].map((cell) =>
                cell.textContent.replace(/\s/g, ' ')
            )
        ).toEqual(['Tulumaksust:', '26 €', 'Sotsiaalmaksust:', '80 €']);
    });

    it('updates an initialized tooltip without putting markup back into the native title', () => {
        const taxWinInfo = document.getElementById('taxWinInfo');
        const tooltip = { setContent: jest.fn() };
        window.bootstrap = { Tooltip: { getInstance: (element) => (element === taxWinInfo ? tooltip : null) } };
        taxWinInfo.removeAttribute('title');

        typeSalary('3000');

        expect(taxWinInfo.hasAttribute('title')).toBe(false);
        expect(tooltip.setContent).toHaveBeenLastCalledWith({
            '.tooltip-inner': expect.stringContaining('rejoin-tax-win-breakdown')
        });
    });

    it('caps a mistyped salary at 99 999 €', () => {
        typeSalary('1000000');

        expect(shown('resTotal')).toBe('10 000 €');
        expect(document.getElementById('calcSalary').value).toBe('99999');
    });

    it('selects the whole salary on focus so typing replaces the example', () => {
        jest.useFakeTimers();
        const salary = document.getElementById('calcSalary');

        salary.dispatchEvent(new Event('focus'));
        jest.runAllTimers();

        expect([salary.selectionStart, salary.selectionEnd]).toEqual([0, 4]);
        jest.useRealTimers();
    });

    it('recalculates for the salary the browser restores when the visitor comes back to the page', () => {
        document.getElementById('calcSalary').value = '3000';

        window.dispatchEvent(new Event('pageshow'));

        expect(shown('resTotal')).toBe('300 €');
    });

    it('reads an emptied salary field as zero', () => {
        typeSalary('');

        expect(shown('resTotal')).toBe('0 €');
    });

    it('reads a salary typed with a thousands space', () => {
        typeSalary('3 000');

        expect(shown('resTotal')).toBe('300 €');
    });

    it('reads a typed zero as zero', () => {
        typeSalary('0');

        expect(shown('resTotal')).toBe('0 €');
    });
});
