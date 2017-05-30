/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'Aheadworks_Ajaxcartpro/js/config',
    'Aheadworks_Ajaxcartpro/js/action'
], function($, awAcpConfig) {
    "use strict";

    $.widget('awacp.productAddToCart', $.awacp.action, {
        options: {
            bindSubmit: true,
            redirectToCatalog: false
        },
        _create: function() {
            if (this.options.bindSubmit) {
                this._super();
                this._on({
                    'submit': function(event) {
                        event.preventDefault();
                        if (!this.element.valid()) {
                            return;
                        }
                        var data = this.element.serializeArray();
                        data.push({
                            name: 'action_url',
                            value: this.element.attr('action')
                        });
                        this.fire(this.getActionId(), awAcpConfig.acpAddToCartUrl, data, this.options.redirectToCatalog);
                    }
                });
            }

        },
        getActionId: function() {
            return 'catalog-add-to-cart-' + $.now()
        }
    });

    return $.awacp.productAddToCart;
});
