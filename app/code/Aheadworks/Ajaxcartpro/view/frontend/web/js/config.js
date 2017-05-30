/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery'
], function($) {
    "use strict";

    var awAcpConfig = {
        requestParamName: 'aw_acp',
        /* UI */
        uiSelector: '[data-role="aw-acp-ui"]',
        uiActionSelector: '[data-action]',
        uiActionCancelSelector: '[data-action="cancel"]',
        uiActionContinueSelector: '[data-action="continue"]',
        uiActionSubmitSelector: '[data-action="submit"]',
        uiActionWaitSelector: '[data-action="wait"]',
        uiActionCheckoutSelector: '[data-action="checkout"]',
        uiUpdateSelector: '[data-role="update"]',
        uiRelatedSelector: '[data-role="related"]',
        uiProgressSelector: '[data-role="progress"]',
        productImageSelector: '.aw-acp-popup__image-wrapper > .product-image-container'
    };
    awAcpConfig.setOptions = function(options) {
        for (var optionName in options) {
            this[optionName] = options[optionName];
        }
    };

    return awAcpConfig;
});
