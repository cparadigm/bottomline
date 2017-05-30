/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'Aheadworks_Ajaxcartpro/js/config'
], function($, awAcpConfig) {
    "use strict";

    $.widget('awacp.action', {
        updateBlocksActionId: '',
        uncompletedUpdates: 0,
        options: {
            uiSelector: awAcpConfig.uiSelector,
            uiUpdateSelector: awAcpConfig.uiUpdateSelector,
            uiRelatedSelector: awAcpConfig.uiRelatedSelector,
            productImageSelector: awAcpConfig.productImageSelector,
            requestParamName: awAcpConfig.requestParamName
        },
        _create: function() {
            $(document).on('awacpUpdate:afterUpdate', $.proxy(this._afterUpdate, this));
            $(document).on('awacpUpdate:beforeUpdate', $.proxy(this._beforeUpdate, this));
        },
        fire: function(actionId, url, data, redirectToCatalog) {
            $(this.options.uiSelector).trigger('awacpAction:beforeFire', [actionId, redirectToCatalog]);
            this._fire(actionId, url, data);
        },
        _fire: function(actionId, url, data) {
            var self = this;
            data.push({
                name: this.options.requestParamName,
                value: 1
            });
            $.ajax({
                url: url,
                data: $.param(data),
                type: 'post',
                dataType: 'json',
                success: function(response, status) {
                    if (status == 'success') {
                        if (response.reloadUrl) {
                            $(location).attr('href', response.reloadUrl);
                            $(self.options.uiSelector).trigger('awacpAction:close');
                        }
                        if (response.backUrl) {
                            self._fire(actionId, response.backUrl, data);
                        }
                        if (response.ui) {
                            self.updateUi(response.ui);
                            self.updateRelated(response.related);
                            self.updateBlocks(actionId, response.addSuccess || false);
                        }
                        if (response.related) {
                            self.updateRelated(response.related);
                        }
                        
                    }
                }
            });
        },
        updateUi: function(content) {
            if (content) {
                var block = $(this.options.uiSelector + ' ' + this.options.uiUpdateSelector);
                block.html(content);
                block.trigger('contentUpdated');
            }
        },
        updateRelated: function(content) {
            if (content) {
                var block = $(this.options.uiSelector + ' ' + this.options.uiRelatedSelector),
                    imageWidth;

                imageWidth = $(this.options.uiSelector).width() * 0.23;
                $(this.options.productImageSelector).css('width', imageWidth);
                block.html(content);
            }
        },
        updateBlocks: function(actionId, addSuccess) {
            if (addSuccess) {
                this.updateBlocksActionId = '';
                $(document).trigger('awacpAction:addSuccess', [actionId, addSuccess]);
                if (this.updateBlocksActionId != actionId) {
                    this._afterUpdate(null, actionId, addSuccess);
                }
            } else {
                this._afterUpdate(null, actionId, addSuccess);
            }
        },
        _beforeUpdate: function(event, actionId) {
            this.uncompletedUpdates++;
            this.updateBlocksActionId = actionId;
        },
        _afterUpdate: function(event, actionId, addSuccess) {
            this.uncompletedUpdates--;
            if (this.uncompletedUpdates <= 0) {
                $(this.options.uiSelector).trigger('awacpAction:afterUpdate', [actionId, addSuccess]);
                this.uncompletedUpdates = 0;
            }
        }
    });

    return $.awacp.action;
});
