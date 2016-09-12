/**
 * EMThemes
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */

(function($) {

EM_Theme = {
	CROSSSELL_ITEM_WIDTH: 250,
	CROSSSELL_ITEM_SPACING: 90,
	UPSELL_ITEM_WIDTH: 250,
	UPSELL_ITEM_SPACING: 90
};



if (typeof EM == 'undefined') EM = {};
if (typeof EM.tools == 'undefined') EM.tools = {};


var isMobile = /iPhone|iPod|iPad|Phone|Mobile|Android|webOS|iPod|BlackBerry|hpwos/i.test(navigator.userAgent);
var isPhone = /iPhone|iPod|Phone|Mobile|Android|webOS|iPod|BlackBerry/i.test(navigator.userAgent);



var domLoaded = false, 
	windowLoaded = false;

/**
 * Decorate Product Tab
 */ 
EM.tools.decorateProductCollateralTabs = function() {
	$(document).ready(function() {
		$('.product-collateral').addClass('tab_content').each(function(i) {
			$(this).wrap('<div class="tabs_wrapper collateral_wrapper" />');
			var tabs_wrapper = $(this).parent();
			var tabs_control = $(document.createElement('ul')).addClass('tabs_control').insertBefore(this);
			
			$('.box-collateral', this).addClass('tab-item').each(function(j) {
				var id = 'box_collateral_'+i+'_'+j;
				$(this).addClass('content_'+id);
				tabs_control.append('<li><h2><a href="#'+id+'">'+$('h2', this).html()+'</a></h2></li>');
			});
			
			initToggleTabs(tabs_wrapper);
		});
	});
};


/**
 * Fix iPhone/iPod auto zoom-in when text fields, select boxes are focus
 */
function fixIPhoneAutoZoomWhenFocus() {
	var viewport = $('head meta[name=viewport]');
	if (viewport.length == 0) {
		$('head').append('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>');
		viewport = $('head meta[name=viewport]');
	}	
	var old_content = viewport.attr('content');	
	function zoomDisable(){
		viewport.attr('content', old_content + ', user-scalable=0');
	}
	function zoomEnable(){
		viewport.attr('content', old_content);
	}
	
	$("input[type=text], textarea, select").mouseover(zoomDisable).mousedown(zoomEnable);
}
/**
 * Adjust elements to make it responsive
 *
 * Adjusted elements:
 * - Image of product items in products-grid scale to 100% width
 */
function responsive() {
	
	// resize products-grid's product image to full width 100% {{{
	var position = $('.products-grid .item').css('position');
	if (position != 'absolute' && position != 'fixed' && position != 'relative')
		$('.products-grid .item').css('position', 'relative');
		
	var img = $('.products-grid .item .product-image img');
//	img.each(function() {
//		img.data({
//			'width': $(this).width(),
//			'height': $(this).height()
//		})
//	});
//	img.removeAttr('width').removeAttr('height').css('width', '100%');
	
	$('.custom-logo').each(function() {
		$(this).css({
			'max-width': $(this).width(),
			'width': '100%'
		});
	});
}
window.onresize = function(){
	if (typeof em_slider!=='undefined')
        em_slider = new EM_Slider(em_slider.config);
	if (($('#image')!=null)&& (product_zoom != null)){
		$('#image').width(product_zoom.imageDim.width);
        Event.stopObserving($('#zoom_in'), 'mousedown', product_zoom.startZoomIn.bind(product_zoom));
        Event.stopObserving($('#zoom_in'), 'mouseup', product_zoom.stopZooming.bind(product_zoom));
        Event.stopObserving($('#zoom_in'), 'mouseout', product_zoom.stopZooming.bind(product_zoom));

        Event.stopObserving($('#zoom_out'), 'mousedown', product_zoom.startZoomOut.bind(product_zoom));
        Event.stopObserving($('#zoom_out'), 'mouseup', product_zoom.stopZooming.bind(product_zoom));
        Event.stopObserving($('#zoom_out'), 'mouseout', product_zoom.stopZooming.bind(product_zoom));

		//$('#image').height(product_zoom.imageDim.height);
		product_zoom = new Product.Zoom('image', 'track', 'handle', 'zoom_in', 'zoom_out', 'track_hint');;
	}
	initButtonAddto();
}



$(document).ready(function() {
	domLoaded = true;
	if(checkPhone){
		$("img.lazy").lazyload({
			load: function(){
				var o = $(this); 
				var li = $(this).parent().first().parent().first();
				var div_top_cart = o.height() - li.find('.actions-cart').height();
				var div_top_add_to = o.height() - li.find('.actions-addto').height();
				li.first().find('.actions-cart').css({
					'top': div_top_cart+'px'
				});
				li.find('.actions-addto').css({
					'top': div_top_add_to+'px'
				});
			}
		});
	}
	else{
		$("img.lazy").each(function(){
			$(this).attr("src", $(this).attr("data-original"));
		})
	}
	isMobile && fixIPhoneAutoZoomWhenFocus();	
	alternativeProductImage();			
	initTopButton();	
	setupReviewLink();
	if($('body').viewPC()){
		toolbar();
	}
	responsive();
	initIsotope();
	menuVertical();
});

$(window).bind('load', function() {
	windowLoaded = true;
	if(!isMobile){
		jQuery('.category-products ul.products-grid').isotope('reLayout');
	}
	em0072();
});
$(window).bind('orientationchange', function(e) {    
   if(window.orientation != 0){
        $('.store-switcher').addClass('store-switcher-landscape');
   }
});
})(jQuery);

/**
 * Change the alternative product image when hover
 */
function alternativeProductImage() {
	var tm;
	var $=jQuery;
	function swap() {
		clearTimeout(tm);
		setTimeout(function() {
			el = $(this).find('img[data-alt-src]');
			var newImg = $(el).data('alt-src');
			var oldImg = $(el).attr('src');
			$(el).attr('src', newImg).data('alt-src', oldImg);
		}.bind(this), 200);
	}	
	$('.item .product-image img[data-alt-src]').parents('.item').bind('mouseenter', swap).bind('mouseleave', swap);
}

/**
*   Slider
**/
function initSlider(e,verticals) {
	var $ = jQuery;
    var wraps;
	if (verticals == null){
		verticals=false;
        wraps = null;
    }else{
        wraps = 'circular';
    }
	
	var widthcss = $( e + ' li.item').width();
	var rightcss = $( e + ' li.item').outerWidth(true)- $( e + ' li.item').outerWidth();
	$(e).addClass('jcarousel-skin-tango');
	$(e).parent().append('<div class="slide_css">');
	$(e).parent().find('.slide_css').html('<style type="text/css">'+e+' .jcarousel-item {width:' + widthcss + 'px;margin-right:'+ rightcss +'px;}</style>');
	//jQuery('#<?php echo $idJs;?>_css').html('<style type="text/css">#<?php echo $idJs;?> .jcarousel-skin-tango .jcarousel-item {width:' + width_<?php echo $idJs;?> + 'px;}</style>');
	//$('.jcarousel-skin-tango .jcarousel-item').css('width',  width>');
	$(e).jcarousel({
		buttonNextHTML:'<a class="next" href="javascript:void(0);" title="Next"></a>',
		buttonPrevHTML:'<a class="previous" href="javascript:void(0);" title="Previous"></a>',
		scroll: 1,
		wrap: wraps,
		animation:'slow',
		vertical:verticals,
		initCallback: function (carousel) {
			var context = carousel.container.context;
			$(context).touchwipe({
				wipeLeft: function() { 
					carousel.next();
				},
				wipeRight: function() { 
					carousel.prev();
				},
				preventDefaultEvents: false
			});
			$(window).resize(function() {
				carousel.scroll(1,true);
			});
		}
	});
}

/**
*   initTopButton
**/
function initTopButton() {
	var $ = jQuery;
	// hide #back-top first
	$("#back-top").hide();

	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
}
function toolbar(){
    var $=jQuery;
    
	$('.show').each(function(){
		$(this).insertUl();
		$(this).selectUl();
	});

	$('.sortby').each(function(){
		$(this).insertUl();
		$(this).selectUl();
	});
}

/**
*   showReviewTab
**/
function showReviewTab() {
	var $ = jQuery;
	
	var reviewTab = $('.tabs_control li:contains('+ review +')');
	if (reviewTab.size()) {
		// scroll to review tab
		$('html, body').animate({
			 scrollTop: reviewTab.offset().top
		}, 500);
		 
		 // show review tab
		reviewTab.click();
	} else if ($('#customer-reviews').size()) {
		// scroll to customer review
		$('html, body').animate({ scrollTop: $('#customer-reviews').offset().top }, 500);
	} else {
		return false;
	}
	return true;
};

/**
*   setupReviewLink
**/
function setupReviewLink() {
	jQuery('.r-lnk').click(function (e) {
		if (showReviewTab())
			e.preventDefault();
	});
};

/**
*   After Layer Update
**/
window.afterLayerUpdate = function () {
    var $=jQuery;  
    if($('body').viewPC()){
		toolbar();
	}
    if(checkPhone){
		$("img.lazy").lazyload({
			load: function(){
				var o = $(this); 
				var li = $(this).parent().first().parent().first();
				var div_top_cart = o.height() - li.find('.actions-cart').height();
				var div_top_add_to = o.height() - li.find('.actions-addto').height();
				li.first().find('.actions-cart').css({
					'top': div_top_cart+'px'
				});
				li.find('.actions-addto').css({
					'top': div_top_add_to+'px'
				});
			}
		});
	}
	else{
		$("img.lazy").each(function(){
			$(this).attr("src", $(this).attr("data-original"));
		})
	}
    alternativeProductImage();
    setTimeout(function(){initIsotope(); em0072();},500);
    qs({
		itemClass: '.products-grid li.item, .products-list li.item, li.item .cate_product, .product-upsell-slideshow li.item, .mini-products-list li.item, #crosssell-products-list li.item', //selector for each items in catalog product list,use to insert quickshop image
		aClass: 'a.product-image', //selector for each a tag in product items,give us href for one product
		imgClass: '.product-image img' //class for quickshop href
	});
    
    
    
}
function initButtonAddto(){
	var $ = jQuery;
		$('.products-grid').find('.item').hover(function(){
			var o = $(this).find('.product-image').find('img'); 
			var div_top_cart = o.height() - $(this).find('.actions-cart').height();
			var div_top_add_to = o.height() - $(this).find('.actions-addto').height();
			$(this).find('.actions-cart').css({
				'top': div_top_cart+'px'
			});
			$(this).find('.actions-addto').css({
				'top': div_top_add_to+'px'
			});
		});
}
function em0072(){
	 // add randomish size classes

	var $ = jQuery;
	var checkPhone = /iPhone|iPod|Phone|Android/i.test(navigator.userAgent);
	initButtonAddto();
	if((!checkPhone)&&(!$('.page').hasClass('one-column'))){
		$(".left-container .inner-left-container").append($(".footer-container").remove());
		$(".footer-container").addClass("footer-pc");
	}
}

function initIsotope(){
	var checkPhone = /iPhone|iPod|Phone|Android/i.test(navigator.userAgent);
	if(!checkPhone){
		var itemwidth = 0;
		itemwidth = jQuery('.category-products ul.products-grid li').first().width();
		jQuery('.category-products ul.products-grid').find('.big-product').each(function(){
	      var $this = jQuery(this);
	      itemwidth = $this.width();
	      $this.width($this.width() * 2 + 10); 
	    });
		
		jQuery.Isotope.prototype._getMasonryGutterColumns = function() {
		    var gutter = this.options.masonry && this.options.masonry.gutterWidth || 0;
		        containerWidth = this.element.width();
		  
		    this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth ||
		                  // or use the size of the first item
		                  this.$filteredAtoms.outerWidth(true) ||
		                  // if there's no items, use size of container
		                  containerWidth;
	
		    this.masonry.columnWidth += gutter;
	
		    this.masonry.cols = Math.floor( ( containerWidth + gutter ) / this.masonry.columnWidth );
		    this.masonry.cols = Math.max( this.masonry.cols, 1 );
		  };
	
		  jQuery.Isotope.prototype._masonryReset = function() {
		    // layout-specific props
		    this.masonry = {};
		    // FIXME shouldn't have to call this again
		    this._getMasonryGutterColumns();
		    var i = this.masonry.cols;
		    this.masonry.colYs = [];
		    while (i--) {
		      this.masonry.colYs.push( 0 );
		    }
		  };
	
		  jQuery.Isotope.prototype._masonryResizeChanged = function() {
		    var prevSegments = this.masonry.cols;
		    // update cols/rows
		    this._getMasonryGutterColumns();
		    // return if updated cols/rows is not equal to previous
		    return ( this.masonry.cols !== prevSegments );
		  };
		 
		jQuery('.category-products ul.products-grid').isotope({
			itemSelector : '.item',
			masonry : {
				columnWidth : itemwidth,
		        gutterWidth : 12
		      },
		    layoutMode : EM_Theme.PRODUCTSGRID_POSITION_ABSOLUTE
		});
	}
}

function menuVertical() {
	var $ = jQuery;
	if($('.vnav > .menu-item-link > .menu-container > li.fix-top').length > 0){
		$('.vnav > .menu-item-link > .menu-container > li.fix-top').parent().parent().mouseover(function() {
			var $container = $(this).children('.menu-container,ul.level0');
			var $containerHeight = $container.outerHeight();
			var $containerTop = $container.offset().top;
			var $winHeight = $(window).height();
			var $maxHeight = $containerHeight + $containerTop;
			//if($maxHeight >= $winHeight){
				$setTop = $(this).parent().offset().top -  $(this).offset().top;
				if(($setTop+$containerHeight) < $(this).height()){
					$setTop  = $(this).outerHeight() - $containerHeight;
				}
			/*}else{
				$setTop = (-1);
			}*/
			var $grid = $(this).parents('.em_nav').first().parents().first();
			$container.css('top', $setTop);
			if($maxHeight < $winHeight){
				$('.vnav ul.level0,.vnav > .menu-item-link > .menu-container').first().css('top', $setTop-9 +'px');
			}
			
		});
		$('.vnav .menu-item-link > .menu-container,.vnav ul.level0').parent().mouseout(function() {
			var $container = $(this).children('.menu-container,ul.level0');
			$container.removeAttr('style');
		});
	}
}
