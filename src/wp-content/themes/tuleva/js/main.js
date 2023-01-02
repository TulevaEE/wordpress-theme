/* global LANGCODE */

$(document).ready(function ($) {
    var handleResponsiveSlidesNav = function () {
        var wrapSelector = '.media-box-slider-responsive',
            desktopSliderSelector = '.media-box-slider.hidden-xs',
            mobileSliderSelector = '.media-box-slider.visible-xs',
            unsliderSelector = '.unslider',
            navSelector = '.unslider-nav';

        $(wrapSelector).each(function () {
            if ($(window).width() < 769) {
                $(this)
                    .find(desktopSliderSelector)
                    .closest(unsliderSelector)
                    .find(navSelector)
                    .hide();
                $(this)
                    .find(mobileSliderSelector)
                    .closest(unsliderSelector)
                    .find(navSelector)
                    .show();
            } else {
                $(this)
                    .find(mobileSliderSelector)
                    .closest(unsliderSelector)
                    .find(navSelector)
                    .hide();
                $(this)
                    .find(desktopSliderSelector)
                    .closest(unsliderSelector)
                    .find(navSelector)
                    .show();
            }
        });
    },
        initNewsletterBeaconToggle = function () {
            var $beaconToggle = $('.beacon-toggle--newsletter');

            $beaconToggle.on('click', function (ev) {
                ev.preventDefault();
                $(this).toggleClass('beacon-toggle--open');
                $('.beacon-content--newsletter').toggleClass('beacon-content--open');
            });

            $(window).scroll(function () {
                var scrollPosition = $(window).scrollTop(),
                    viewportHeight = $(window).height();

                if (scrollPosition > viewportHeight && !$('.footer-help-close').hasClass('footer-help-close--open')) {
                    $beaconToggle.addClass('d-block');
                }
            });
        },
        initHelpBeaconToggle = function () {
            var $beaconToggle = $('.beacon-toggle--help');

            $beaconToggle.on('click', function (ev) {
                ev.preventDefault();
                $(this).toggleClass('beacon-toggle--open');
                window.Beacon('toggle');
            });

            $(window).scroll(function () {
                var scrollPosition = $(window).scrollTop(),
                    viewportHeight = $(window).height();

                if (scrollPosition > viewportHeight && !$('.footer-help-close').hasClass('footer-help-close--open')) {
                    $beaconToggle.addClass('d-block');
                }
            });
        },
        showStickyHeader = function () {
            $('body').addClass('sticky-header-visible');
        },
        hideStickyHeader = function () {
            $('body').removeClass('sticky-header-visible');
        },
        initStickyHeader = function () {
            $(window).scroll(function () {
                var scrollPosition = $(window).scrollTop(),
                    headerHeight = $('.header').outerHeight();

                if (scrollPosition > headerHeight) {
                    showStickyHeader();
                } else {
                    hideStickyHeader();
                }
            });
        },
        initModal = function (id, target) {
            $(id).animatedModal({
                modalTarget: target,
                color: '#fff',
                animatedIn: 'fadeIn',
                animatedOut: 'fadeOut',
                animationDuration: '.25s'
            });
        },
        initModalEscClose = function () {
            $(document).on('keyup', function (ev) {
                if (ev.keyCode === 27) {
                    $('[class^="close-button-').each(function () {
                        $(this).trigger('click');
                    });
                }
            });
        },
        initGenericModals = function () {
            $('[href^="#modal-"]').each(function () {
                var id = $(this).attr('id');

                if (!id) {
                    id = $(this).attr('href').split('#modal-')[1];
                    $(this).attr('id', id);
                }

                initModal('#' + id, $(this).attr('href').substr(1));
            });
        },
        initPostSidebarHandler = function () {
            var $sidebar = $('.widget-area');
            if ($('body').hasClass('single-post') && $sidebar.length > 0) {
                window.onscroll = function () {
                    if (this.scrollY > 2000) {
                        $sidebar.addClass('widget-area--bottom');
                    } else {
                        $sidebar.removeClass('widget-area--bottom');
                    }
                }
            }
        },
        setCookie = function (cookieName, cookieValue, nDays) {
            var today = new Date(),
                expire = new Date();

            if (nDays === null || nDays === 0) {
                nDays = 1;
            }

            expire.setTime(today.getTime() + 3600000 * 24 * nDays);
            document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" + expire.toGMTString() + "; path=/";
        },
        format = function (number) {
            if (typeof LANGCODE === 'undefined') {
                return Math.floor(number);
            }

            if (LANGCODE === 'et' && number > 9999) {
                number = Math.floor(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            } else if (LANGCODE === 'en') {
                number = Math.floor(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            return number;
        },
        calculateThirdPillarSavings = function () {
            var $calculator = $('.third-pillar-calculator');
            var yearlyWage = parseInt($calculator.find('#yearlyWage').val()) || 0;
            var monthlyWage = parseInt($calculator.find('#monthlyWage').val()) || 0;
            var wageAddition = parseInt($calculator.find('#wageAddition').val()) || 0;
            var wageDeduction = parseInt($calculator.find('#wageDeduction').val()) || 0;
            var taxReliefs = parseInt($calculator.find('#taxReliefs').val()) || 0;
            var kids = parseInt($calculator.find('#kids').val()) || 0;

            var wage = yearlyWage ? yearlyWage : monthlyWage * 12;
            var wageTotal = Math.max(wage - wageDeduction + wageAddition, 0);
            var taxFreeWage = (function (wage) {
                if (wage < 14400) {
                    return 7848;
                }
                if (wage < 25200) {
                    return 7848 - 7848 / 10800 * (wage - 14400);
                }
                return 0;
            })(wage);
            var deductions = wageTotal * 0.036;
            var additionalTaxFreeWage = (function (kids) {
                var additionalTaxFreeWage = 0;
                if (kids >= 2) {
                    additionalTaxFreeWage += 1848;
                }
                if (kids >= 3) {
                    additionalTaxFreeWage += 3048 * (kids - 2);
                }
                return additionalTaxFreeWage;
            })(kids);

            var taxableWage = Math.max(
                wageTotal - taxFreeWage - deductions - additionalTaxFreeWage - taxReliefs, 0);
            var yearlyAmount = Math.min(0.15 * wageTotal, 6000, taxableWage);
            var monthlyAmount = yearlyAmount / 12;
            var savingsSum = yearlyAmount * 0.2;

            $calculator.find('#yearlyAmount').text(format(yearlyAmount));
            $calculator.find('#monthlyAmount').text(format(monthlyAmount));
            $calculator.find('#savingsSum').text(format(savingsSum));
        },
        initThirdPillarCalculator = function () {
            var $input = $('.third-pillar-calculator input');
            $input.on('change', calculateThirdPillarSavings);
            $input.on('keyup', calculateThirdPillarSavings);
            $input.closest('form').on('submit', function (ev) {
                ev.preventDefault();
                return false;
            });
        };

    initStickyHeader();
    initNewsletterBeaconToggle();
    initHelpBeaconToggle();
    initPostSidebarHandler();
    initGenericModals();
    initModalEscClose();
    initThirdPillarCalculator();
    initModal('#founders', 'foundersModal');
    initModal('#founders-2', 'foundersModal-2');
    // initModal('#question-joining-fee', 'questionJoiningFeeModal');
    // initModal('#question-profit', 'questionProfitModal');
    initModal('#question-vote', 'questionVoteModal');
    initModal('#question-rights', 'questionRightsModal');

    $('.testimonial-slider').unslider({
        nav: false,
        arrows: true
    });

    $('.media-box-slider').unslider({
        nav: true,
        arrows: false
    });
    handleResponsiveSlidesNav();

    $(window).resize(function () {
        handleResponsiveSlidesNav();
    });

    $('.navbar-nav a').on('click', function () {
        $(this).closest('.navbar').find('.navbar-toggle').addClass('collapsed');
        $(this).closest('.navbar-collapse').removeClass('in');
    });

    // Select all links with hashes
    $('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .not('[href^="#modal-"]')
        .not('[data-toggle="tab"]')
        .not('[data-toggle="collapse"]')
        .not('[href="#carouselControls"]')
        .on('click', function (ev) {
            // Figure out element to scroll to
            var target = $(this.hash),
                $target = $(target),
                result = true;

            // On-page links
            if (
                location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '')
                &&
                location.hostname === this.hostname
            ) {
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

                // Does a scroll target exist?
                if (target.length) {
                    // Only prevent default if animation is actually gonna happen
                    ev.preventDefault();

                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000, function () {
                        // Callback after animation
                        // Must change focus!
                        $target.focus();

                        // Checking if the target was focused
                        if ($target.is(":focus")) {
                            result = false;
                        } else {
                            // Adding tabindex for elements not focusable
                            $target.attr('tabindex', '-1');
                            // Set focus again
                            $target.focus();
                        }

                        return result;
                    });
                }
            }
        });

    $('.popper').popover({
        container: 'body',
        html: true,
        content: function () {
            return $(this).next('.popper-content').html();
        }
    });

    $('.qa-block').each(function () {
        if ($(this).find('.qa__question-wrapper').length < 4) {
            $(this).find('.qa-block__expand').remove();
        }
    });

    $('.qa-block__expand').on('click', function (ev) {
        var currentText = $(this).text(),
            openText = $(this).data('open-text'),
            closeText = $(this).data('close-text');

        ev.preventDefault();

        if (currentText === openText) {
            $(this).closest('.qa-block').removeClass('qa-block--collapsed');
            $(this).text(closeText);
        } else {
            $(this).closest('.qa-block').addClass('qa-block--collapsed');
            $(this).text(openText);
        }
    });

    $('.footer-help').on('click', function (ev) {
        ev.preventDefault();
        window.Beacon('toggle');
        $('.footer-help-close').toggleClass('footer-help-close--open');
        $('.beacon-toggle').removeClass('d-block');
    });

    $('.footer-help-close').on('click', function (ev) {
        ev.preventDefault();
        window.Beacon('toggle');
        $(this).toggleClass('footer-help-close--open');
    });

    $('.cookie-bar__btn').on('click', function (ev) {
        setCookie('cookie-consent', 1, 365);
        $('#cookie-bar').fadeOut();
    });
});
