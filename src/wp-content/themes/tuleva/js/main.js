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
            number = Math.floor(number);

            if (typeof LANGCODE === 'undefined') {
                return number;
            }

            if (number < 10000 && number > -10000) {
                return number;
            }

            if (LANGCODE === 'et') {
                number = Math.floor(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            } else if (LANGCODE === 'en') {
                number = Math.floor(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            return number;
        },

        getTaxFreeWage = function (wage) {
            if (wage < 14400) {
                return 7848;
            }
            if (wage < 25200) {
                return 7848 - 7848 / 10800 * (wage - 14400);
            }
            return 0;
        },

        calculateThirdPillarSavings = function () {
            var $calculator = $('.third-pillar-calculator');
            var yearlyWage = parseInt($calculator.find('#yearlyWage').val());
            var parsedYearlyWage = isNaN(yearlyWage) ? 24000 : yearlyWage;
            var monthlyWage = parseInt($calculator.find('#monthlyWage').val());
            monthlyWage = isNaN(monthlyWage) ? 2000 : monthlyWage;
            var wageAddition = parseInt($calculator.find('#wageAddition').val());
            wageAddition = isNaN(wageAddition) ? 0 : wageAddition;
            var wageDeduction = parseInt($calculator.find('#wageDeduction').val());
            wageDeduction = isNaN(wageDeduction) ? 0 : wageDeduction;
            var taxReliefs = parseInt($calculator.find('#taxReliefs').val());
            taxReliefs = isNaN(taxReliefs) ? 0 : taxReliefs;

            var wage = !isNaN(yearlyWage) ? parsedYearlyWage : monthlyWage * 12;
            var wageTotal = Math.max(wage - wageDeduction + wageAddition, 0);
            var taxFreeWage = getTaxFreeWage(wage);
            var deductions = wageTotal * 0.036;

            var taxableWage = Math.max(
                wageTotal - taxFreeWage - deductions - taxReliefs, 0);
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
        },
        calculateSecondPillarPaymentRate = function () {
            var $calculator = $('.second-pillar-payment-rate-calculator');

            var age = parseInt($calculator.find('#yourAge').val());
            age = isNaN(age) ? 30 : age;

            var grossSalary = parseInt($calculator.find('#monthlyWage').val());
            grossSalary = isNaN(grossSalary) ? 2000 : grossSalary;

            var pillarContribution = parseInt($calculator.find('input[name="pillarContribution"]:checked').val());
            var returnRate = Number($calculator.find('#returnRate').val());

            var unemploymentInsurance = 0.016 * grossSalary;
            var taxFreeWage = 700;
            var secondPillarContribution2Percent = 2 / 100 * grossSalary;

            // 2024
            var taxFreeWage2024 = getTaxFreeWage(grossSalary * 12) / 12;
            var incomeTax2024 =
                Math.max((grossSalary - unemploymentInsurance - secondPillarContribution2Percent - taxFreeWage2024) * 0.20, 0);
            var netSalary2024 = grossSalary - unemploymentInsurance - secondPillarContribution2Percent - incomeTax2024;

            // 2025 at your selected contribution rate
            var yourSecondPillarContribution = pillarContribution / 100 * grossSalary;
            var incomeTax =
                Math.max((grossSalary - unemploymentInsurance - yourSecondPillarContribution - taxFreeWage) * 0.22, 0);
            var netSalary = grossSalary - unemploymentInsurance - yourSecondPillarContribution - incomeTax;

            // 2025 at 2% contribution rate
            var incomeTax2Percent =
                Math.max((grossSalary - unemploymentInsurance - secondPillarContribution2Percent - taxFreeWage) * 0.22, 0);
            var netSalary2Percent = grossSalary - unemploymentInsurance - secondPillarContribution2Percent - incomeTax2Percent;
            var netSalaryVs2Percent = netSalary2Percent - netSalary;

            // total 2nd pillar contribution per month
            var governmentContribution = 0.04 * grossSalary;
            var monthlyContribution = yourSecondPillarContribution + governmentContribution;
            var monthlyContribution2Percent = secondPillarContribution2Percent + governmentContribution;
            var monthlyContributionDiff = monthlyContribution - monthlyContribution2Percent;

            var yearlyTaxWin = Math.max((monthlyContributionDiff - netSalaryVs2Percent) * 12, 0);

            var netSalary2025vs2024 = netSalary - netSalary2024;

            var years = 65 - age;

            var savingSum = !returnRate ?
                monthlyContributionDiff * 12 * years :
                monthlyContributionDiff * 12 * (Math.pow(1 + returnRate / 100, years) - 1) / (returnRate / 100);

            $calculator.find('#netWage').text(`${format(netSalary)} €`);
            $calculator.find('#netWage2024').text(`${format(netSalary2024)} €`);

            if (netSalary2025vs2024 < 0) {
                $calculator.find('#netWageDiff').text(`${format(netSalary2025vs2024).toString().replace('-', '−')} €`);
            } else {
                $calculator.find('#netWageDiff').text(`+${format(netSalary2025vs2024)} €`);
            }

            $calculator.find('#monthlyContribution').text(`${format(monthlyContribution)} €`);
            $calculator.find('#monthlyContributionYou').text(`${format(yourSecondPillarContribution)} €`);
            $calculator.find('#monthlyContributionGov').text(`${format(governmentContribution)} €`);

            if (yearlyTaxWin > 0) {
                $calculator.find('#yearlyTaxWin').text(`+${format(yearlyTaxWin)} €`);
                $calculator.find('#yearlyTaxWin').removeClass('d-none');
                $calculator.find('#yearlyTaxWinZero').addClass('d-none');
            } else {
                $calculator.find('#yearlyTaxWinZero').removeClass('d-none');
                $calculator.find('#yearlyTaxWin').addClass('d-none');
            }

            if (savingSum > 0) {
                $calculator.find('#savingsSum').text(`+${format(savingSum)} €`);
                $calculator.find('#savingsSum').removeClass('d-none');
                $calculator.find('#savingsSumZero').addClass('d-none');
            } else {
                $calculator.find('#savingsSumZero').removeClass('d-none');
                $calculator.find('#savingsSum').addClass('d-none');
            }
        },
        initSecondPillarPaymentRateCalculator = function () {
            var $input = $('.second-pillar-payment-rate-calculator input');
            $input.on('change', calculateSecondPillarPaymentRate);
            $input.on('keyup', calculateSecondPillarPaymentRate);
            $input.closest('form').on('submit', function (ev) {
                ev.preventDefault();
                return false;
            });
            var $customRange = $('.second-pillar-payment-rate-calculator .custom-range');
            var $customTooltip = $('#customTooltip');

            function updateTooltip(value) {
                $customTooltip.text(value + '%');
            }

            function updateTooltipPosition() {
                var value = Number($customRange.val());
                var max = Number($customRange.attr('max'));
                var min = Number($customRange.attr('min'));

                var percent = (value - min) / (max - min);
                var rangeWidth = $customRange.outerWidth();
                var newX = percent * rangeWidth - rangeWidth / 2;

                var thumbRadius = 24 / 2;
                var fractionFromCentre = (percent - 0.5) * 2;
                var adjustment = fractionFromCentre * -thumbRadius;

                $customTooltip.css('transform', 'translateX(' + (newX + adjustment) + 'px)');
            }

            $customRange.on('input', function () {
                var value = $(this).val();
                updateTooltip(value);
                updateTooltipPosition();
                calculateSecondPillarPaymentRate();
            });
        },
        initAccordion = function () {
            $('.accordion-parent').on('show.bs.collapse', function (ev) {
                var $target = $(ev.target);
                $($target).addClass('active');

                var $toggle = $('[data-target="#' + $target.attr('id') + '"], [href="#' + $target.attr('id') + '"]');
                $($toggle).closest('.toggle-parent').addClass('active').siblings().removeClass('active');
            });

            $('.accordion-parent').on('hide.bs.collapse', function (ev) {
                var $target = $(ev.target);
                $($target).removeClass('active');

                var $toggle = $('[data-target="#' + $target.attr('id') + '"], [href="#' + $target.attr('id') + '"]');
                $($toggle).closest('.toggle-parent').removeClass('active');
            });

            $('.accordion-parent').on('shown.bs.collapse', function () {
                this.scrollIntoView();
            });

            $('.accordion-parent [data-toggle="collapse"]').click(function () {
                var target = $(this).attr('data-target');
                var $target = $(target);
                var $parent = $($target.attr('data-parent'));

                $parent.find('.collapse.show').collapse('hide');
            });
        },
        initCountdownTimer = function () {
            var datetimeElement = document.querySelector('.countdown-timer');

            if (!datetimeElement) {
                return;
            }

            var targetDatetime = datetimeElement.getAttribute('data-datetime');

            if (!targetDatetime) {
                return;
            }

            var targetDate = new Date(targetDatetime);
            var hoursElement = datetimeElement.querySelector('.countdown-timer__hours');
            var minutesElement = datetimeElement.querySelector('.countdown-timer__minutes');
            var secondsElement = datetimeElement.querySelector('.countdown-timer__seconds');

            function updateClock() {
                var time = getTimeRemaining(targetDate);

                hoursElement.innerText = time.hours.toString().padStart(2, "0");
                minutesElement.innerText = time.minutes.toString().padStart(2, "0");
                secondsElement.innerText = time.seconds.toString().padStart(2, "0");

                if (time.total > 0) {
                    var delay = 1000 - (new Date() % 1000);
                    setTimeout(updateClock, delay);
                }
            }

            updateClock();
        };

    initStickyHeader();
    initNewsletterBeaconToggle();
    initHelpBeaconToggle();
    initPostSidebarHandler();
    initGenericModals();
    initModalEscClose();
    initThirdPillarCalculator();
    initSecondPillarPaymentRateCalculator();
    initAccordion();
    initCountdownTimer();
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

    $('[data-toggle="tooltip"]').tooltip();

    $('.qa-block__expand').on('click', function (ev) {
        var currentText = $(this).text(),
            openText = $(this).data('open-text'),
            closeText = $(this).data('close-text');

        ev.preventDefault();

        if (currentText === openText) {
            $(this).closest('.qa-block').find('.qa__question-wrapper').removeClass('qa__question-wrapper--collapsed');
            $(this).text(closeText);
        } else {
            $(this).closest('.qa-block').find('.qa__question-wrapper--collapsable').addClass('qa__question-wrapper--collapsed');
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

function getTimeRemaining(endtime) {
    var total = Date.parse(endtime) - Date.parse(new Date());
    var total_seconds = Math.floor(total / 1000);
    var hours = Math.floor(total_seconds / 3600);
    var minutes = Math.floor((total_seconds / 60) % 60);
    var seconds = total_seconds % 60;

    return {
        total,
        hours,
        minutes,
        seconds
    };
}
