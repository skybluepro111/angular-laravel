/*global console:false, jQuery:false, scotchPanel:false, FastClick:false, owlCarousel: false, stick_in_parent:false, SelectDecorator:false*/

(function($) {
	'use strict';

	$(function()
	{
		FastClick.attach(document.body);

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			}
		});

		$('.slider').owlCarousel({
			loop:true,
			autoplay: true,
			autoplayTimeout:3000,
			autoplayHoverPause:true,
			responsive:{
				0:{
					items:1,
					dots:true
				}
			}
		});

		window.dispatchEvent(new Event('resize'));

		$('.toggle-nav').click(function() {
			$('body').toggleClass('side-open');
			$('.toggle-nav').toggleClass('is-active');
		});

		pageWidth();

		$(window).on('resize orientationchange', function() {
			pageWidth();
		});

		function pageWidth () {
			var width = $('.container').width();

			if ( $(window).width() <= 800 ) {
				$('.page').removeAttr('style'); 
				$('.resize').removeAttr('style'); 
				$('.resize-post-header').removeAttr('style'); 
				$('.sticky').trigger('sticky_kit:detach');
				return;
			}

			var pWidth = width - 330; // 315: Sidebar + 30px margin
			var hWidth = width - 471; // 456: Logo + Sidebar + 30px margin
			var postHeaderWidth = width - 356; // 341: Logo small + Sidebar + 30px margin

			if ( $(window).width() > 800 ) {
				var isWebkit = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
				var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);

				/* jshint ignore:start */
				if ( isWebkit || isSafari ) {
					$('.sticky').stick_in_parent({
						parent: $("#container"),
						offset_top: 80
					});
				} else {
					$('.sticky').stick_in_parent({
						container: $("#container"),
						offset_top: 80
					});
				}
				/* jshint ignore:end */
			} else {
				$('.sticky').removeAttr('style');
			}

			$('.page').css({width:pWidth}); 
			$('.resize').css({width:hWidth});
			$('.resize-post-header').css({width:postHeaderWidth});

			$('.arrow').removeClass('animate');
			$('.modal').removeClass('animate');
			$('.search').removeClass('animate');
		}

		$(document).on('click', '.more', function () {
			$('.modal').toggleClass('animate');
			$('.arrow').toggleClass('animate');
			$('.more').toggleClass('active');
		});

		$(document).on('click', '.show-search', function () {
			$(this).siblings('.search').toggleClass('animate');
		});

		$(document).on('click', '.mobile-search', function () {
			$(this).parent('form').submit();
		});

		$(document).on('click', '.go-top', function () {
			$('html, body').animate({'scrollTop': '0px'}, 1000);
		});

		scrollManager();

		$(document).scroll(function() {
			scrollManager();
		});

		function scrollManager (argument) {

			var scroll = $(document).scrollTop();

			$('.arrow').removeClass('animate');
			$('.modal').removeClass('animate');
			$('.search').removeClass('animate');

			if ( scroll >=500 ) {

				if ( ! $('.go-top').hasClass('show') )
					$('.go-top').addClass('show');
			}

			else {
				$('.go-top').removeClass('show');
			}

			if ( $('.header--post').length )
			{
				if ( scroll >=300 ) {

					if ( ! $('.header--post').hasClass('animate') )
					{
						$('.header--post').addClass('animate');
						$('.header--main').addClass('animate');
						$('.header--post').find('.logo').addClass('bounce animated');
					}
				}

				else {
					$('.header--post').removeClass('animate');
					$('.header--main').removeClass('animate');
					$('.header--post').find('.logo').removeClass('bounce animated');
				}
			}
		}

		$('.share-buttons a').on('click', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');

			var w = window.open(url,'Share','width=550,height=400');
			return false;
		});

		$('.load').on('click', function() {

			var $action = $(this).data('action');
			var $cat = $(this).data('category');

			$.ajax({
				type: 'POST',
				url: '/more-posts',
				data: {
					action: $action,
					category: $cat
				},
				beforeSend: function () {
					$('.fill-content').css({'opacity':'0.3'});
				},
				success: function (response) {
					setTimeout(function () {
						$('.fill-content').append(response);
						$('.fill-content').css({'opacity':'1'});
					}, 500);
				}
			});
		});

		var slc = new SelectDecorator('select');

		timeoutButton();

	});

	function timeoutButton()
	{
		var stroke = document.querySelector('.stroke');
		var length = stroke.getTotalLength();

		// This logs the stroke lenght to the (devtools) console when run
		// console.log(length);

		// This sets the strokes dasharray and offset to be exactly the length of the stroke
		// stroke.style.strokeDasharray = length;
		// stroke.style.strokeDashoffset = length;

		// Toggle the animation-play-state of the ".stroke" on clicking the ".icon" -container
		var animationDiv = document.querySelector('.stroke');
		var clickDiv = document.querySelector('.play-btn');
		var play = document.querySelector('.play');
		var pause = document.querySelector('.pause');

		var isPausedByUser = false;

		// prefixer helper function
		var pfx = ['webkit', 'moz', 'MS', 'o', ''];
		function prefixedEventListener(element, type, callback) {
		    for (var p = 0; p < pfx.length; p++) {
		        if (!pfx[p]) type = type.toLowerCase();
		        element.addEventListener(pfx[p]+type, callback, false);
		    }
		}

		function hasClass(element, cls) {
			return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
		}

		$(document).scroll(function()
		{
			// console.log(isPausedByUser);

			if (isPausedByUser)
				return;

			if ( $('#next-post').isOnScreen() )
			{
				animationDiv.setAttribute('class', 'stroke animate');
				play.classList.add('hidden');
				pause.classList.remove('hidden');
				animationDiv.style.webkitAnimationPlayState = 'running';
				animationDiv.style.animationPlayState = 'running';
			}
			else
			{	
				animationDiv.style.webkitAnimationPlayState = 'paused';
				animationDiv.style.animationPlayState = 'paused';
				animationDiv.setAttribute('class', 'stroke reset');  
				pause.classList.add('hidden'); 
				play.classList.remove('hidden'); 
			} 
		});

		clickDiv.addEventListener('click', function() {
			if (
				animationDiv.style.webkitAnimationPlayState === 'paused' || 
				animationDiv.style.webkitAnimationPlayState === '' || 
				animationDiv.style.animationPlayState === 'paused' || 
				animationDiv.style.animationPlayState === '')
			{
				play.classList.add('hidden');
				pause.classList.remove('hidden');
				animationDiv.style.webkitAnimationPlayState = 'running';
				animationDiv.style.animationPlayState = 'running';
				isPausedByUser = false;
			}
			else if (animationDiv.style.webkitAnimationPlayState === 'running' || animationDiv.style.animationPlayState === 'running')
			{
				pause.classList.add('hidden');
				play.classList.remove('hidden');
		    	animationDiv.style.webkitAnimationPlayState = 'paused';
		    	animationDiv.style.animationPlayState = 'paused';
		    	isPausedByUser = true;
			}
		});

		prefixedEventListener(animationDiv,'AnimationEnd',function(e)
		{
			var link = $('#next-post-url').attr('href');
			window.location.href = link; 
		});
	}

	$.fn.isOnScreen = function(){

		var win = $(window); 

		var viewport = {
			top : win.scrollTop(),
			left : win.scrollLeft()
		};
		viewport.right = viewport.left + win.width();
		viewport.bottom = viewport.top + win.height();

		var bounds = this.offset();
		bounds.right = bounds.left + this.outerWidth();
		bounds.bottom = bounds.top + this.outerHeight();

		return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

	};
 
})(jQuery);