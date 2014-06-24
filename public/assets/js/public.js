(function ( $ ) {
	"use strict";

	$(function () {

		$(".flex-placeholder").each(function() {

            var slider = $(this);

            var defaults = {
                animationLoop: false,
                controlNav: true,
                directionNav: true
            };

            var config = $.extend({}, defaults, slider.data("flexoptions"));

            // Initialize Slider
            slider.flexslider(config);

        });

	});

}(jQuery));