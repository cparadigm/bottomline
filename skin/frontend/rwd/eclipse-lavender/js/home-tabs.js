if (typeof $magik_jq != 'function'){
	if (typeof jQuery == 'undefined'){
		var msg = 'Please include jquery first. jQuery 1.5.0 is recommended!';
		if (console.log){
			console.log(msg);
		} else {
			alert(msg);
		}
	} else {
		$magik_jq = jQuery.noConflict();
	}
}


//<![CDATA[
$magik_jq(document).ready(function($){
	(function(element){
			$element = $(element);
			itemNav = $('.item-nav',$element);
			itemContent = $('.pdt-content',$element);
			btn_loadmore = $('.btn-loadmore',$element);
			ajax_url="http://www.magikcommerce.com/producttabs/index/ajax";
			catids = '39';
			label_allready = 'All Ready';
			label_loading = 'Loading ...';


			function setAnimate(el){
				$_items = $('.item-animate',el);
				$('.btn-loadmore',el).fadeOut('fast');
				$_items.each(function(i){
					$(this).attr("style", "-webkit-animation-delay:" + i * 300 + "ms;"
			                + "-moz-animation-delay:" + i * 300 + "ms;"
			                + "-o-animation-delay:" + i * 300 + "ms;"
			                + "animation-delay:" + i * 300 + "ms;");
			        if (i == $_items.size() -1) {
			            $(".pdt-list", el).addClass("play");
			            $('.btn-loadmore', el).fadeIn(i*0.3);
			        }
				});
			}
			setAnimate($('.tab-content-actived',$element));

			itemNav.click(function(){
				var $this = $(this);
				if($this.hasClass('tab-nav-actived')) return false;
				itemNav.removeClass('tab-nav-actived');
				$this.addClass('tab-nav-actived');
				var itemActive = '.'+$this.attr('data-href');
				itemContent.removeClass('tab-content-actived');
				$(".pdt-list").removeClass("play");$(".pdt-list .item").removeAttr('style');
				$('.item',$(itemActive, $element)).addClass('item-animate').removeClass('animated');
				$(itemActive, $element).addClass('tab-content-actived');

				contentLoading = $('.content-loading',$(itemActive, $element));
				isLoaded = $(itemActive, $element).hasClass('is-loaded');
				if(!isLoaded && !$(itemActive, $element).hasClass('is-loading')){
					$(itemActive, $element).addClass('is-loading');
					contentLoading.show();
					pdt_type = $this.attr('data-type');
					catid = $this.attr('data-catid');
					orderby = $this.attr('data-orderby');
					$.ajax({
						type: 'POST',
						url: ajax_url,
						data:{
							numberstart: 0,
							catid: catid,
							orderby: orderby,
							catids: catids,
							pdt_type: pdt_type
						},
						success: function(result){
							if(result.listProducts !=''){
								$('.pdt-list',$(itemActive, $element)).html(result.listProducts);
								$(itemActive, $element).addClass('is-loaded').removeClass('is-loading');
								contentLoading.remove();
								setAnimate($(itemActive, $element));
								setResult($(itemActive, $element));
							}
						},
						dataType:'json'
					});
				}else{
					$('.item', itemContent ).removeAttr('style');
					setAnimate($(itemActive, $element));
				}
			});
			function setResult(content){
				$('.btn-loadmore', content).removeClass('loading');
				itemDisplay = $('.item', content).length;
				$('.btn-loadmore', content).parent('.pdt-loadmore').attr('data-start', itemDisplay);
				total = $('.btn-loadmore', content).parent('.pdt-loadmore').attr('data-all');
				loadnum = $('.btn-loadmore', content).parent('.pdt-loadmore').attr('data-loadnum');
				if(itemDisplay < total){
					$('.load-number', content).attr('data-total', (total - itemDisplay));
     				if((total - itemDisplay)< loadnum ){
     					$('.load-number',  content).attr('data-more', (total - itemDisplay));
     				}
				}
				if(itemDisplay == total){
					$('.load-number', content).css({display: 'none'});
					$('.btn-loadmore', content).addClass('loaded');
					$('.load-text', content).text(label_allready);
				}else{
					$('.load-text', content).text(label_loadmore);
				}
			}
			btn_loadmore.on('click.loadmore', function(){
				var $this = $(this);
				itemActive = '.'+$this.parent('.pdt-loadmore').attr('data-href');
				$(".pdt-list").removeClass("play");$(".pdt-list .item").removeAttr('style');
				$('.item',$(itemActive, $element)).addClass('animated').removeClass('item-animate');
				if ($this.hasClass('loaded') || $this.hasClass('loading')){
					return false;
				}else{
					$this.addClass('loading'); $('.load-text', $this).text(label_loading);
					numberstart = $this.parent('.pdt-loadmore').attr('data-start');
					catid = $this.parent('.pdt-loadmore').attr('data-catid');
					pdt_type = $this.parent('.pdt-loadmore').attr('data-type');
					orderby = $this.parent('.pdt-loadmore').attr('data-orderby');
					$.ajax({
						type: 'POST',
						url: ajax_url,
						data:{
							numberstart: numberstart,
							catid: catid,
							orderby: orderby,
							catids: catids,
							pdt_type: pdt_type
						},
						success: function(result){
							if(result.listProducts !=''){
								animateFrom = $('.item',$(itemActive, $element)).size();
								$(result.listProducts).insertAfter($('.item',$(itemActive, $element)).nextAll().last());
								setAnimate($(itemActive, $element));
								setResult($(itemActive, $element));
							}
						},
						dataType:'json'
					});
				}
				return false;
			});
	})('#magik_producttabs1');
});
//]]>


/*-------- End Product Tabs js -------------------*/