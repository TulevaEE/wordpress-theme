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
});
