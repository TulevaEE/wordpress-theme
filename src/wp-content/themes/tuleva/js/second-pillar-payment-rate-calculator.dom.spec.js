/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

const template = fs.readFileSync(
    path.join(__dirname, '../templates/components/second-pillar-payment-rate-calculator.php'),
    'utf8'
);

function renderCalculator() {
    document.body.innerHTML = `
        <div class="payment-rate-calculator">
            <div class="payment-rate-calculator__salary">${template.match(/<input type="text" id="paymentRateSalary"[^>]*>/)[0]}<span>€</span></div>
            <legend id="paymentRateLabel">Sinu panus brutopalgast</legend>
            ${[2, 4, 6].map((rate) => `<input type="radio" name="paymentRate" id="paymentRate${rate}" value="${rate}"${rate === 6 ? ' checked' : ''}>`).join('')}
            <p role="status"></p>
            <dd data-figure="netSalary" data-label="Netopalk järgmisest aastast"></dd>
            <dd data-figure="contribution" data-label="Sissemakse kuus"></dd>
            <span data-tax-win-note data-tax-win="jõuab sinu II samba kontole" data-tax-free="Maksuvaba tulu katab kogu palga"></span>
            <dd data-figure="yearlyTaxWin" data-label="Tulumaksuvõit aastas"></dd>
        </div>
    `;
    jest.isolateModules(() => {
        require('./salary-arrival-focus');
        require('./second-pillar-payment-rate-calculator');
    });
}

function figures() {
    return ['netSalary', 'contribution', 'yearlyTaxWin'].map(
        (figure) => document.querySelector(`[data-figure="${figure}"]`).textContent
    );
}

function typeSalary(value) {
    const salary = document.getElementById('paymentRateSalary');
    salary.value = value;
    salary.dispatchEvent(new Event('input', { bubbles: true }));
}

describe('II pillar payment rate hero calculator', () => {
    beforeEach(() => {
        window.matchMedia = jest.fn(() => ({ matches: false }));
        renderCalculator();
    });

    afterEach(() => {
        delete window.matchMedia;
        jest.useRealTimers();
    });

    it('starts from the 2000 € example at the 6% the page checks', () => {
        expect(figures()).toEqual(['1595 €', '120 €', '316 €']);
    });

    it('swaps the figures for the rate the visitor clicks', () => {
        const rate = document.getElementById('paymentRate2');
        rate.checked = true;
        rate.dispatchEvent(new Event('change', { bubbles: true }));

        expect(figures()).toEqual(['1657 €', '40 €', '105 €']);
    });

    it('keeps the digits of a salary pasted from a payslip', () => {
        typeSalary('2 500,00 €');

        expect([document.getElementById('paymentRateSalary').value, ...figures()])
            .toEqual(['2500', '1955 €', '150 €', '396 €']);
    });

    it('says the tax-free allowance covers a salary it covers, and wins nothing on it', () => {
        typeSalary('650');

        expect([
            document.querySelector('[data-tax-win-note]').textContent,
            document.querySelector('.payment-rate-calculator').classList.contains('is-tax-free'),
            figures()[2]
        ]).toEqual(['Maksuvaba tulu katab kogu palga', true, '0 €']);
    });

    it('says where the tax win goes again once the salary is above the allowance', () => {
        typeSalary('650');
        typeSalary('2000');

        expect(document.querySelector('[data-tax-win-note]').textContent).toBe('jõuab sinu II samba kontole');
    });

    it('reads the labelled figures to a screen reader once the visitor stops typing', () => {
        jest.useFakeTimers();
        typeSalary('2000');
        jest.advanceTimersByTime(500);

        expect(document.querySelector('[role="status"]').textContent).toBe(
            'Sinu panus brutopalgast 6%: Netopalk järgmisest aastast 1595 €, Sissemakse kuus 120 €, Tulumaksuvõit aastas 316 €.'
        );
    });

    it('keeps the status line quiet on arrival, before the visitor changes anything', () => {
        jest.useFakeTimers();
        renderCalculator();
        jest.advanceTimersByTime(500);

        expect(document.querySelector('[role="status"]').textContent).toBe('');
    });

    it('puts the caret in the salary when the visitor clicks anywhere in its field, the euro sign included', () => {
        document.querySelector('.payment-rate-calculator__salary span').click();

        expect(document.activeElement).toBe(document.getElementById('paymentRateSalary'));
    });

    it('focuses the salary on arrival on a desktop', () => {
        window.matchMedia = jest.fn(() => ({ matches: true }));
        renderCalculator();

        expect(document.activeElement).toBe(document.getElementById('paymentRateSalary'));
    });
});
