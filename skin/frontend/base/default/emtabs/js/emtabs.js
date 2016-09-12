/**
 * EM Tabs
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */

function initToggleTabs($selector){
	if(jQuery($selector).length > 0){
		var timeout = new Array(jQuery($selector).length);
		var div = new Array(jQuery($selector).length);
		jQuery($selector).addClass('ui-tabs ui-widget ui-widget-content ui-corner-all');
		jQuery($selector).each(function(index,value){
			timeout[index] = null;
			div[index] = jQuery(this);
			div[index].addClass('ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all');
			//When page loads...
			div[index].find(".tab-item").slideUp('fast'); //Hide all content
			div[index].children('ul').find("li:first").addClass("ui-tabs-selected").slideDown('fast'); //Activate first tab
			div[index].children('ul').addClass('ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all');
			div[index].children('ul').find('li').addClass('ui-state-default ui-corner-top');	
			div[index].find(".tab-item:first").slideDown('fast'); //Show first tab content
			//On Click Event
			div[index].children('ul').find("li").click(function() {
				var currentTab = jQuery(this);
				if(currentTab.hasClass('ui-tabs-selected'))
					return false;
				if (timeout[index])
					clearTimeout(timeout[index]);
				timeout[index] = setTimeout(function() {
					timeout[index] = null;	
					// Hide old content tab
					var oldIndex = div[index].children('ul').find('li.ui-tabs-selected a').attr('href').replace(/.*?#/,'');
					div[index].find('div.content_' + oldIndex).slideToggle('fast');
					
					div[index].children('ul').find("li").removeClass("ui-tabs-selected"); //Remove any "ui-tabs-selected" class
					currentTab.addClass("ui-tabs-selected"); //Add "active" class to selected tab
					
					var activeIndex = currentTab.find("a").attr("href").replace(/.*?#/,''); //Find the href attribute value to identify the active tab + content
					div[index].find('div.content_' + activeIndex).slideToggle('fast').trigger('emtabshow'); //Fade in the active ID content
					return false;
				}, 10);
				return false;
			});	
		});
		
	}
}

document.observe("dom:loaded", function() {
	initToggleTabs('.tabs_wrapper');
});