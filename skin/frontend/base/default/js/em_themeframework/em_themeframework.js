jQuery(window).load(function () {
	var $ = jQuery;
	
	$('.em_themeframework_previewblock').each(function() {
		if (!$(this).hasClass('empty')) {
			var block = $(this).prev();
			$(this).css({
				position: 'absolute',
				left: block.position().left + (block.outerWidth(true) - block.width())/2 + 'px',
				top: block.position().top + (block.outerHeight(true) - block.height())/2 + 'px'/*,
				width: block.outerWidth() - ($(this).outerWidth() - $(this).width()) + 'px',
				height: block.outerHeight() - ($(this).outerHeight() - $(this).height()) + 'px'*/
			}).show();
		}
		else {
			$(this).show();
		}
	});
	
	$('.em_themeframework_previewarea_title').each(function() {
			var area = $(this).parent();
			$(this).css({
				position: 'absolute',
				left: area.position().left + (area.outerWidth(true) - area.width())/2 + 'px',
				top: area.position().top + (area.outerHeight(true) - area.height())/2 + 'px'
			}).show();
	});
	
});
