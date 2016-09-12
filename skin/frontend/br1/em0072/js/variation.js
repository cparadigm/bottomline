/**
 * EMThemes
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */

(function($) {

$(document).ready(function() {
	$('.color-picker').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val('#'+hex);
			$(el).css('backgroundColor', '#' + hex);
			$(el).ColorPickerHide();
		},
		onChange: function(hsb, hex) {
			var el = this.data('colorpicker').el;
			$(el).val('#'+hex);
			$(el).css('backgroundColor', '#' + hex);
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);            
	});
});

})(jQuery);
