/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'Aheadworks_Ajaxcartpro/js/config',
    'jquery/ui',
    'magnificPopup'
], function($, awAcpConfig) {
    "use strict";

    $.widget('awacp.acpDialog', {
        actionId: '',
        isShown: false,
        redirectOnContinueClick: false,
        options: {
            actionCancel: awAcpConfig.uiActionCancelSelector,
            actionContinue: awAcpConfig.uiActionContinueSelector,
            actionSubmit: awAcpConfig.uiActionSubmitSelector,
            actionWait: awAcpConfig.uiActionWaitSelector,
            actionCheckout: awAcpConfig.uiActionCheckoutSelector,
            updateBlock: awAcpConfig.uiUpdateSelector,
            relatedBlock: awAcpConfig.uiRelatedSelector,
            progressBlock: awAcpConfig.uiProgressSelector,
            popupMainClass: 'aw-acp-popup-container aw-acp-popup-container--zoom-in'
        },
        _create: function() {
            var eventBind = {};
            eventBind['click ' + this.options.actionCancel] = this.onCancelClick;
            eventBind['click ' + this.options.actionContinue] = this.onContinueClick;
            eventBind['click ' + this.options.actionCheckout] = this.onCheckoutClick;
            this._on(eventBind);

            this.element.on('awacpAction:close', $.proxy(this._close, this));
            this.element.on('awacpAction:beforeFire', $.proxy(this._beforeFire, this));
            this.element.on('awacpAction:afterUpdate', $.proxy(this._afterUpdate, this));
        },
        _beforeFire: function(event, actionId, redirectToCatalog) {
            this.redirectOnContinueClick = redirectToCatalog ? true : false;
            if (this.isShown || this.actionId != actionId) {
                this.actionId = actionId;
                this.busy();
            }
        },
        _afterUpdate: function(event, actionId, addSuccess) {
            if (this.isShown && this.actionId == actionId) {
                this.showContent(addSuccess);
            }
        },
        busy: function() {
            this._setActions(this.options.actionWait, this.options.actionCancel);
            this.element.find(this.options.updateBlock).hide();
            this.element.find(this.options.relatedBlock).html('');
            this.element.find(this.options.progressBlock).show();
            this._open();
        },
        showContent: function(addSuccess) {
            if (addSuccess) {
                this._setActions(this.options.actionCheckout, this.options.actionContinue);
            } else {
                this._setActions(this.options.actionSubmit, this.options.actionCancel);
            }
            this.element.find(this.options.updateBlock).show();
            this.element.find(this.options.progressBlock).hide();
            this._open();
        },
        _close: function() {
            if (this.isShown) {
                $.magnificPopup.close();
            }
        },
        _open: function() {
            if (!this.isShown) {
                var self = this;
                $.magnificPopup.open({
                    items: {
                        src: awAcpConfig.uiSelector
                    },
                    callbacks: {
                        open: function() {
                            $(awAcpConfig.uiSelector).trigger('awacpDialog:open');
                            self.isShown = true;
                        },
                        close: function() {
                            $(awAcpConfig.uiSelector).trigger('awacpDialog:close');
                            self.isShown = false;
                            self.actionId = '';
                        }
                    },
                    type: 'inline',
                    removalDelay: 300,
                    mainClass: self.options.popupMainClass,
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: 'auto',
                    showCloseBtn: false
                }, 0);
            }
        },
        _setActions: function(primarySelector, secondarySelector) {
            this.element.find(awAcpConfig.uiActionSelector).hide();
            this.element.find(primarySelector).show();
            this.element.find(secondarySelector).show();
        },
        onCancelClick: function(event) {
            this._close();
            event.preventDefault();
        },
        onContinueClick: function(event) {
            if (this.redirectOnContinueClick && awAcpConfig.productCategoryUrl) {
                $(location).attr('href', awAcpConfig.productCategoryUrl);
            } else {
                this._close();
            }
            event.preventDefault();
        },
        onCheckoutClick: function(event) {
            $(location).attr('href', awAcpConfig.checkoutUrl);
            event.preventDefault();
        }
    });

    return $.awacp.acpDialog;
});
