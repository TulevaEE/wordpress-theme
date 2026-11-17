/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

function loadScript(relativePath) {
    window.eval(fs.readFileSync(path.join(__dirname, relativePath), 'utf8'));
}

function addCookieYesBar(height) {
    const bar = document.createElement('div');
    bar.className = 'cky-consent-container cky-classic-bottom';
    Object.defineProperty(bar, 'offsetHeight', { value: height });
    document.body.appendChild(bar);
    return bar;
}

function bottomScrollPadding() {
    return document.documentElement.style.getPropertyValue('--fixed-bottom-height');
}

describe('scrolling', () => {
    let cookieYesBar;

    beforeAll(async () => {
        document.body.innerHTML =
            '<header class="header"></header>' +
            '<a id="to-calculator" href="#kalkulaator">Arvuta</a>' +
            '<section id="kalkulaator"></section>';
        cookieYesBar = addCookieYesBar(273);

        Element.prototype.scrollIntoView = jest.fn();
        window.BroadcastChannel = class {
            postMessage() {}
        };
        window.matchMedia = () => ({ matches: false, addEventListener() {} });

        loadScript('vendor/jquery-3.7.1.min.js');
        window.$.fn.unslider = window.$.fn.popover = window.$.fn.tooltip = function () {
            return this;
        };
        loadScript('vendor/animatedModal.min.js');
        loadScript('main.js');

        await new Promise((resolve) => window.$(resolve));
    });

    it('leaves the scroll animation to CSS, which turns it off for visitors who ask for reduced motion', () => {
        document.getElementById('to-calculator').click();

        expect(Element.prototype.scrollIntoView.mock.calls).toEqual([[]]);
    });

    it('keeps focused elements clear of the CookieYes bar while the bar is shown', () => {
        expect(bottomScrollPadding()).toBe('273px');
    });

    it('stops reserving room once the visitor has answered and the bar is hidden', () => {
        cookieYesBar.classList.add('cky-hide');
        document.dispatchEvent(new Event('cookieyes_consent_update'));

        expect(bottomScrollPadding()).toBe('0px');
    });

    it('reserves room again when CookieYes shows the bar anew', () => {
        cookieYesBar.classList.remove('cky-hide');
        document.dispatchEvent(new Event('cookieyes_banner_load'));

        expect(bottomScrollPadding()).toBe('273px');
    });
});
