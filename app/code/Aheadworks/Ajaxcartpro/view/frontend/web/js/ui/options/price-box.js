/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'priceBox'
], function($) {
    "use strict";
    $.widget('awacp.acpPriceBox', {
        options: {
            priceConfig: ''
        },

        /**
         * Creates widget
         * @private
         */
        _create: function () {
            this._reloadPrice();
        },

        /**
         * Reload price
         * @private
         */
        _reloadPrice: function () {
            var priceBoxes = this.element;

            priceBoxes = priceBoxes.filter(function(index, elem){
                return !$(elem).find('.price-from').length;
            });
            priceBoxes.priceBox({'priceConfig':this.options.priceConfig});
            priceBoxes.trigger('reloadPrice');
        }
    });

    return $.awacp.acpPriceBox;
});