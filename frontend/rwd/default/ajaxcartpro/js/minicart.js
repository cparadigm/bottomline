(function() {
    Minicart.prototype.updateContentOnRemoveOriginal = Minicart.prototype.updateContentOnRemove;
    Minicart.prototype.updateContentOnRemove = function(result, el) {
        this.updateContentOnRemoveOriginal(result, el);
        this.updateCartContent();
    };

    Minicart.prototype.updateContentOnUpdateOriginal = Minicart.prototype.updateContentOnUpdate;
    Minicart.prototype.updateContentOnUpdate = function(result) {
        this.updateContentOnUpdateOriginal(result);
        this.updateCartContent();
    };

    Minicart.prototype.showOverlayOriginal = Minicart.prototype.showOverlay;
    Minicart.prototype.showOverlay = function() {
        this.showAwAcpOverlay();
    };

    Minicart.prototype.hideOverlayOriginal = Minicart.prototype.hideOverlay;
    Minicart.prototype.hideOverlay = function() {};

    Minicart.prototype.updateCartContent = function() {
        var url = document.location.pathname + document.location.search;
        var parameters = {
            'actionData': "[]",
            'block[]': ['cart']
        };

        var me = this;
        AW_AjaxCartPro.connector.sendRequest(url, parameters,
            function(json) {
                if (!AW_AjaxCartPro.config.targetsToUpdate['cart'].update(json.block.cart)) {
                    document.location.reload();
                    return;
                }
                AW_AjaxCartPro.stopObservers();
                AW_AjaxCartPro.startObservers();

                me.hideAwAcpOverlay();
            },
            function(json) {
                document.location.reload();
            }
        );
    };

    Minicart.prototype.showAwAcpOverlay = function() {
        var ui = AW_AjaxCartPro.ui;
        var progressBlock = ui.blocks['progress'];
        if (progressBlock.getEnabled()) {
            ui.showBlock(progressBlock.cssSelector);
        }
    };
    Minicart.prototype.hideAwAcpOverlay = function() {
        var ui = AW_AjaxCartPro.ui;
        ui.hideBlock(ui.blocks['progress'].cssSelector);
    };
})();