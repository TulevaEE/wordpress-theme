/**
 * @jest-environment jsdom
 */

const READABILITY_UNLIKELY_CANDIDATES = new RegExp(
    '-ad-|ai2html|banner|breadcrumbs|combx|comment|community|' +
        'cover-wrap|disqus|extra|footer|gdpr|header|legends|menu|related|' +
        'remark|replies|rss|shoutbox|sidebar|skyscraper|social|sponsor|' +
        'shopping|shopper|retailer|supplemental|ad-break|agegate|' +
        'pagination|pager|popup|yom-remote',
    'i'
);
const READABILITY_OK_MAYBE_ITS_A_CANDIDATE = new RegExp(
    'and|article|body|column|content|main|mathjax|' +
        'shadow|recipe|ingredients|instructions|' +
        'directions|steps',
    'i'
);

const PAGE =
    '<a class="skip-link visually-hidden-focusable" href="#main">Liigu edasi sisu juurde</a>' +
    '<header class="header"></header>' +
    '<main id="main" class="page-container"><div class="content"><p>Artikkel</p></div></main>' +
    '<footer id="footer" class="footer section-spacing"></footer>';

const COOKIEYES_ELEMENTS =
    '<div class="cky-btn-revisit-wrapper cky-revisit-hide cky-revisit-bottom-left">' +
    '<button class="cky-btn-revisit" aria-label="Küpsiste seaded">' +
    '<img src="revisit.svg" alt="Revisit consent button"></button></div>' +
    '<div class="cky-consent-container cky-classic-bottom" role="region" ' +
    'aria-label="Hindame teie privaatsust" tabindex="-1"><div class="cky-consent-bar">' +
    '<p class="cky-title" role="heading" aria-level="2">Hindame teie privaatsust</p>' +
    '<div class="cky-notice-des"><p>Kasutame küpsiseid.</p></div></div></div>';

function renderPage(markup) {
    const body = document.createElement('body');
    body.innerHTML = markup;
    document.documentElement.replaceChild(body, document.body);
}

function loadScript() {
    jest.isolateModules(() => require('./reader-view'));
}

function insertCookieYes() {
    document.body.insertAdjacentHTML('afterbegin', COOKIEYES_ELEMENTS);
}

function removeCookieYes() {
    cookieYesElements().forEach((element) => element.remove());
}

function cookieYesElements() {
    return Array.from(
        document.querySelectorAll('.cky-btn-revisit-wrapper, .cky-consent-container')
    );
}

function strippedByReadability(element) {
    const matchString = element.className + ' ' + element.id;
    return (
        READABILITY_UNLIKELY_CANDIDATES.test(matchString) &&
        !READABILITY_OK_MAYBE_ITS_A_CANDIDATE.test(matchString)
    );
}

function withoutReaderViewClass(element) {
    const copy = element.cloneNode(true);
    copy.classList.remove('reader-view-supplemental');
    return copy.outerHTML;
}

function afterMutations() {
    return new Promise((resolve) => setTimeout(resolve));
}

describe('CookieYes elements in reader views', () => {
    beforeEach(() => renderPage(PAGE));
    afterEach(() => renderPage(''));

    it('leaves out the banner and the revisit button that CookieYes adds after the page loads', async () => {
        loadScript();
        insertCookieYes();
        await afterMutations();

        expect(cookieYesElements().map(strippedByReadability)).toEqual([true, true]);
    });

    it('leaves out the banner and the revisit button already in the page when the script runs', () => {
        insertCookieYes();
        expect(cookieYesElements().map(strippedByReadability)).toEqual([false, false]);
        loadScript();

        expect(cookieYesElements().map(strippedByReadability)).toEqual([true, true]);
    });

    it('leaves out the banner again after CookieYes draws it anew for another language', async () => {
        loadScript();
        insertCookieYes();
        await afterMutations();
        removeCookieYes();
        insertCookieYes();
        await afterMutations();

        expect(cookieYesElements().map(strippedByReadability)).toEqual([true, true]);
    });

    it('changes nothing else on the banner, so screen readers announce and operate it as before', async () => {
        insertCookieYes();
        const before = cookieYesElements().map(withoutReaderViewClass);
        loadScript();
        await afterMutations();

        expect(cookieYesElements().map(withoutReaderViewClass)).toEqual(before);
    });

    it('leaves the page itself as it was', async () => {
        loadScript();
        insertCookieYes();
        await afterMutations();

        expect(
            Array.from(document.body.children)
                .slice(2)
                .map((element) => element.className)
        ).toEqual([
            'skip-link visually-hidden-focusable',
            'header',
            'page-container',
            'footer section-spacing'
        ]);
    });
});
