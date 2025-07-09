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
        initHelpBeaconToggle = function () {
            var $beaconToggle = $('.beacon-toggle--help');

            $beaconToggle.on('click', function (ev) {
                ev.preventDefault();
                $(this).toggleClass('beacon-toggle--open btn-danger btn-secondary');
                window.Beacon('toggle');
            });

            $(window).scroll(function () {
                var scrollPosition = $(window).scrollTop(),
                    viewportHeight = $(window).height(),
                    heroHeight = $('.page-container section:first-of-type').height();

                var maxHeight = Math.max(heroHeight, viewportHeight);

                if (scrollPosition > maxHeight && !$('.footer-help-close').hasClass('footer-help-close--open')) {
                    $beaconToggle.addClass('d-block');
                }

                if (scrollPosition < maxHeight && !$beaconToggle.hasClass('beacon-toggle--open')) {
                    $beaconToggle.removeClass('d-block');
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

        getTaxFreeWage = function (yearlyWage) {
            if (yearlyWage < 14400) {
                return 7848;
            }
            if (yearlyWage < 25200) {
                return 7848 - 7848 / 10800 * (yearlyWage - 14400);
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
            var incomeTaxRate = 0.22;
            var savingsSum = yearlyAmount * incomeTaxRate;

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
            calculateThirdPillarSavings();
        },
        calculateSecondPillarPaymentRate = function () {
            var $calculator = $('.second-pillar-payment-rate-calculator');

            var grossSalary = parseInt($calculator.find('#monthlyWage').val());
            grossSalary = isNaN(grossSalary) ? 2000 : grossSalary;

            // 2, 4 or 6
            var pillarContribution = parseInt($calculator.find('input[name="pillarContribution"]:checked').val());

            var unemploymentInsurance = 0.016 * grossSalary;

            // 2026
            var taxFreeWage = 700;

            // 2026 at your selected contribution rate
            var yourSecondPillarContribution = pillarContribution / 100 * grossSalary;
            var incomeTaxRate = 0.22;
            var incomeTax =
                Math.max((grossSalary - unemploymentInsurance - yourSecondPillarContribution - taxFreeWage) * incomeTaxRate, 0);
            var nationalSecurityTax = 0.02 * grossSalary;
            var netSalary = grossSalary - unemploymentInsurance - yourSecondPillarContribution - incomeTax - nationalSecurityTax;

            // 2026 at 0% contribution rate
            var incomeTax0Percent = Math.max((grossSalary - unemploymentInsurance - taxFreeWage) * incomeTaxRate, 0);

            var monthlyTaxWin = Math.max((incomeTax0Percent - incomeTax), 0);
            var yearlyTaxWin = monthlyTaxWin * 12;

            $calculator.find('#netWage').text(`${format(netSalary)} €`);
            $calculator.find('#monthlyContributionYou').text(`${format(yourSecondPillarContribution)} €`);
            $calculator.find('#yearlyTaxWin').text(`${format(yearlyTaxWin)} €`);
        },
        initSecondPillarPaymentRateCalculator = function () {
            var $input = $('.second-pillar-payment-rate-calculator input');
            $input.on('change', calculateSecondPillarPaymentRate);
            $input.on('keyup', calculateSecondPillarPaymentRate);
            $input.closest('form').on('submit', function (ev) {
                ev.preventDefault();
                return false;
            });
            var $formRange = $('.second-pillar-payment-rate-calculator .form-range');

            $formRange.on('input', function () {
                calculateSecondPillarPaymentRate();
            });
            calculateSecondPillarPaymentRate();
        },
        initPayoutCalculator = function () {
            $('.payout-calculator').each(function () {
                var $calculator = $(this);

                $calculator.find('input').on('change keyup', function () {
                    calculatePayout($calculator);
                });

                $calculator.find('.form-range').on('input', function () {
                    calculatePayout($calculator);
                });

                calculatePayout($calculator);
            });
        },
        calculatePayout = function ($calculator) {
            var portfolioSum = parseInt($calculator.find('.portfolioSum').val());
            portfolioSum = isNaN(portfolioSum) ? 20000 : portfolioSum;

            // Elada jäänud aastad 65-aastastel
            // source: https://www.stat.ee/et/avasta-statistikat/valdkonnad/heaolu/tervis/oodatav-eluiga
            var pensionYears = 19;

            var returnRate = Number($calculator.find('.returnRate').val()) / 100;

            var lumpSumIncomeTax = 0.1;
            var recurringPaymentIncomeTax = pensionYears < 19 ? 0.1 : 0;

            var recurringTotal = 0;
            for (var i = 1; i <= pensionYears; i++) {
                var unitPrice = Math.pow(1 + returnRate, i);
                var withdrawalUnits = portfolioSum / pensionYears;
                var withdrawal = withdrawalUnits * unitPrice * (1 - recurringPaymentIncomeTax);
                recurringTotal += withdrawal;
                if (i === pensionYears) {
                    var recurringMonthlyLastYear = withdrawal / 12;
                }
            }

            var lumpSumTotal = portfolioSum * (1 - lumpSumIncomeTax);
            var lumpSumMonthly = lumpSumTotal / pensionYears / 12;

            if (pensionYears < 1) {
                recurringTotal = lumpSumTotal;
            }

            var recurringMonthlyFirstYear = portfolioSum * (1 - recurringPaymentIncomeTax) / pensionYears / 12;

            $calculator.find('.recurringPayoutSum').text(`${format(Math.round(recurringTotal))} €`);
            $calculator.find('.singlePayoutSum').text(`${format(Math.round(lumpSumTotal))} €`);
            $calculator.find('.recurringPayoutMonthly1').text(format(Math.round(recurringMonthlyFirstYear)));

            if (Math.round(recurringMonthlyFirstYear) !== Math.round(recurringMonthlyLastYear)) {
                $calculator.find('.recurringArrow').removeClass('d-none');
                $calculator.find('.recurringPayoutMonthly2').removeClass('d-none')
                    .text(format(Math.round(recurringMonthlyLastYear)));
            } else {
                $calculator.find('.recurringArrow').addClass('d-none');
                $calculator.find('.recurringPayoutMonthly2').addClass('d-none');
            }

            if (Math.round(recurringMonthlyFirstYear) === Math.round(recurringMonthlyLastYear)) {
                $calculator.find('.receiveMonthlyDec').addClass('d-none');
                $calculator.find('.receiveMonthlyInc').addClass('d-none');
                $calculator.find('.receiveMonthly').removeClass('d-none');
                $calculator.find('.increasingTooltip').addClass('d-none');
                $calculator.find('.decreasingTooltip').addClass('d-none');
            } else if (Math.round(recurringMonthlyFirstYear) < Math.round(recurringMonthlyLastYear)) {
                $calculator.find('.receiveMonthlyDec').addClass('d-none');
                $calculator.find('.receiveMonthlyInc').removeClass('d-none');
                $calculator.find('.receiveMonthly').addClass('d-none');
                $calculator.find('.increasingTooltip').removeClass('d-none');
                $calculator.find('.decreasingTooltip').addClass('d-none');
            } else if (Math.round(recurringMonthlyFirstYear) > Math.round(recurringMonthlyLastYear)) {
                $calculator.find('.receiveMonthlyDec').removeClass('d-none');
                $calculator.find('.receiveMonthlyInc').addClass('d-none');
                $calculator.find('.receiveMonthly').addClass('d-none');
                $calculator.find('.increasingTooltip').addClass('d-none');
                $calculator.find('.decreasingTooltip').removeClass('d-none');
            }

            $calculator.find('.singlePayoutMonthly').text(`${format(Math.round(lumpSumMonthly))} €`);
            $calculator.find('.recurringPayoutTaxRate').text(`${(recurringPaymentIncomeTax * 100).toFixed(0)}%`);
            $calculator.find('.singlePayoutTaxRate').text(`${(lumpSumIncomeTax * 100).toFixed(0)}%`);
        },
        initAllFormRangeSliders = function () {
            $('.form-range').each(function () {
                var $formRange = $(this);
                var $customTooltip = $formRange.siblings('.custom-tooltip');

                function updateTooltip(value) {
                    var unit = $formRange.data('unit') || '';
                    $customTooltip.text(value + unit);
                }

                function updateTooltipPosition() {
                    var value = Number($formRange.val());
                    var max = Number($formRange.attr('max'));
                    var min = Number($formRange.attr('min'));

                    var percent = (value - min) / (max - min);
                    var rangeWidth = $formRange.outerWidth();
                    var newX = percent * rangeWidth - rangeWidth / 2;

                    var thumbRadius = 40 / 2; // 2.5rem = 40px
                    var fractionFromCentre = (percent - 0.5) * 2;
                    var adjustment = fractionFromCentre * -thumbRadius;

                    $customTooltip.css('transform', 'translate(' + (newX + adjustment) + 'px, 0)');
                }

                $(window).on('resize pageshow', function () {
                    var value = $formRange.val();
                    updateTooltip(value);
                    updateTooltipPosition();
                });

                $formRange.on('input', function () {
                    var value = $(this).val();
                    updateTooltip(value);
                    updateTooltipPosition();
                });
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

            $('.accordion-parent [data-bs-toggle="collapse"]').click(function () {
                var target = $(this).attr('data-target');
                var $target = $(target);
                var $parent = $($target.attr('data-parent'));

                $parent.find('.collapse.show').collapse('hide');
            });

            const hash = window.location.hash.substring(1);

            if (hash) {
                const targetElement = document.querySelector(`.toggle-parent a[href="#${hash}"]`);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    targetElement.click();
                }
            }
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
        },
        initHighContrastMode = function() {
            // synced with onboarding-client
            const HIGH_CONTRAST_MODE_COOKIE_NAME = 'high-contrast';

            const domain = window.location.host.includes('localhost') ? 'localhost' : '.tuleva.ee'

            const isHighContrastModeEnabled = () => {
                if (document.cookie.includes(HIGH_CONTRAST_MODE_COOKIE_NAME)) {
                    return document.cookie.includes(HIGH_CONTRAST_MODE_COOKIE_NAME);
                }
                return window.matchMedia('(prefers-contrast: more)').matches;
            };

            const updateHighContrastCookie = () => {
                const oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
                const unixEpoch = new Date(1);
                // if enabled, set expiry to UTC epoch to delete, if not enabled then set it to 12 months in future
                const expiry = isHighContrastModeEnabled() ? unixEpoch : oneYearFromNow;
                document.cookie = `${HIGH_CONTRAST_MODE_COOKIE_NAME}=true;expires=${expiry.toUTCString()};domain=${domain};path=/`;
            };

            var $toggleSwitches = $('.high-contrast-toggle');

            const toggleHighContrastMode = () => {
                const root = document.documentElement;

                if (isHighContrastModeEnabled()) {
                    root.classList.add('high-contrast');
                } else {
                    root.classList.remove('high-contrast');
                }
                $toggleSwitches.prop('checked', isHighContrastModeEnabled());
            };

            $toggleSwitches.on('change', function() {
                updateHighContrastCookie();
                toggleHighContrastMode();
            });

            const contrastMediaQuery = window.matchMedia('(prefers-contrast: more)');

            contrastMediaQuery.addEventListener('change', () => {
                if (!document.cookie.includes(HIGH_CONTRAST_MODE_COOKIE_NAME)) {
                    toggleHighContrastMode();
                }
            });

            toggleHighContrastMode();

        },
        initCountdownTimerFull = function () {
            var march31midnight = 1743454799000;
            var days = 0, hours = 0, minutes = 0, seconds = 0;
            var daysFirstNumber = document.getElementById('days-first-number');
            var daysLastNumber = document.getElementById('days-last-number');
            var hoursFirstNumber = document.getElementById('hours-first-number');
            var hoursLastNumber = document.getElementById('hours-last-number');
            var minutesFirstNumber = document.getElementById('minutes-first-number');
            var minutesLastNumber = document.getElementById('minutes-last-number');

            if (!daysFirstNumber) {
                return
            }
            var countdownTimer = countdown(march31midnight, function (ts) {
                if (ts.end > ts.start) {
                    daysFirstNumber.innerHTML = '0';
                    daysLastNumber.innerHTML = '0';
                    hoursFirstNumber.innerHTML = '0';
                    hoursLastNumber.innerHTML = '0';
                    minutesFirstNumber.innerHTML = '0';
                    minutesLastNumber.innerHTML = '0';
                    window.clearInterval(countdownTimer);
                } else {
                    days = ('0' + ts.days).slice(-2);
                    hours = ('0' + ts.hours).slice(-2);
                    minutes = ('0' + ts.minutes).slice(-2);
                    seconds = ('0' + ts.seconds).slice(-2);
                    daysFirstNumber.innerHTML = days.substring(0, 1);
                    daysLastNumber.innerHTML = days.substring(1, 2);
                    hoursFirstNumber.innerHTML = hours.substring(0, 1);
                    hoursLastNumber.innerHTML = hours.substring(1, 2);
                    minutesFirstNumber.innerHTML = minutes.substring(0, 1);
                    minutesLastNumber.innerHTML = minutes.substring(1, 2);
                }
            }, countdown.DAYS | countdown.HOURS | countdown.MINUTES);
        };

    var setupFunctions = [
        function () { initHelpBeaconToggle(); },
        function () { initPostSidebarHandler(); },
        function () { initGenericModals(); },
        function () { initModalEscClose(); },
        function () { initThirdPillarCalculator(); },
        function () { initSecondPillarPaymentRateCalculator(); },
        function () { initPayoutCalculator(); },
        function () { initAllFormRangeSliders(); },
        function () { initAccordion(); },
        function () { initCountdownTimer(); },
        function () { initCountdownTimerFull(); },
        function () { initModal('#founders', 'foundersModal'); },
        function () { initModal('#founders-2', 'foundersModal-2'); },
        // function () {  initModal('#question-joining-fee', 'questionJoiningFeeModal'); },
        // function () { // initModal('#question-profit', 'questionProfitModal'); },
        function () { initModal('#question-vote', 'questionVoteModal'); },
        function () { initModal('#question-rights', 'questionRightsModal'); },
        function () { initHighContrastMode(); },
    ]

    setupFunctions.forEach(function (func) {
        try {
            func()
        } catch (e) {
            console.error(e)
        }
    })

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
        .not('[data-bs-toggle="tab"]')
        .not('[data-bs-toggle="collapse"]')
        .not('[href="#carouselControls"]')
        .on('click', function (ev) {
            // Only handle on-page links
            if (
              location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
              location.hostname === this.hostname
            ) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

              if (target.length) {
                // Prevent default scroll behavior
                ev.preventDefault();

                // Use scrollIntoView API for smooth scrolling
                target.get(0).scrollIntoView({ behavior: 'smooth' });

                // Update the URL hash without jumping
                history.pushState(null, null, this.hash);

                // Handle focus after scrolling completes
                setTimeout(function () {
                  target.focus();
                  if (!target.is(":focus")) {
                    target.attr('tabindex', '-1');
                    target.focus();
                  }
                }, 500);
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

    $('[data-bs-toggle="tooltip"]').tooltip();

    $('.qa-block__expand').on('click', function (ev) {
        var currentText = $(this).text(),
            openText = $(this).data('open-text'),
            closeText = $(this).data('close-text');

        ev.preventDefault();

        if (currentText === openText) {
            $(this).closest('.qa-block').find('.qa__question-wrapper').removeClass('qa__question-wrapper--collapsed');
            $(this).toggleClass('more').text(closeText);
        } else {
            $(this).closest('.qa-block').find('.qa__question-wrapper--collapsable').addClass('qa__question-wrapper--collapsed');
            $(this).toggleClass('more').text(openText);
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
