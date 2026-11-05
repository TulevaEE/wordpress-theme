/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

function loadScript(relativePath) {
    window.eval(fs.readFileSync(path.join(__dirname, relativePath), 'utf8'));
}

function pageOverflow() {
    return {
        html: document.documentElement.style.overflow,
        body: document.body.style.overflow,
    };
}

function open() {
    document.getElementById('security').click();
}

function close() {
    document.querySelector('.close-button-modal-security').click();
}

function pressEsc() {
    window.$(document).trigger(window.$.Event('keyup', { keyCode: 27 }));
}

const NO_INLINE_OVERFLOW = { html: '', body: '' };

describe('full-page modals', () => {
    beforeAll(async () => {
        document.body.innerHTML =
            '<header class="header"></header>' +
            '<a href="#modal-security">Uuri lähemalt</a>' +
            '<div id="modal-security" class="modal-full">' +
            '<div class="close-button-modal-security"></div>' +
            '</div>';

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

    afterEach(() => {
        close();
        window.$('body, html').css('overflow', '');
    });

    it('locks page scrolling while a modal is open', () => {
        open();

        expect(pageOverflow()).toEqual({ html: 'hidden', body: 'hidden' });
    });

    it('keeps the header sticky after closing, because overflow on both html and body makes body the scroll container', () => {
        open();
        close();

        expect(pageOverflow()).toEqual(NO_INLINE_OVERFLOW);
    });

    it('keeps the header sticky after closing with Esc', () => {
        open();
        pressEsc();

        expect(pageOverflow()).toEqual(NO_INLINE_OVERFLOW);
    });

    it('keeps the header sticky when Esc is pressed with no modal open', () => {
        pressEsc();

        expect(pageOverflow()).toEqual(NO_INLINE_OVERFLOW);
    });
});
