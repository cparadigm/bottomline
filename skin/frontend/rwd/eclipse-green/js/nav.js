
jQuery.extend( jQuery.easing,
{

	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},

});

(function(jQuery){
     jQuery.fn.extend({
         accordion: function() {       
            return this.each(function() {
		
				function activate(el,effect){
					
					
					jQuery(el).siblings( panelSelector )[(effect || activationEffect)](((effect == "show")?activationEffectSpeed:false),function(){
jQuery(el).parents().show();
					
					});
					
				}
				
            });
        }
    }); 
})(jQuery);

jQuery(function($) {
	$('.accordion').accordion();
	
	$('.accordion').each(function(index){
		var activeItems = $(this).find('li.active');
		activeItems.each(function(i){
			$(this).children('ul').css('display', 'block');
			if (i == activeItems.length - 1)
			{
				$(this).addClass("current");
			}
		});
	});
	
});



/*-------- End Nav js -------------------*/	