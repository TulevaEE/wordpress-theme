/**
 * Keeps the CookieYes consent banner out of reader views. Chrome's Reading
 * Mode runs its own copy of Readability, which takes the CookieYes revisit
 * icon as the article's lead image and every paragraph of the banner, hidden
 * or not, as its caption. Readability drops elements whose class calls them
 * supplemental, so each element CookieYes adds to the body gets that class,
 * also when CookieYes draws the banner anew. Words like banner, cookie or
 * consent stay out of the class and file names: ad blockers hide and block
 * those.
 */

(function () {
    var READER_VIEW_SKIPPED_CLASS = 'reader-view-supplemental';

    function markCookieYesElements() {
        document
            .querySelectorAll('body > [class^="cky-"]')
            .forEach(function (element) {
                element.classList.add(READER_VIEW_SKIPPED_CLASS);
            });
    }

    markCookieYesElements();
    new MutationObserver(markCookieYesElements).observe(document.body, {
        childList: true
    });
})();
