var Admin_JsWebFormsResultModal;
require(['jquery', 'jquery/ui', 'Magento_Ui/js/modal/modal'], function () {
    Admin_JsWebFormsResultModal = function (title, url) {
        var dialog = jQuery("#webforms-dialog");
        if (jQuery("#webforms-dialog").length == 0) {
            dialog = jQuery('<div id="webforms-dialog" style="display:hidden"></div>').appendTo('body');
        }

        jQuery.ajax({
            url: url,
            type: 'GET',
            showLoader: true,
            data: {}
        }).done(jQuery.proxy(function (data) {
            dialog.html(data);
            dialog.modal({title: title, innerScroll: true}).modal('openModal');
        }, this));

        //prevent the browser to follow the link
        return false;
    }
})

var setResultStatus;
require(['prototype'], function () {
    setResultStatus = function (el, url) {
        // hide action buttons
        el.up().select('.grid-button-action').invoke('hide');
        el.up().select('.request-progress').invoke('show');
        new Ajax.Request(url, {
            onSuccess: function (transport) {
                el.up().select('.request-progress').invoke('hide');

                var response = transport.responseText.evalJSON(true);
                var indicator = el.up().select('.grid-status')[0];

                indicator.update(response.text);
                indicator.removeClassName('approved');
                indicator.removeClassName('notapproved');
                indicator.removeClassName('pending');

                switch (response.status) {
                    case -1:
                        indicator.addClassName('notapproved');
                        el.up().select('.approve').invoke('show');
                        break;
                    case 1:
                        indicator.addClassName('approved');
                        el.up().select('.complete').invoke('show');
                        el.up().select('.reject').invoke('show');
                        break;
                    case 2:
                        indicator.addClassName('approved');
                        break;
                }
            },
            onFailure: function (transport) {
                el.up().select('.grid-button-action').invoke('show');
                el.up().select('.request-progress').invoke('hide');
                alert('Error occured during request.');
            }
        });
    }
})