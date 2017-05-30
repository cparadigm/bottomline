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

    $.widget('awacp.widgetAddToCart', $.awacp.action, {
        options: {
            wishListFormSelector: '#wishlist-view-form',
            formKey: ''
        },
        _create: function() {
            var self = this;
            this._super();
            this.element.one('mouseup', function() {
                if (!self.element.closest('form').is(":data('awacpCatalogAddToCart')")
                    && !self.element.closest(self.options.wishListFormSelector).length
                ) {
                    self.element.off('click');
                    self._on({
                        'click': self.onClick
                    });
                }
            });
        },
        getActionId: function() {
            return 'widget-add-to-cart-' + $.now()
        },
        onClick: function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            var postData = this.element.data('post');
            if (postData) {
                this.fire(this.getActionId(), awAcpConfig.acpAddToCartUrl, this._preparePostData(postData));
            } else if (this.element.is(":data('mageRedirectUrl')")) {
                var url = this.element.redirectUrl('option', 'url');
                if (url) {
                    this.fire(this.getActionId(), awAcpConfig.acpAddToCartUrl, this._preparePostData({action: url}));
                }
            }
        },
        _preparePostData: function(postData) {
            var result = [];
            var data = postData.data || {};
            for (var name in data) {
                result.push({
                    'name': name,
                    'value': data[name]
                });
            }
            result.push({
                name: 'action_url',
                value: postData.action
            });
            result.push({
                name: 'form_key',
                value: this.options.formKey
            });
            return result;
        }
    });

    return $.awacp.widgetAddToCart;
});
