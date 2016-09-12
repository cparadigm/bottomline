(function(a) {
    function b() {
    }
    for (var c = "assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","), d; !!(d = c.pop()); ) {
        a[d] = a[d] || b;
    }
})
        (function() {
            try {
                console.log();
                return window.console;
            } catch (a) {
                return (window.console = {});
            }
        }());

VarienForm.prototype.submit = VarienForm.prototype.submit.wrap(function(submit) {
    if (this.validator && this.validator.validate()) {
        var test = $$('.giftselect-btn-cart');
        $$('.giftselect-btn-cart').each(function(element) {
            element.disabled = true;
            element.addClassName('disabled');
        });
        // kept for backwards compatibility to customers who have custom templates
        if ($('giftselect-products-list')) {
            console.log('ID BASED giftselect-products-list was DEPRICATED. You are using an older version of the selectgifts.phtml file. Please update your theme version of the file');
            if ($('giftselect-btn-cart')) {
                $('giftselect-btn-cart').each(function(element) {
                    element.disabled = true;
                    element.addClassName('disabled');
                });
            }
            $('giftselect-products-list').childElements().each(function(childElement) {
                if (childElement.nodeName == 'FORM') {
                    var elements = Form.getElements(childElement);
                    elements.each(function(element) {
                        if (element.nodeName == 'BUTTON') {
                            element.disabled = true;
                            element.addClassName('disabled');
                        }
                    });
                }
            });
        }
    }
    submit();
});
