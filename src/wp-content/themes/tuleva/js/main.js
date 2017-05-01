$(document).ready(function($) {
    var handleResponsiveSlidesNav = function() {
        var wrapSelector = '.media-box-slider-responsive',
            desktopSliderSelector = '.media-box-slider.hidden-xs',
            mobileSliderSelector = '.media-box-slider.visible-xs',
            unsliderSelector = '.unslider',
            navSelector = '.unslider-nav';

        $(wrapSelector).each(function() {
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
    };

    $('.media-box-slider').unslider();
    handleResponsiveSlidesNav();

    $(window).resize(function() {
        handleResponsiveSlidesNav();
    });

    $('.beacon-toggle').on('click', function(ev) {
        ev.preventDefault();
        $(this).toggleClass('beacon-toggle--open');
        window.HS.beacon.toggle();
    });

    // Select all links with hashes
    $('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .not('[data-toggle="tab"]')
        .on('click', function(ev) {
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
                    }, 1000, function() {
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
});
