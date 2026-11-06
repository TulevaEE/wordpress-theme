/**
 * The salary field of a landing page calculator. Testers didn't see that the salary can be
 * changed, so on a desktop the field takes the focus on arrival with a blinking caret, as a
 * search box does. Not on touch screens, where it would open the keyboard over the page, and
 * not when the visitor came for a section further down.
 */

var SALARY_AUTOFOCUS_MEDIA = '(min-width: 992px) and (pointer: fine)';
var LINE_SCROLL_PX = 40;
var PAGE_SCROLL_SHARE = 0.85;

function replacesArrivalExample(event) {
    return event.inputType === 'insertFromPaste' || /^\d/.test(event.data || '');
}

function arrivalScrollDistance(event) {
    var page = window.innerHeight * PAGE_SCROLL_SHARE;
    var distances = {
        ' ': event.shiftKey ? -page : page,
        PageDown: page,
        PageUp: -page,
        ArrowDown: LINE_SCROLL_PX,
        ArrowUp: -LINE_SCROLL_PX
    };
    return distances[event.key];
}

function initSalaryArrivalFocus(salary) {
    var replaceOnFirstKey = false;

    salary.addEventListener('focus', function () {
        if (replaceOnFirstKey) {
            return;
        }
        setTimeout(function () {
            salary.select();
        });
    });
    // After the arrival focus the caret blinks at the end of the example; the first digit
    // typed or a paste still replaces the example, while deleting or clicking edits it as usual
    salary.addEventListener('beforeinput', function (event) {
        if (replaceOnFirstKey && replacesArrivalExample(event)) {
            salary.value = '';
        }
        replaceOnFirstKey = false;
    });
    // The arrival focus must not take the page's keys: a visitor who came to read presses
    // Space or an arrow to scroll, so those hand the page back instead of editing the salary
    salary.addEventListener('keydown', function (event) {
        var distance = arrivalScrollDistance(event);
        if (!replaceOnFirstKey || distance === undefined) {
            return;
        }
        event.preventDefault();
        replaceOnFirstKey = false;
        salary.blur();
        window.scrollBy({ top: distance });
    });
    ['mousedown', 'blur'].forEach(function (type) {
        salary.addEventListener(type, function () {
            replaceOnFirstKey = false;
        });
    });

    if (window.matchMedia && window.matchMedia(SALARY_AUTOFOCUS_MEDIA).matches && !window.location.hash) {
        replaceOnFirstKey = true;
        salary.focus({ preventScroll: true });
        salary.setSelectionRange(salary.value.length, salary.value.length);
    }
}

if (typeof window !== 'undefined') {
    window.initSalaryArrivalFocus = initSalaryArrivalFocus;
}

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initSalaryArrivalFocus: initSalaryArrivalFocus
    };
}
