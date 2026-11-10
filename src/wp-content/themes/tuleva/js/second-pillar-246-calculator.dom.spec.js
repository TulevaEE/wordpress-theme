/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

const template = fs.readFileSync(path.join(__dirname, '../templates/second-pillar-246-content.php'), 'utf8');

function renderPage() {
    document.body.innerHTML = template;
    let calculator;
    jest.isolateModules(() => {
        require('./salary-arrival-focus');
        calculator = require('./second-pillar-246-calculator');
    });
    return calculator;
}

function shown(id) {
    return document.getElementById(id).textContent.replace(/\s/g, ' ');
}

function thousandsBelow(value) {
    return Math.floor(value / 1000) * 1000;
}

function shownReturn(calculator, wealth) {
    return calculator.thousandsAddingUpTo([wealth.fromNetSalary, wealth.fromIncomeTax, wealth.fromSocialTax, wealth.fromReturns])[3];
}

function eur(value) {
    return value.toLocaleString('et-EE').replace(/\s/g, ' ') + ' €';
}

function type(id, value) {
    const field = document.getElementById(id);
    field.value = String(value);
    field.dispatchEvent(new Event('input'));
}

function rateOptions() {
    return [...document.querySelectorAll('#segRate input[type="radio"]')];
}

function pickRate(rate) {
    rateOptions().find((option) => option.value === String(rate)).click();
}

function card() {
    return ['cNet', 'cTax', 'cState', 'cInflow'].map(shown);
}

function pressedRates() {
    return rateOptions().filter((option) => option.checked).map((option) => option.value);
}

function pickColumn(rate) {
    document.querySelector(`.vbar[data-r="${rate}"]`).click();
}

function dimmedRates() {
    return [...document.querySelectorAll('.vbar.dim')].map((bar) => bar.dataset.r);
}

describe('II pillar 2-4-6 page calculators', () => {
    it('shows the 3000 € example at 6% on arrival', () => {
        renderPage();

        expect(card()).toEqual(['140 €', '40 €', '120 €', '300 €']);
    });

    it('recomputes the card and presses only the rate picked', () => {
        renderPage();

        pickRate(2);

        expect(pressedRates()).toEqual(['2']);
        expect(card()).toEqual(['47 €', '13 €', '120 €', '180 €']);
    });

    it('focuses the salary on arrival on a desktop, so the field shows it can be typed into', () => {
        window.matchMedia = jest.fn(() => ({ matches: true }));
        renderPage();

        expect(document.activeElement).toBe(document.getElementById('gross'));
        delete window.matchMedia;
    });

    it('reads a salary typed with a thousands space, as the rejoin calculator does', () => {
        renderPage();

        type('gross', '3 000');

        expect(card()).toEqual(['140 €', '40 €', '120 €', '300 €']);
    });

    it('shows no income tax win when the basic exemption covers the salary', () => {
        renderPage();

        type('gross', 700);

        expect(card()).toEqual(['42 €', '0 €', '28 €', '70 €']);
    });

    it('caps the salary at 99 999 €, as the rejoin calculator does', () => {
        renderPage();

        type('gross', 150000);
        document.getElementById('gross').dispatchEvent(new Event('change'));

        expect(shown('cInflow')).toBe('10 000 €');
        expect(document.getElementById('gross').value).toBe('99999');
    });

    it('shows zeros, not NaN, when the salary is emptied', () => {
        renderPage();

        type('gross', '');

        expect(card()).toEqual(['0 €', '0 €', '0 €', '0 €']);
        expect(document.getElementById('wealth').innerHTML).not.toContain('NaN');
        expect(document.getElementById('vara-kasv').innerHTML).not.toContain('NaN');
    });

    it('paints the same example in the page before the script runs', () => {
        document.body.innerHTML = template;
        const painted = card();

        renderPage();

        expect(painted).toEqual(card());
    });

    it('shows the legend in whole thousands that add up to the column of the rate picked', () => {
        const { projectWealth } = renderPage();

        type('ret', 7);

        const euros = (id) => Number(shown(id).replace(/[^0-9]/g, ''));
        const wealth = projectWealth(3000, 6, 35, 0.07);
        const legend = ['lNet', 'lTax', 'lState', 'lGrow'].map(euros);
        const exact = [wealth.fromNetSalary, wealth.fromIncomeTax, wealth.fromSocialTax, wealth.fromReturns];
        expect(legend.reduce((sum, part) => sum + part, 0)).toBe(euros('vv6'));
        expect(legend.every((part, index) => part % 1000 === 0 && Math.abs(part - exact[index]) < 1000)).toBe(true);
    });

    it('highlights 6% in the growth calculator whatever the card above picks', () => {
        renderPage();

        pickRate(2);

        expect(dimmedRates()).toEqual(['2', '4']);
    });

    it('highlights the column clicked and leaves the card above as it was', () => {
        renderPage();

        pickColumn(4);

        expect([dimmedRates(), pressedRates()]).toEqual([['2', '6'], ['6']]);
    });

    it('draws the columns as shares of the tallest, so their heights compare like the totals', () => {
        renderPage();

        const heights = [2, 4, 6].map((rate) => parseFloat(document.getElementById('vc' + rate).style.height));
        expect(heights[0]).toBeCloseTo(60, 1);
        expect(heights[1]).toBeCloseTo(80, 1);
        expect(heights[2]).toBe(100);
        expect(document.getElementById('vc6').style.height).toMatch(/%$/);
    });

    it('projects until 65 from the age typed', () => {
        const { projectWealth } = renderPage();

        type('age', 62);

        expect(shown('vv6')).toBe(eur(thousandsBelow(projectWealth(3000, 6, 62, 0).total)));
    });

    it('keeps the last full age while one is being typed, so the figures do not jump', () => {
        const { projectWealth } = renderPage();

        type('age', 4);

        expect(shown('vv6')).toBe(eur(thousandsBelow(projectWealth(3000, 6, 35, 0).total)));
    });

    it('puts an age above 64 back to 64 when the field is left', () => {
        const { projectWealth } = renderPage();

        type('age', 70);
        document.getElementById('age').dispatchEvent(new Event('change'));

        expect(document.getElementById('age').value).toBe('64');
        expect(shown('vv6')).toBe(eur(thousandsBelow(projectWealth(3000, 6, 64, 0).total)));
    });

    it('puts an age below 18 back to 18 when the field is left', () => {
        renderPage();

        type('age', 12);
        document.getElementById('age').dispatchEvent(new Event('change'));

        expect(document.getElementById('age').value).toBe('18');
    });

    it('brings back the last full age when the field is left empty', () => {
        renderPage();

        type('age', 47);
        type('age', '');
        document.getElementById('age').dispatchEvent(new Event('change'));

        expect(document.getElementById('age').value).toBe('47');
    });

    it('shows the column totals floored to whole thousands, so a projection never reads higher than it is', () => {
        renderPage();

        expect(['vv2', 'vv4', 'vv6'].map(shown)).toEqual(['104 000 €', '138 000 €', '173 000 €']);
    });

    it('leaves the return out of the breakdown while it adds nothing, and lists it once it does', () => {
        renderPage();
        const returnLine = () => document.getElementById('lGrow').parentElement.hidden;

        const atZero = returnLine();
        type('ret', 5);

        expect([atZero, returnLine()]).toEqual([true, false]);
    });

    it('lists a loss in the breakdown as a negative return', () => {
        renderPage();

        type('ret', -5);

        expect([document.getElementById('lGrow').parentElement.hidden, /^[−-]/.test(shown('lGrow'))]).toEqual([false, true]);
    });

    it('totals the breakdown to the column clicked', () => {
        renderPage();

        pickColumn(4);

        expect(shown('lTotal')).toBe(shown('vv4'));
    });

    it('shows no growth from returns at the default zero return', () => {
        renderPage();

        expect(shown('lGrow')).toBe('0 €');
    });

    it('splits out what the return picked adds', () => {
        const calculator = renderPage();
        const { projectWealth } = calculator;

        type('ret', 5);

        expect(shown('lGrow')).toBe(eur(shownReturn(calculator, projectWealth(3000, 6, 35, 0.05))));
    });

    it('sets the return to the historic 7% of stocks when that line is picked, as the child savings calculator does', () => {
        const calculator = renderPage();
        const { projectWealth } = calculator;

        document.querySelector('.historic-return-rate').click();

        expect(document.getElementById('ret').value).toBe('7');
        expect(shown('lGrow')).toBe(eur(shownReturn(calculator, projectWealth(3000, 6, 35, 0.07))));
    });

    it('names the age field and the return slider by their labels', () => {
        renderPage();

        expect(document.getElementById('age').labels).toHaveLength(1);
        expect(document.getElementById('ret').labels).toHaveLength(1);
    });

    it('groups the rate buttons under their label', () => {
        renderPage();

        const rates = document.getElementById('segRate');
        expect(rates.getAttribute('role')).toBe('group');
        expect(rates.getAttribute('aria-labelledby')).toBe('rateLbl');
        expect(document.getElementById('rateLbl').tagName).toBe('SPAN');
    });

    it('offers the rates as one radio group with a label on each option', () => {
        renderPage();

        expect(rateOptions().map((option) => [option.name, option.value, option.labels.length])).toEqual([
            ['rate', '2', 1],
            ['rate', '4', 1],
            ['rate', '6', 1]
        ]);
        expect(pressedRates()).toEqual(['6']);
    });

    it('announces the card figures politely when they change', () => {
        renderPage();

        const region = document.getElementById('cNet').closest('[aria-live="polite"]');
        expect(['cTax', 'cState', 'cInflow'].every((id) => region.contains(document.getElementById(id)))).toBe(true);
    });

    it('names the columns as a group and hides the chevrons from screen readers', () => {
        renderPage();

        expect(document.getElementById('vbars').getAttribute('role')).toBe('group');
        expect([...document.querySelectorAll('.chev')].every((chevron) => chevron.getAttribute('aria-hidden') === 'true')).toBe(true);
    });
});
