/**
 * @jest-environment jsdom
 */

function renderCalculator() {
    document.body.innerHTML = `
        <input id="calcSalary" type="number" placeholder="2000" max="99999">
        <span id="resNetSalary"></span>
        <span id="resTaxWin"></span>
        <span id="resTotal"></span>
        <span id="resTenYears"></span>
        <span id="taxWinInfo"
              data-income-tax-label="Tulumaksuvõit"
              data-social-tax-label="Sotsiaalmaksust (4% brutopalgast)"></span>
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

    it('shows the 2000 € placeholder example while the salary field is empty', () => {
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

    it('breaks the tax win down into the income tax saving and the part of social tax, one per row', () => {
        const breakdown = document.createElement('div');
        breakdown.innerHTML = document.getElementById('taxWinInfo').getAttribute('title');

        expect(
            [...breakdown.querySelectorAll('.rejoin-tax-win-breakdown > span')].map((cell) =>
                cell.textContent.replace(/\s/g, ' ')
            )
        ).toEqual(['Tulumaksuvõit:', '26 €', 'Sotsiaalmaksust (4% brutopalgast):', '80 €']);
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

    it('goes back to the 2000 € example when the salary field is emptied', () => {
        typeSalary('3000');
        typeSalary('');

        expect(shown('resTotal')).toBe('200 €');
    });

    it('reads a typed zero as zero', () => {
        typeSalary('0');

        expect(shown('resTotal')).toBe('0 €');
    });
});
