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
        calculator = require('./second-pillar-246-calculator');
    });
    return calculator;
}

function shown(id) {
    return document.getElementById(id).textContent.replace(/\s/g, ' ');
}

function eur(value) {
    return value.toLocaleString('et-EE').replace(/\s/g, ' ') + ' €';
}

function type(id, value) {
    const field = document.getElementById(id);
    field.value = String(value);
    field.dispatchEvent(new Event('input'));
}

function pickRate(rate) {
    document.querySelector(`#segRate button[data-r="${rate}"]`).click();
}

function card() {
    return ['cNet', 'cPil', 'cState', 'cInflow', 'cTax'].map(shown);
}

function pressedRates() {
    return [...document.querySelectorAll('#segRate button[aria-pressed="true"]')].map((button) => button.dataset.r);
}

function dimmedRates() {
    return [...document.querySelectorAll('.vbar.dim')].map((bar) => bar.dataset.r);
}

describe('II pillar 2-4-6 page calculators', () => {
    it('shows the 3000 € example at 6% on arrival', () => {
        renderPage();

        expect(card()).toEqual(['140 €', '180 €', '120 €', '300 €', '480 €']);
    });

    it('recomputes the card and presses only the rate picked', () => {
        renderPage();

        pickRate(2);

        expect(pressedRates()).toEqual(['2']);
        expect(card()).toEqual(['47 €', '60 €', '120 €', '180 €', '156 €']);
    });

    it('shows no income tax win when the basic exemption covers the salary', () => {
        renderPage();

        type('gross', 700);

        expect(card()).toEqual(['42 €', '42 €', '28 €', '70 €', '0 €']);
    });

    it('caps the salary at 99 999 €, as the rejoin calculator does', () => {
        renderPage();

        type('gross', 150000);
        document.getElementById('gross').dispatchEvent(new Event('change'));

        expect(shown('cPil')).toBe('6000 €');
        expect(document.getElementById('gross').value).toBe('99999');
    });

    it('shows zeros, not NaN, when the salary is emptied', () => {
        renderPage();

        type('gross', '');

        expect(card()).toEqual(['0 €', '0 €', '0 €', '0 €', '0 €']);
        expect(document.getElementById('wealth').innerHTML).not.toContain('NaN');
        expect(document.getElementById('dec').innerHTML).not.toContain('NaN');
    });

    it('paints the same example in the page before the script runs', () => {
        document.body.innerHTML = template;
        const painted = card();

        renderPage();

        expect(painted).toEqual(card());
    });

    it('adds the legend up to the column of the rate picked', () => {
        renderPage();

        type('ret', 7);

        const euros = (id) => Number(shown(id).replace(/[^0-9]/g, ''));
        expect(euros('lNet') + euros('lState') + euros('lTax') + euros('lGrow')).toBe(euros('vv6'));
    });

    it('labels the chart axis in thousands for large totals and in euros for small ones', () => {
        renderPage();
        const axis = () => [...document.querySelectorAll('#chart .cy')].map((label) => label.textContent).slice(1);

        expect(axis().every((label) => label.endsWith(' k€'))).toBe(true);

        type('gross', 300);
        type('age', 62);

        expect(axis().every((label) => /^[0-9 ]+ €$/.test(label.replace(/\s/g, ' ')))).toBe(true);
    });

    it('dims the columns of the rates not picked', () => {
        renderPage();

        pickRate(4);

        expect(dimmedRates()).toEqual(['2', '6']);
    });

    it('draws the columns as shares of the tallest, so their heights compare like the totals', () => {
        renderPage();

        const heights = [2, 4, 6].map((rate) => parseFloat(document.getElementById('vc' + rate).style.height));
        expect(heights[0]).toBeCloseTo(60, 1);
        expect(heights[1]).toBeCloseTo(80, 1);
        expect(heights[2]).toBe(100);
        expect(document.getElementById('vc6').style.height).toMatch(/%$/);
    });

    it('draws one line per rate and the picked one thickest', () => {
        renderPage();

        pickRate(4);

        const widths = [...document.querySelectorAll('#chart polyline')].map((line) => line.getAttribute('stroke-width'));
        expect(widths).toEqual(['2', '3.6', '2']);
    });

    it('projects until 65 from the age picked', () => {
        const { projectWealth } = renderPage();

        type('age', 62);

        expect(shown('ageVal')).toBe('62');
        expect(shown('vv6')).toBe(eur(projectWealth(3000, 6, 62, 0).total));
    });

    it('shows no growth from returns at the default zero return', () => {
        renderPage();

        expect(shown('retVal')).toBe('0%');
        expect(shown('lGrow')).toBe('0 €');
    });

    it('splits out what the return picked adds, written with a decimal comma', () => {
        const { projectWealth } = renderPage();

        type('ret', 7.5);

        expect(shown('retVal')).toBe('7,5%');
        expect(shown('lGrow')).toBe(eur(projectWealth(3000, 6, 35, 0.075).fromReturns));
    });

    it('names the age and return sliders by their labels', () => {
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

    it('announces the card figures politely when they change', () => {
        renderPage();

        const region = document.getElementById('cNet').closest('[aria-live="polite"]');
        expect(['cPil', 'cState', 'cInflow', 'cTax'].every((id) => region.contains(document.getElementById(id)))).toBe(true);
    });

    it('names the columns as a group and hides the chevrons from screen readers', () => {
        renderPage();

        expect(document.getElementById('vbars').getAttribute('role')).toBe('group');
        expect([...document.querySelectorAll('.chev')].every((chevron) => chevron.getAttribute('aria-hidden') === 'true')).toBe(true);
    });
});
