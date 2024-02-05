jQuery(function (window, document, $) {

	/*-- Strict mode enabled --*/
	"use strict";
	var _document = $(document),
		_window = $(window),
		_navbarCollapse = $('.navbar-collapse'),
		_navbarToggler = $('.navbar-toggler');

	var _dynamicActiveClass = function (e) {
		$(function () {
			var _nav = $('.dynamic-nav');
			var pgurl = window.location.href.substr(window.location.href
				.lastIndexOf("/") + 1);

			_nav.each(function () {
				var _activeLink = $(this).find("li a");
				_activeLink.each(function () {
					if ($(this).attr("href") == pgurl || $(this).attr("href") == '') {
						$(this).addClass("active");

						if ($(this).parents('li').hasClass('has-dropdown')) {
							$(this).parents('.has-dropdown').find('>a').addClass('active');
						}
					}
				})
			});
		});
	};

	_dynamicActiveClass();

	var smoothScroll = function (e) {
		$('a[href*="#"]')
			.not('[href="#"]')
			.not('[href="#0"]')
			.not('[data-toggle="tab"]')
			.not('[data-toggle="pill"]')
			.on('click', function (event) {

				var _defaults = {
					offset: 0,
					offset_mobile: 0
				}

				if (
					location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
					location.hostname == this.hostname
				) {
					//set target
					var target = $(this.hash),
						_offsetTop;

					//set the offset value
					var _tOffset = target[0].hasAttribute('data-scroll-offset') ? target.data('scroll-offset') : _defaults.offset,

						_tOffsetMobile = target[0].hasAttribute('data-scroll-offset-mobile') ? target.data('scroll-offset-mobile') : _defaults.offset_mobile;

					_window.width() < 768 ? _offsetTop = _tOffsetMobile : _offsetTop = _tOffset;

					target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

					if (target.length) {
						event.preventDefault();
						$('html, body').animate({
							scrollTop: target.offset().top - _offsetTop
						}, 800, 'easeInOutExpo');
					}
				}
			});
	};
	smoothScroll();

	//animated navbar-toggler button
	_document.on('click', '.navbar .navbar-toggler', function () {
		$(this).toggleClass("change");
	});

	_document.on('click', function (e) {
		if (_navbarCollapse.hasClass('show') && !$('.navbar-nav, .navbar-nav *').is(e.target)) {
			console.log(e.target);
			_navbarCollapse.removeClass('show');
			_navbarToggler.removeClass('change');
		}
	});

	if (_window.width() < 992) {
		_document.on('click', '.has-dropdown > a', function () {
			$(this).siblings(".submenu").slideToggle('300');
		});
	}

	//Video modal
	$('.video-popup').magnificPopup({
		type: 'iframe',
		mainClass: 'mfp-with-zoom',
		iframe: {
			markup: '<div class="mfp-iframe-scaler">' +
				'<div class="mfp-close"></div>' +
				'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
				'</div>',
			patterns: {
				youtube: {
					index: 'youtube.com/',
					id: 'v=',
					src: '//www.youtube.com/embed/%id%?autoplay=1'
				}
			},
			srcAction: 'iframe_src'
		}
	});

	//Counter Up js
	$('.counter').counterUp({
		// time: 2000
	});

	//Global Form validation
	// $('.quote-form').on('submit', function (e) {
	// 	e.preventDefault();
	// 	var _self = $(this),
	// 		data = $(this).serialize(),
	// 		__selector = _self.closest('input, textarea');

	// 	_self.closest('div').find('input,textarea').removeAttr('style');
	// 	_self.find('.err-msg').remove();
	// 	_self.find('.form-success').removeClass('form-success');

	// 	$('.submit-loading-img').css('display', 'block');
	// 	_self.closest('div').find('button[type="submit"]').attr('disabled', 'disabled');

	// 	$.ajax({
	// 		url: 'assets/email/email.php',
	// 		type: "post",
	// 		dataType: 'json',
	// 		data: data,
	// 		success: function (data) {
	// 			$('.submit-loading-img').css('display', 'none');
	// 			_self.closest('div').find('button[type="submit"]').removeAttr('disabled');
	// 			if (data.code == false) {
	// 				_self.closest('div').find('[name="' + data.field + '"]').addClass('form-success');
	// 				_self.closest('div').find('[name="' + data.field + '"]').after('<div class="err-msg">*' + data.err + '</div>');
	// 			} else {
	// 				_self.find('textarea').after('<div class="success-msg">' + data.success + '</div>');
	// 				_self[0].reset();
	// 				_self.find('.success-msg').css({
	// 					'display': 'block'
	// 				});

	// 				setTimeout(function () {
	// 					$('.success-msg').fadeOut('slow');
	// 				}, 5000);
	// 			}
	// 		}
	// 	});
	// });

	_window.on("load resize", function () {

		// Owl carousel with filter
		var _x = 5;
		var _projectOwl = $('.projects-carousel.owl-carousel').owlCarousel({
			margin: 0,
			nav: true,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: _x - 3
				},
				1025: {
					items: _x - 2
				},
				1199: {
					items: _x
				}
			}
		});

		// Clients carousel with filter


		var owl = $('.clients-carousel-wrapper.owl-carousel').owlCarousel({
			margin: 0,
			loop: true,
			nav: true,
			responsive: {
				0: {
					items: 3
				},
				600: {
					items: 4
				},
				1199: {
					items: 5
				}
			}
		});

		//isotope initialization
		var $grid = $('.iso-grid').isotope({
			// options
			itemSelector: '.item',
			percentPosition: true
		});


		// filter items on button click
		$('.btn-filter-wrap').on('click', '.btn-filter', function () {
			var filterValue = $(this).attr('data-filter');
			$grid.isotope({
				filter: filterValue
			});
		});

		$('.btn-filter-wrap').each(function (i, buttonGroup) {
			var $buttonGroup = $(buttonGroup);
			$buttonGroup.on('click', 'button', function () {
				$buttonGroup.find('.is-checked').removeClass('is-checked');
				$(this).addClass('is-checked');
			});
		});

	});

	// script for box-equal-height
	var maxHeight = 0,
		_equalHeight = function (eq) {
			$(eq).each(function () {
				$(this).find('.equalHeight').each(function () {
					if ($(this).height() > maxHeight) {
						maxHeight = $(this).height();
					}
				});
				$(this).find('.equalHeight').height(maxHeight);
			});
		}
	_equalHeight('.equalHeightWrapper');

	//Change navbar style on scroll

	var _pageHeader = $('.page-header');
	_window.on('scroll load', function () {
		if (_window.scrollTop() >= 100) {
			_pageHeader.addClass('scrolled');
		} else {
			_pageHeader.removeClass('scrolled');
		}
	});

}(window, document, jQuery));