/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'Aheadworks_Ajaxcartpro/js/config'
], function($, awAcpConfig) {
    "use strict";

    $.widget('awacp.update', {
        options: {
            partName: ''
        },
        _create: function() {
            $(document).on('awacpAction:addSuccess', $.proxy(this._doUpdate, this));
        },
        _doUpdate: function(event, actionId, addSuccess) {
            var self = this;
            var data = [{
                name: 'part',
                value: this.options.partName

            }];
            $(document).trigger('awacpUpdate:beforeUpdate', [actionId, addSuccess]);
            $.ajax({
                url: awAcpConfig.acpGetBlockContentUrl,
                data: $.param(data),
                type: 'post',
                dataType: 'json',
                success: function (response, status) {
                    if (status == 'success' && response.content) {
                        self._update(response.content);
                    }
                },
                complete: function() {
                    $(document).trigger('awacpUpdate:afterUpdate', [actionId, addSuccess]);
                }
            });
        }
    });

    return $.awacp.update;
});
