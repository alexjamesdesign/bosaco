// binds $ to jquery, requires you to write strict code. Will fail validation if it doesn't match requirements.
(function ($) {
	"use strict";

	// add all of your code within here, not above or below
	$(function () {

		// Smaller header
		$(window).scroll(function() {
			if ($(document).scrollTop() > 150) {
			  $('.nt-sticky').addClass('nt-shrink');
			} else {
			  $('.nt-sticky').removeClass('nt-shrink');
			}
		});

		var accordionContent = $('accordion-content');

		if ($(window).width() > 1000) {
			$('.accordion-content').slideDown();
		}

		$('.accordion-header').click(function() {
			$(this).siblings('.accordion-content').slideToggle();
		});


	});

}(jQuery));