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
    'rejoinFigureTakeHomeBarEdge',
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
        ${[2, 4, 6].map((rate) => `<input type="radio" name="calcRate" value="${rate}"${rate === 6 ? ' checked' : ''}>`).join('')}
        <span id="resNetSalary"></span>
        <span id="resTaxWin"></span>
        <span id="resTotal"></span>
        <span id="taxWinInfo"
              data-income-tax-label="Tulumaksust"
              data-social-tax-label="Sotsiaalmaksust"></span>
        <svg id="rejoinFigure">
            ${fromTemplate(/<g id="rejoinFigureTaxWinInfo"[^>]*>/)}</g>
            ${fromTemplate(/<g id="rejoinFigureTaxWinLabel"[^>]*>/)}</g>
            ${FIGURE_SHAPES.map(shapeInTemplate).join('')}
            ${FIGURE_AMOUNTS.map(amountInTemplate).join('')}
            <text id="rejoinFigureTakeHomeLabel"></text>
            <text id="rejoinFigurePillarBaseLabel"></text>
        </svg>
        ${fromTemplate(/<span id="rejoinFigureSalary">[^<]*<\/span>/)}
    `;
    jest.isolateModules(() => require('./second-pillar-rejoin-calculator'));
}

const FIGURE_BARS = [
    'rejoinFigureTakeHomeBar',
    'rejoinFigureTakeHomeBarEdge',
    'rejoinFigurePillarBaseBar',
    'rejoinFigureTaxWinBar',
    'rejoinFigureTaxWinBarEdge'
];

function barHeights() {
    return FIGURE_BARS.map((id) => document.getElementById(id).getAttribute('height'));
}

function taxWinLabelPosition(svg) {
    return svg.querySelector('#rejoinFigureTaxWinLabel').getAttribute('transform');
}

function shown(id) {
    return document.getElementById(id).textContent.replace(/\s/g, ' ');
}

function taxWinBreakdown(id) {
    const breakdown = document.createElement('div');
    breakdown.innerHTML = document.getElementById(id).getAttribute('title');
    return [...breakdown.querySelectorAll('.rejoin-tax-win-breakdown > span')].map((cell) =>
        cell.textContent.replace(/\s/g, ' ')
    );
}

function typeSalary(value) {
    const salary = document.getElementById('calcSalary');
    salary.value = value;
    salary.dispatchEvent(new Event('input', { bubbles: true }));
    salary.dispatchEvent(new Event('change', { bubbles: true }));
}

function chooseRate(value) {
    const rate = document.querySelector(`input[name="calcRate"][value="${value}"]`);
    rate.checked = true;
    rate.dispatchEvent(new Event('change', { bubbles: true }));
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
    });

    it('recalculates when the salary changes, grouping thousands only from five digits', () => {
        typeSalary('3000');

        expect(shown('resNetSalary')).toBe('140 €');
        expect(shown('resTaxWin')).toBe('160 €');
        expect(shown('resTotal')).toBe('300 €');
    });

    it('starts from the 6% contribution that is checked in the page', () => {
        expect(fromTemplate(/<input[^>]*name="calcRate"[^>]*>/g)).toBeDefined();
        expect(template).toMatch(/\$rate === 6 \? ' checked'/);
    });

    it('keeps the take-home amount inside its bar when a smaller contribution makes the bar shorter', () => {
        const amountY = () => Number(document.getElementById('rejoinFigureTakeHomeLabel').getAttribute('y'));
        const bar = document.getElementById('rejoinFigureTakeHomeBar');
        const barTop = () => Number(bar.getAttribute('y'));

        expect(amountY()).toBeCloseTo(413, 0);

        chooseRate('2');

        expect(amountY()).toBeGreaterThan(barTop());
        expect(amountY()).toBeLessThan(470);
        expect(document.getElementById('rejoinFigurePillarBaseLabel').getAttribute('y')).toBe(String(amountY()));
    });

    it('recalculates for the contribution the visitor picks, the state part staying the same', () => {
        chooseRate('2');

        expect(shown('resNetSalary')).toBe('31 €');
        expect(shown('resTaxWin')).toBe('89 €');
        expect(shown('resTotal')).toBe('120 €');
        expect(shown('rejoinFigurePillarTotal')).toBe('120 €');
    });

    it('breaks the tax win down into what comes from social tax and from income tax, larger part first', () => {
        expect(taxWinBreakdown('taxWinInfo')).toEqual(['Sotsiaalmaksust', '80 €', 'Tulumaksust', '26 €']);
    });

    it('breaks the tax win down on the green bar of the figure too, following the salary', () => {
        typeSalary('3000');

        expect(taxWinBreakdown('rejoinFigureTaxWinInfo')).toEqual(['Sotsiaalmaksust', '120 €', 'Tulumaksust', '40 €']);
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

    it('focuses the salary on arrival on a desktop, so the field shows it can be typed into', () => {
        window.matchMedia = jest.fn(() => ({ matches: true }));
        renderCalculator();

        expect(document.activeElement).toBe(document.getElementById('calcSalary'));
        delete window.matchMedia;
    });

    it('shows a caret after the example on arrival, and the first digit typed replaces the example', () => {
        window.matchMedia = jest.fn(() => ({ matches: true }));
        renderCalculator();
        const salary = document.getElementById('calcSalary');

        expect([salary.selectionStart, salary.selectionEnd]).toEqual([4, 4]);

        salary.dispatchEvent(new InputEvent('beforeinput', { inputType: 'insertText', data: '3' }));
        expect(salary.value).toBe('');

        salary.dispatchEvent(new InputEvent('beforeinput', { inputType: 'insertText', data: '0' }));
        salary.value = '30';
        expect(salary.value).toBe('30');
        delete window.matchMedia;
    });

    it('lets the visitor delete digits of the example after arrival instead of clearing it', () => {
        window.matchMedia = jest.fn(() => ({ matches: true }));
        renderCalculator();
        const salary = document.getElementById('calcSalary');

        salary.dispatchEvent(new InputEvent('beforeinput', { inputType: 'deleteContentBackward' }));

        expect(salary.value).toBe('2000');
        delete window.matchMedia;
    });

    it('leaves the focus alone on a touch screen, where it would open the keyboard', () => {
        window.matchMedia = jest.fn(() => ({ matches: false }));
        renderCalculator();

        expect(document.activeElement).not.toBe(document.getElementById('calcSalary'));
        delete window.matchMedia;
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
        ['resNetSalary', 'resTaxWin', 'resTotal'].forEach((id) => {
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

    it('names the gross salary under the figure, following the calculator', () => {
        typeSalary('3000');

        expect(shown('rejoinFigureSalary')).toBe('3000 €');
    });

    it('paints the figure for the same example before the script runs', () => {
        [...FIGURE_AMOUNTS, 'rejoinFigureSalary'].forEach((id) => {
            expect(paintedInTemplate(id)).toBe(shown(id));
        });
        FIGURE_SHAPES.forEach((id) => {
            const painted = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            painted.innerHTML = shapeInTemplate(id);
            expect(geometry(painted.firstChild)).toEqual(geometry(document.getElementById(id)));
        });
        const painted = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        painted.innerHTML = fromTemplate(/<g id="rejoinFigureTaxWinLabel"[^>]*>/) + '</g>';
        expect(taxWinLabelPosition(painted)).toBe(taxWinLabelPosition(document));
    });

    it('keeps the take-home bar as tall as the base of the II pillar column, with the tax win filling the rest and its label in the middle', () => {
        typeSalary('3000');

        expect(
            ['rejoinFigureTakeHomeBar', 'rejoinFigurePillarBaseBar', 'rejoinFigureTaxWinBar'].map((id) =>
                document.getElementById(id).getAttribute('height')
            )
        ).toEqual(['130.7', '130.7', '149.3']);
        expect(taxWinLabelPosition(document)).toBe('translate(0 264.7)');
    });

    it.each(['', '0', '2', '20', '200', '999'])(
        'shows the page example in the figure for a salary of "%s", below 1000 €, as while the visitor is still typing',
        (typed) => {
            typeSalary(typed);

            [...FIGURE_AMOUNTS, 'rejoinFigureSalary'].forEach((id) => {
                expect(shown(id)).toBe(paintedInTemplate(id));
            });
            expect(barHeights()).toEqual(['131.6', '12', '131.6', '148.4', '12']);
        }
    );

    it('keeps calculating the visitor\'s own salary below 1000 €, the example staying in the figure', () => {
        typeSalary('500');

        expect(shown('resTotal')).toBe('50 €');
        expect(taxWinBreakdown('taxWinInfo')).toEqual(['Sotsiaalmaksust', '20 €', 'Tulumaksust', '0 €']);
        expect(taxWinBreakdown('rejoinFigureTaxWinInfo')).toEqual(['Sotsiaalmaksust', '80 €', 'Tulumaksust', '26 €']);
    });

    it('follows the salary in the figure from 1000 €', () => {
        typeSalary('1000');

        expect(shown('rejoinFigureSalary')).toBe('1000 €');
        expect(shown('rejoinFigurePillarTotal')).toBe('100 €');
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
