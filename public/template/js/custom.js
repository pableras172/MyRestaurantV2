/**
	Template Name 	 : swigo
	Author			 : DexignZone
	Version			 : 1.1
	Author Portfolio : https://themeforest.net/user/dexignzone/portfolio
	
	Core script to handle the entire theme and core functions
**/

var swigo = function(){
	'use strict';
	
	var screenWidth = $( window ).width();

	// loader
	var loader = function(){
		setTimeout(function(){
			jQuery('#loading-area').fadeOut(500, function() {
				// Asegurar que permanezca oculto después del fadeOut
				jQuery(this).css('display', 'none').remove();
			});
		},1500);
	}
	/* NiceSelect Function*/
	var handleNiceSelect = function(){
		$('select:not(.ignore)').niceSelect();
	}

	/* Cart Function*/
	var cartButton = function(){
		$(".item-close").on('click',function(){
			$(this).closest(".cart-item").hide('500');
		});
		$('.cart-btn').on('click',function(){
			$(".cart-list").slideToggle('slow');
		})	
	}

	/* Box Hover ============ */
	var handleBoxHover = function(){
		jQuery('.box-hover').on('mouseenter',function(){
			var selector = jQuery(this).parent().parent();
			selector.find('.box-hover').removeClass('active');
			jQuery(this).addClass('active');
		});
	}

	// Drawer 
	var handledrawer = function(){
		$('.user-btn').on('click',function(){
			$('#drawer1').toggleClass('drawer-show');
			$('.menu-backdrop').toggleClass("show");
			$('body').css({overflow : 'hidden'});
		});
		$('.btn-close').on('click',function(){
			$('#drawer1').removeClass('drawer-show');
			$('.menu-backdrop').removeClass("show");
			$('.filter-category-sidebar').removeClass('drawer-show');
			$('.shop-filter').removeClass('show');
			$('body').css({overflow : 'auto'});
		});
		$('.btn-close2').on('click',function(){
			$('#drawer2').removeClass('drawer-show');
			$('body').css({overflow : 'hidden'});
		});
		$('#register').on('click',function(){
			$('#drawer2').addClass('drawer-show');
		});
		$('#register2').on('click',function(){
			$('#drawer2').removeClass('drawer-show')
		});
		$('#filter-button').on('click',function(){
			$('.filter-category-sidebar').toggleClass('drawer-show');
			$('.menu-backdrop').toggleClass("show");
		});
		$('#filter-button2').on('click',function(){
			$('.shop-filter').toggleClass('show');
		});
	}

	/* toogle Function*/
	var handleMenuToggle = function(){
		$('.togglebtn').on('click',function(){
			$(this).toggleClass('open');
			$('.header-nav').toggleClass('open');
			$('.menu-backdrop').toggleClass("show");
			$('.bar1').toggleClass("rotate1");
			$('.bar2').toggleClass("opacity0");
			$('.bar3').toggleClass("rotate2");
			$('.switch-btn').toggleClass("hidden");
		})
		
		$('.menu-backdrop').on('click',function(){
			$(this).removeClass('show');
			$('.header-nav').removeClass('open');
			$('.bar1').removeClass("rotate1");
			$('.bar2').removeClass("opacity0");
			$('.bar3').removeClass("rotate2");
			$('#drawer1').removeClass('drawer-show');
			$('#drawer2').removeClass('drawer-show');
			$('.contact-sidebar').removeClass('open');
			$('.filter-category-sidebar').removeClass('drawer-show');
			$(".modal-detail").css("display", "none");
			$('body').css({overflow : 'auto'});
		})
		
		$('.menu-btn').on('click',function(){
			$('.contact-sidebar').toggleClass('open');
			$('.menu-backdrop').toggleClass("show");
		});
	}

	/* submenu Function*/
	var submenutoggle = function(){
		$('.sub-menu-down').on('click',function(){
			$(this).toggleClass('active');
			$(this).children('.sub-menu').toggleClass('show');
			if($('.navbar > li .sub-menu').hasClass('show')){
				$('.navbar > li .sub-menu').removeClass('show');
				$(this).children('.sub-menu').toggleClass('show');
			}
			if($('.sub-menu-down').hasClass('active')){
				$('.sub-menu-down').removeClass('active');
				$(this).toggleClass('active');
			}
		})
	}

	/* stickyheader Function*/
	var stickyheader = function(){
		$(window).scroll(function() {
			if ($(this).scrollTop() > 1){  
				$('header').addClass("is-fixed");
			}
			else{
				$('header').removeClass("is-fixed");
			}
		});
	}
	// Date picker
	var handlePickdate = function(){
		if(jQuery('#datePickerOnly').length > 0 ){
		$('#datePickerOnly').pickadate()
		}
	}

	/* Pointer Function*/

	var handleCursorEffect = function(){
		const cursor = document.querySelector("#cursor");
		const cursorBorder = document.querySelector("#cursor-border");
		
		// Si no existen los elementos, no hacer nada
		if (!cursor || !cursorBorder) {
			return;
		}
		
		const cursorPos = { x: 0, y: 0 };
		const cursorBorderPos = { x: 0, y: 0 };
	
		document.addEventListener("mousemove", (e) => {
		cursorPos.x = e.clientX;
		cursorPos.y = e.clientY;
	
		cursor.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
		});
	
		requestAnimationFrame(function loop() {
		const easting = 8;
		cursorBorderPos.x += (cursorPos.x - cursorBorderPos.x) / easting;
		cursorBorderPos.y += (cursorPos.y - cursorBorderPos.y) / easting;
	
		cursorBorder.style.transform = `translate(${cursorBorderPos.x}px, ${cursorBorderPos.y}px)`;
		requestAnimationFrame(loop);
		});
	
		document.querySelectorAll("[data-cursor]").forEach((item) => {
			item.addEventListener("mouseover", (e) => {
				if (item.dataset.cursor === "pointer") {
				cursorBorder.style.backgroundColor = "rgba(255, 255, 255, .6)";
				cursorBorder.style.setProperty("--size", "30px");
				}
				if (item.dataset.cursor === "pointer2") {
				cursorBorder.style.backgroundColor = "white";
				cursorBorder.style.mixBlendMode = "difference";
				cursorBorder.style.setProperty("--size", "80px");
				}
			});
			item.addEventListener("mouseout", (e) => {
				cursorBorder.style.backgroundColor = "unset";
				cursorBorder.style.mixBlendMode = "unset";
				cursorBorder.style.setProperty("--size", "50px");
			});
		});
	}

	var handleScrollTop = function(){

		var progressPath = document.querySelector('.progress-wrap path');
		var pathLength = progressPath.getTotalLength();
		progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
		progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
		progressPath.style.strokeDashoffset = pathLength;
		progressPath.getBoundingClientRect();
		progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';		
		var updateProgress = function () {
			var scroll = $(window).scrollTop();
			var height = $(document).height() - $(window).height();
			var progress = pathLength - (scroll * pathLength / height);
			progressPath.style.strokeDashoffset = progress;
		}
		updateProgress();
		$(window).scroll(updateProgress);	
		var offset = 50;
		var duration = 550;
		jQuery(window).on('scroll', function() {
			if (jQuery(this).scrollTop() > offset) {
				jQuery('.progress-wrap').addClass('active-progress');
			} else {
				jQuery('.progress-wrap').removeClass('active-progress');
			}
		});				
		jQuery('.progress-wrap').on('click', function(event) {
			event.preventDefault();
			jQuery('html, body').animate({scrollTop: 0}, duration);
			return false;
		})
	}

	/* Password Show / Hide */
	var handleShowPass = function(){
		jQuery('.show-pass').on('click',function(){
			var inputType = jQuery(this).parent().find('.dzPassword');
			if(inputType.attr('type') == 'password')
			{
				inputType.attr('type', 'text');
				jQuery(this).addClass('active');
			}
			else
			{
				inputType.attr('type', 'password');
				jQuery(this).removeClass('active');
			}
		});
	}

	var handleDzDrawer = function(){
		$('.dz-drawer-open').on('click',function(){
			var el = $(this).attr('data-drawer');
			$('#'+el).toggleClass('show');
		})
		$('.dz-drawer-close').on('click',function(){
			var el = $(this).attr('data-drawer');
			$('#'+el).removeClass('show');
		})
	}

	/* Magnific Popup ============ */
	var MagnificPopup = function(){
		
		if(jQuery('.mfp-gallery').length > 0){
			/* magnificPopup function */
			jQuery('.mfp-gallery').magnificPopup({
				delegate: '.mfp-link',
				type: 'image',
				tLoading: 'Loading image #%curr%...',
				mainClass: 'mfp-img-mobile',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				},
				image: {
					tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
					titleSrc: function(item) {
						return item.el.attr('title') + '<small></small>';
					}
				}
			});
			/* magnificPopup function end */
		}
		
		if(jQuery('.mfp-video').length > 0){
			/* magnificPopup for Play video function */		
			jQuery('.mfp-video').magnificPopup({
				type: 'iframe',
				iframe: {
					markup: '<div class="mfp-iframe-scaler">'+
							'<div class="mfp-close"></div>'+
							'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
							'<div class="mfp-title">Some caption</div>'+
							'</div>'
				},
				callbacks: {
					markupParse: function(template, values, item) {
						values.title = item.el.attr('title');
					}
				}
			});	
		}

		if(jQuery('.popup-youtube, .popup-vimeo, .popup-gmaps').length > 0){
			/* magnificPopup for Play video function end */
			$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
				disableOn: 700,
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: true,
			});
		}
	}

	/* Masonry Box ============ */
	var masonryBox = function(){
		/* masonry by  = bootstrap-select.min.js */
		if(jQuery('#masonry, .masonry').length > 0)
		{
			jQuery('.filters li').removeClass('active');
			jQuery('.filters li:first').addClass('active');
			var self = jQuery("#masonry, .masonry"); 
			var filterValue = "";
	 
			if(jQuery('.card-container').length > 0)
			{
				var gutter = (self.data('gutter') === undefined)?0:self.data('gutter');
				gutter = parseInt(gutter);

				var columnWidthValue = (self.attr('data-column-width') === undefined)?'':self.attr('data-column-width');
				if(columnWidthValue != ''){columnWidthValue = parseInt(columnWidthValue);}
					filter: filterValue,
					self.masonry({
						gutter: gutter,
						columnWidth:columnWidthValue, 
						//columnWidth:3, 
						//gutterWidth: 15,
						isAnimated: true,
						itemSelector: ".card-container",
						//horizontalOrder: true,
						//fitWidth: true,
						stagger: 30
						//containerStyle: null
						//percentPosition: true
					});
					
			} 
		}
		if(jQuery('.filters').length)
		{
			jQuery(".filters li:first").addClass('active');
			
			jQuery(".filters").on("click", "li", function() {
				
				jQuery('.filters li').removeClass('active');
				jQuery(this).addClass('active');
				
				var filterValue = $(this).attr("data-filter");
				self.isotope({ 
					filter: filterValue,
					masonry: {
						gutter: gutter,
						columnWidth: columnWidthValue,
						isAnimated: true,
						itemSelector: ".card-container"
					}
				});
			});
		}
		/* masonry by  = bootstrap-select.min.js end */

		$('.filters li').on('click',function(){
			$('li').removeClass('active');
			$(this).addClass('active');
		});
	}
	/* heartblast */
	var handleheartblast = function(){
		$('.bg-heart').on('click',function(){
			$(this).toggleClass('heart-blast')
		});
	}

	/* accordionFaq2 Function*/
	var handleaccordionFaq = function(){
		$('.accordion-button').on('click',function(){
			$('.accordion-collapse').slideUp();
			if($(this).hasClass('collapsed')){
				$('.accordion-button').addClass('collapsed');
				$(this).removeClass('collapsed');
				$(this).parent().next().slideDown();
			}else{
				$('.accordion-button').addClass('collapsed');
				$(this).addClass('collapsed');
				$(this).parent().next().slideUp();
			}
		})		
	}

	/* Range ============ */
	var handleNouiSlider = function(){
		if($("#slider-tooltips").length > 0 ) {
			var tooltipSlider = document.getElementById('slider-tooltips');
			noUiSlider.create(tooltipSlider, {
				start: [10, 70],
				connect: true,
				tooltips: [wNumb({decimals: 1}), true],
				range: {
					'min': 0,
					'max': 100,
				}
			});
		}
	}
	 
	// Menudeatilmodal
	var handlemenumodal = function(){
		$('.menuDetailModal').on('click' ,function(){
			$(".modal-detail").css("display", "block");
			$('.menu-backdrop').toggleClass("show");
		});
		$('.modal-detail').on('click', function(){
		    $(".modal-detail").css("display", "none");
		    $('.menu-backdrop').removeClass("show");
		});
		$('.modal-content').on('click', function(event){
		    event.stopPropagation(); // Stop the click event from propagating
		});
	}

	// Touchspin
	var handletouchspin = function(){
		
		function incrementValue(e) {
			e.preventDefault();
			var fieldName = $(e.target).data('field');
			var parent = $(e.target).closest('div');
			var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);
		
			if (!isNaN(currentVal)) {
				parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
			} else {
				parent.find('input[name=' + fieldName + ']').val(0);
			}
			}
		
			function decrementValue(e) {
			e.preventDefault();
			var fieldName = $(e.target).data('field');
			var parent = $(e.target).closest('div');
			var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);
		
			if (!isNaN(currentVal) && currentVal > 0) {
				parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
			} else {
				parent.find('input[name=' + fieldName + ']').val(0);
			}
			}
		
			$('.input-group').on('click', '.button-plus', function(e) {
			incrementValue(e);
			});
		
			$('.input-group').on('click', '.button-minus', function(e) {
			decrementValue(e);
		});
	}

	// Shoppage
	var handleshoppage = function(){
		$('.create-an-account').on('click',function(){
			$('#create-an-account').slideToggle(300)
		})
		$('.different-address').on('click',function(){
			$('#different-address').slideToggle(300)
		})
		$('.tabs-style-1 > li > .nav-link').on('click',function(){
			$('.tabs-style-1 > li > .nav-link').removeClass('active')
			$(this).addClass('active')
		})
		$('.web-design-1').on('click',function(){
			$('#web-design-1').css("display", "block");
			$('#developement-1').css("display", "none");
			$('#graphic-design-1').css("display", "none");
		})
		$('.graphic-design-1').on('click',function(){
			$('#graphic-design-1').css("display", "block");
			$('#developement-1').css("display", "none");
			$('#web-design-1').css("display", "none");
		})
		$('.developement-1').on('click',function(){
			$('#developement-1').css("display", "block");
			$('#web-design-1').css("display", "none");
			$('#graphic-design-1').css("display", "none");
		})
	}


	var handleCurrentActive = function() {
		for (var nk = window.location,
			o = $("ul.navbar a").filter(function() {
				
				return this.href == nk;
				
			})
			.addClass("active")
			.parent()
			.addClass("active");;) 
		{
			
			if (!o.is("li")) break;
			
			o = o.parent()
				.addClass("show")
				.parent('li')
				.addClass("active");
		}
	}

	/* Function ============ */
	return {
		init:function(){
			loader();
			handleNiceSelect();
			cartButton();
			handleMenuToggle();
			submenutoggle();
			handlePickdate();
			stickyheader();
			handleBoxHover();
			handledrawer();
			handleShowPass();
			handleCursorEffect();
			handleScrollTop();
			handleDzDrawer();
			MagnificPopup();
			masonryBox();
			handleheartblast();
			handleaccordionFaq();
			handleNouiSlider();
			handlemenumodal();
			handletouchspin();
			handleshoppage();
			handleCurrentActive();
		},

		load:function(){
			masonryBox();
		},
		
		resize:function(){
		}
	}
	
}();

/* Document.ready Start */	
jQuery(document).ready(function() {
	swigo.init();
});
/* Document.ready END */

/* Window Resize START */
jQuery(window).on('resize',function () {
	swigo.resize();
});
/*  Window Resize END */

/* Window Load START */
jQuery(window).on('load',function () {
	
	swigo.load();
	
	// Ya se maneja en la función loader() llamada desde init()
	// setTimeout(function(){
	// 	jQuery('#loading-area').fadeOut();
	// }, 1500);
	
	if(jQuery('#loading-area2').length > 0){
		let isVisible = false;
		
		function handleVisibilityChange() {
			if (!isVisible && !document.hidden) {
				
				setTimeout(function (){
					jQuery('#loading-area2').addClass('active');
					
					setTimeout(function() {
						jQuery('#loading-area2').fadeOut();
					}, 1200);
					
				}, 700);
				
				isVisible = true;
			}
		}
		document.addEventListener("visibilitychange", handleVisibilityChange);
		
		if ($("#loading-area2").is(":visible")) {
			handleVisibilityChange();
		}
	}
	
	if(jQuery('#loading-area3').length > 0){
		let isVisible = false;
		
		function handleVisibilityChange() {
			if (!isVisible && !document.hidden) {
				
				setTimeout(function() {
					jQuery('.text').addClass('show');
				}, 100);
				setTimeout(function (){
					jQuery('#loading-area3').addClass('active');
				}, 1500);
				setTimeout(function() {
					jQuery('#loading-area3').fadeOut();
				}, 2000);
				
				isVisible = true;
			}
		}
		document.addEventListener("visibilitychange", handleVisibilityChange);
		
		if ($("#loading-area3").is(":visible")) {
			handleVisibilityChange();
		}
	}

});
/*  Window Load END */

