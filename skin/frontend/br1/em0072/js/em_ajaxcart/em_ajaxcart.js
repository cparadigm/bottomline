/**
 * EM AjaxCart
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */

/*
 * ajaxcart javascript;
 * update date : 1/04/2013.
 */ 
	var em_box;
	var timeoutID;
	function ajax_add(url,param)
	{
		if(typeof param == 'undefined'){
			param ='ajax_package_name=' + $('ajax_package_name').value
				+ '&ajax_layout=' + $('ajax_layout').value
				+ '&ajax_template=' + $('ajax_template').value
				+ '&ajax_skin=' + $('ajax_skin').value;
		}
		var link = url.replace('checkout','ajaxcart').replace('wishlist/index','ajaxcart/wishlist');
		var tmp		=	url.search("in_cart");
		em_box.open();
		new Ajax.Request(link, {
			parameters:param,
			onSuccess: function(data) {
				if(tmp > 0 ) {
					var host	=	find_host(url);
					window.location.href = host+'checkout/cart/';
				}
				else{
					html = data.responseText.evalJSON();
					changeHTML(html);
				}
				
				// auto close ajaxcart
				if (EM_Theme.AJAXCART_AUTOCLOSE > 0) {
					timeoutID = setTimeout(function() { 
						em_box.close();
						$('ajax_image').update('');
						$('closeLink').hide();
						$('close').hide();
						$('viewcart_button').hide();
					}, EM_Theme.AJAXCART_AUTOCLOSE*1000);
				}
			}
		});
	}

	function changeHTML(html){
		$('ajax_loading').hide();
		$('closeLink').show();
		$('close').show();
		$('ajax_content').show();
		$$('#ajax_image').each(function (el){
			el.innerHTML = html.msg;
		});

		if(html.check == 'success'){
			$('viewcart_button').show();

			$$('.top-link-cart').each(function (el){
				el.innerHTML = html.toplink;
				el.title = html.toplink;
			});

			$$('.dropdown-cart').each(function (el){
				var newElement = new Element('div');
				newElement.update(html.sidebar);
				var div = newElement.select('div')[0];
				el.update(div.innerHTML);
			});

			if(html.w_check == 1){
				var sub	=	html.w_sub;

				$$('.add-to-cart .btn-cart')[0].remove();
				$$('.add-to-cart .paypal-logo')[0].remove();
				var tmp_whish	=	$$('.add-to-cart')[0].innerHTML;
				$$('.add-to-cart')[0].update(sub.text+tmp_whish);

				if(sub.sidebar == ""){
					$$('.block-wishlist')[0].remove();
				}else{
					$$('.block-wishlist').each(function (el){
						var newElement = new Element('div');
						newElement.update(sub.sidebar);
						var div = newElement.select('div')[0];
						el.update(div.innerHTML);
					});
				}

				var $$li = $$('.header .links li');
				if ($$li.length > 0) {
					$$li.each(function(li) {
						 var a = li.down('a');
						 var title	=	a.readAttribute('title');
						if(title.search("ishlist") > 0){
							a.setAttribute("title", sub.link);
							a.update(sub.link);
						}
					});
				}
			}
		}
		else
			$('viewcart_button').hide();
		deleteItem();
	}
 
	// pre-submit callback
	function showRequest(formData, jqForm, options) {
		em_box.open();
		return true;
	} 
	 
	// post-submit callback
	function showResponse(responseText, statusText, xhr, $form)  {
		changeHTML(responseText);
		// auto close ajaxcart
		if (EM_Theme.AJAXCART_AUTOCLOSE > 0) {
			timeoutID = setTimeout(function() { 
				em_box.close();
				$('ajax_image').update('');
				$('closeLink').hide();
				$('close').hide();
				$('viewcart_button').hide();
			}, EM_Theme.AJAXCART_AUTOCLOSE*1000);
		}
	} 

	function setLocation(url){
		if(jQuery("#enable_module").val() == 1)	window.location.href = url;
		else{
			var tam		=	url.search("checkout/cart/");
			var tam_2	=	url.search("in_cart");
			if(tam > 0){
				if(tam_2 < 0)	ajax_add(url);
				else	window.location.href = url;
			}
			else	window.location.href = url;
		}
	}

	document.observe("dom:loaded", function() {
		if(jQuery("#enable_module").val() == 1) return false;
		var containerDiv = $('containerDiv');
		if(containerDiv)
			em_box = new LightboxAJC(containerDiv);
		var options = {
			beforeSubmit:  showRequest,
			success:       showResponse,
			dataType: 'json'
		}; 
		jQuery('#product_addtocart_form').ajaxForm(options);
		if(em_box){
			$$('button.btn-cart').each(function(el){
				if(el.up('form#product_addtocart_form')){
					var url	=	$('product_addtocart_form').readAttribute('action');
					var link = url.replace('checkout','ajaxcart').replace('wishlist/index','ajaxcart/wishlist');
					$('product_addtocart_form').setAttribute("action", link);
					el.onclick = function(){
						jQuery('#product_addtocart_form').submit();return false;
					}
				}
				if(el.up('form#wishlist-view-form')){
					el.onclick = function(){
						var form = $('wishlist-view-form');
						var dir_up	=	el.up('#wishlist-table tr');
						var str	=	dir_up.readAttribute('id');
						var itemId	=	str.replace("item_","");
						addWItemToCart(itemId);
					}
				}
				if(el.up('form#reorder-validate-detail')){
					el.onclick = function(){
						var url	=	$('reorder-validate-detail').readAttribute('action');
						var param	=	$('reorder-validate-detail').serialize()
									+ '&ajax_package_name=' + $('ajax_package_name').value
									+ '&ajax_layout=' + $('ajax_layout').value
									+ '&ajax_template=' + $('ajax_template').value
									+ '&ajax_skin=' + $('ajax_skin').value;

						if(param.search("ajaxcart") < 0){
							if(reorderFormDetail.submit){
								if(reorderFormDetail.validator && reorderFormDetail.validator.validate()){
									ajax_add(url,param);
								}
								return false;
							}
						}
					}
				}
			});
		}
		deleteItem();
		if($('closeLink')){
			Event.observe('closeLink', 'click', function () {
				if(timeoutID!=null)
					clearTimeout(timeoutID);
				em_box.close();
				$('ajax_image').update('');
				$('ajax_loading').show();
				$('closeLink').hide();
				$('close').hide();
				$('viewcart_button').hide();
				
			});
		}
		
		if($('close')){
			Event.observe('close', 'click', function () {
				if(timeoutID!=null)
					clearTimeout(timeoutID);
				em_box.close();
				$('ajax_image').update('');
				$('ajax_loading').show();
				$('close').hide();
				$('closeLink').hide();
				$('viewcart_button').hide();
			});
		}
		
	});

	function deleteItem(){    
		$$('a').each(function(el){
			if(el.href.search('checkout/cart/delete') != -1 && el.href.search('javascript:ajax_del') == -1){
				el.href = 'javascript:ajax_del(\'' + el.href +'\')';
			}
			if(el.up('.truncated')){
				var a	=	el.up('.truncated');
				a.observe('mouseover', function() {
					a.down('.truncated_full_value').addClassName('show');
				});
				a.observe('mouseout', function() {
					a.down('.truncated_full_value').removeClassName('show');
				});
			}
		});
	}

	function ajax_del(url){
		var check	=	$('shopping-cart-table');
		if(check){
			window.location.href =	url;
		}else{
			var tmp	=	url.search("checkout/cart/");
			var baseurl		=	url.substr(0,tmp);
			var tmp_2	=	url.search("/id/")+4;
			var tmp_3	=	url.search("/uenc/");
			var id		=	url.substr(tmp_2,tmp_3-tmp_2);
			var link	=	baseurl+'ajaxcart/index/delete/id/'+id;
			em_box.open();
			new Ajax.Request(link, {
				onSuccess: function(data) {
					var html = data.responseText.evalJSON();

					$$('.top-link-cart').each(function (el){
						el.innerHTML = html.toplink;
						el.title = html.toplink;
					});
					
					$$('.dropdown-cart').each(function (el){
						var newElement = new Element('div');
						newElement.update(html.sidebar);
						var div = newElement.select('div')[0];
						el.update(div.innerHTML);
					});

					em_box.close();
					deleteItem();
				}
			});
		}
		
	}

	function find_host(url)
	{
		var tmp		=	url.search("checkout/cart/");
		var str		=	url.substr(0,tmp)
		return str;
	}