/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'Aheadworks_Ajaxcartpro/js/config',
    'Aheadworks_Ajaxcartpro/js/update'
], function($, awAcpConfig) {
    "use strict";

    $.widget('awacp.checkoutCartUpdate', $.awacp.update, {
        _update: function(content) {
            window.setTimeout(function() {
                $(awAcpConfig.uiSelector).trigger('awacpAction:close');
                location.reload();
            }, 5000);
        }
    });

    return $.awacp.checkoutCartUpdate;
});
