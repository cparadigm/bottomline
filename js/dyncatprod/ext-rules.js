Object.extend(VarienRulesForm.prototype, {
    hideParamInputField: function(container, event) {
        var relatedTarget = event.relatedTarget;
        if (relatedTarget) {
            if (relatedTarget.classList[0] == 'freetext') {
                return;
            }
        }
        Element.removeClassName(container, 'rule-param-edit');
        var label = Element.down(container, '.label'), elem;

        if (!container.hasClassName('rule-param-new-child')) {
            elem = Element.down(container, '.element-value-changer');
            if (elem && elem.options) {
                var selectedOptions = [];
                for (i = 0; i < elem.options.length; i++) {
                    if (elem.options[i].selected) {
                        selectedOptions.push(elem.options[i].text);
                    }
                }

                var str = selectedOptions.join(', ');
                label.innerHTML = str != '' ? str : '...';
//              if (elem && elem.selectedIndex>=0) {
//                  var str = elem.options[elem.selectedIndex].text;
//                  label.innerHTML = str!='' ? str : '...';
//              }
            }
            elem = Element.down(container, 'input.input-text');
            if (elem && elem.value != '') {
                var str = elem.value.replace(/(^\s+|\s+$)/g, '');
                elem.value = str;
                if (str == '') {
                    str = '...';
                } else if (str.length > 30) {
                    str = str.substr(0, 30) + '...';
                }
                label.innerHTML = str.escapeHTML();
            }
        } else {
            elem = Element.down(container, '.element-value-changer');
            if (elem.value) {
                this.addRuleNewChild(elem);
            }
            elem.value = '';
        }

        if (elem && elem.id && elem.id.match(/__value$/)) {
            this.hideChooser(container, event);
            this.updateElement = null;
        }

        this.shownElement = null;
    },
    onAddNewChildComplete: function(new_elem) {
        if (this.readOnly) {
            return false;
        }

        $(new_elem).removeClassName('rule-param-wait');
        var elems = new_elem.getElementsByClassName('rule-param');
        if (elems && elems[1]) {
            this.updateElement = elems[1];
        }
        for (var i = 0; i < elems.length; i++) {
            this.initParam(elems[i]);
        }
    },
});

function valueChangedInSelect(e) {
    var selected = e.srcElement.selectedIndex >= 0 ? e.srcElement.options[e.srcElement.selectedIndex] : undefined;
    var id = e.srcElement.id;
    var valueElm = id.replace("operator", "value");
    var calendarimg = $(valueElm+'_trig');
    if (selected.value == '<D') {
        calendarimg.hide();
        if(!isNaN(parseInt($(valueElm).value))){
            $(valueElm).value = 0;
        }
    } else {
        calendarimg.show();
    }
}

function bindSelectChange(elm) {
    var id = $(elm).id;
    var operator = id.replace("value", "operator");
    if ($(operator)) {
        $(operator).observe('change', valueChangedInSelect);
    }
}