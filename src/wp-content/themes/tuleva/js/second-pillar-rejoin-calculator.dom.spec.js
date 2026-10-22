/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

const template = fs.readFileSync(
    path.join(__dirname, '../templates/second-pillar-rejoin-content.php'),
    'utf8'
);

function fromTemplate(pattern) {
    return template.match(pattern)[0];
}

function paintedInTemplate(id) {
    return fromTemplate(new RegExp('id="' + id + '">[^<]*'))
        .replace('id="' + id + '">', '')
        .replace(/&nbsp;/g, ' ')
        .replace(/&euro;/g, '€');
}

const FIGURE_AMOUNTS = [
    'rejoinFigureContribution',
    'rejoinFigureIncomeTax',
    'rejoinFigureTakeHome',
    'rejoinFigurePillarBase',
    'rejoinFigureTaxWin',
    'rejoinFigureTakeHomeTotal',
    'rejoinFigurePillarTotal'
];

const FIGURE_SHAPES = [
    'rejoinFigureTakeHomeArrow',
    'rejoinFigureTakeHomeArrowHead',
    'rejoinFigureTakeHomeBar',
    'rejoinFigureTaxWinBar',
    'rejoinFigureTaxWinBarEdge',
    'rejoinFigurePillarBaseBar'
];

function shapeInTemplate(id) {
    return fromTemplate(new RegExp('<(rect|path) id="' + id + '"[^>]*>'));
}

function amountInTemplate(id) {
    return fromTemplate(new RegExp('<(text|tspan) id="' + id + '"[^>]*>[^<]*</(text|tspan)>'));
}

function geometry(element) {
    return ['y', 'height', 'd'].map((name) => element.getAttribute(name));
}

function renderCalculator() {
    document.body.innerHTML = `
        ${fromTemplate(/<input[^>]*id="calcSalary"[^>]*>/)}
        <span id="resNetSalary"></span>
        <span id="resTaxWin"></span>
        <span id="resTotal"></span>
        <span id="resTenYears"></span>
        <span id="taxWinInfo"
              data-income-tax-label="Tulumaksust"
              data-social-tax-label="Sotsiaalmaksust"></span>
        <svg id="rejoinFigure">
            ${FIGURE_SHAPES.map(shapeInTemplate).join('')}
            ${FIGURE_AMOUNTS.map(amountInTemplate).join('')}
        </svg>
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

    it('starts from a 2000 € salary, where your part stays under 100 € and the tax win goes over it', () => {
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

    it('breaks the tax win down into what comes from social tax and from income tax, larger part first', () => {
        const breakdown = document.createElement('div');
        breakdown.innerHTML = document.getElementById('taxWinInfo').getAttribute('title');

        expect(
            [...breakdown.querySelectorAll('.rejoin-tax-win-breakdown > span')].map((cell) =>
                cell.textContent.replace(/\s/g, ' ')
            )
        ).toEqual(['Sotsiaalmaksust', '80 €', 'Tulumaksust', '26 €']);
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

    it('paints the same example in the page before the script runs', () => {
        ['resNetSalary', 'resTaxWin', 'resTotal', 'resTenYears'].forEach((id) => {
            expect(paintedInTemplate(id)).toBe(shown(id));
        });
    });

    it('shows the same split in the figure, take-home pay against the II pillar', () => {
        typeSalary('3000');

        expect(FIGURE_AMOUNTS.map(shown)).toEqual([
            '180 €',
            '40 €',
            '140 €',
            '140 €',
            '160 €',
            '140 €',
            '300 €'
        ]);
    });

    it('paints the figure for the same example before the script runs', () => {
        FIGURE_AMOUNTS.forEach((id) => {
            expect(paintedInTemplate(id)).toBe(shown(id));
        });
        FIGURE_SHAPES.forEach((id) => {
            const painted = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            painted.innerHTML = shapeInTemplate(id);
            expect(geometry(painted.firstChild)).toEqual(geometry(document.getElementById(id)));
        });
    });

    it('keeps the take-home bar as tall as the base of the II pillar column, with the tax win filling the rest', () => {
        typeSalary('500');

        expect(
            ['rejoinFigureTakeHomeBar', 'rejoinFigurePillarBaseBar', 'rejoinFigureTaxWinBar'].map((id) =>
                document.getElementById(id).getAttribute('height')
            )
        ).toEqual(['168', '168', '112']);
    });

    it('leaves the figure bars as they were for a zero salary', () => {
        typeSalary('0');

        expect(document.getElementById('rejoinFigureTakeHomeBar').getAttribute('height')).toBe('131.6');
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
