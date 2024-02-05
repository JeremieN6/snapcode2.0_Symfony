// Avoid `console` errors in browsers that lack a console.
(function () {
	var method;
	var noop = function () {};
	var methods = [
		'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
		'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
		'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
		'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
	];
	var length = methods.length;
	var console = (window.console = window.console || {});

	while (length--) {
		method = methods[length];

		// Only stub undefined methods.
		if (!console[method]) {
			console[method] = noop;
		}
	}
}());

// Place any jQuery/helper plugins in here.
(function ($) {
	'use strict';

	// Equal Height
	$.fn.equalHeight = function (options) {
		var maxHeight = 0;
		var defaults = {
			selector: $('.equalHeight')
		};
		options = $.extend(defaults, options);

		$(this).each(function () {
			$(this).find(defaults.selector).each(function () {
				if ($(this).height() > maxHeight) {
					maxHeight = $(this).height();
				}
			});
			$(this).find(defaults.selector).height(maxHeight);
		});

		return this;
	}

	// Equal Width
	$.fn.equalWidth = function (options) {
		var maxWidth = 0;
		var defaults = {
			selector: $('.equalWidth')
		};
		options = $.extend(defaults, options);

		$(this).each(function () {
			$(this).find(defaults.selector).each(function () {
				if ($(this).width() > maxWidth) {
					maxWidth = $(this).width();
				}
			});
			$(this).find(defaults.selector).width(maxWidth);
		});

		return this;
	}

	if ($(window).width() > 767) {
		$('.equalHeightWrapper').equalHeight({
			selector: $('.equalHeight')
		});
	}

})(jQuery);