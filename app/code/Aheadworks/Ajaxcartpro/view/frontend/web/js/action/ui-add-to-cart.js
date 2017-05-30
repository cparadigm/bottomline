/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'Aheadworks_Ajaxcartpro/js/action'
], function($) {
    "use strict";

    $.widget('awacp.uiAddToCart', $.awacp.action, {
        _create: function() {
            this._super();
            this._on({
                'click': function(event) {
                    event.preventDefault();
                    var form = $('#' + this.element.data('form'));
                    if (form.length) {
                        var self = this;
                        var isValid = form.valid();
                        form.one('submit', function() {
                            if (isValid) {
                                self.fire(self.getActionId(), form.attr('action'), form.serializeArray());
                            }
                            return false;
                        });
                        form.submit();
                    }
                }
            });
        },
        getActionId: function() {
            return 'ui-add-to-cart-' + $.now()
        }
    });

    return $.awacp.uiAddToCart;
});
