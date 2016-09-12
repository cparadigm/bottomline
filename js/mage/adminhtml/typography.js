/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}

EMTypo = Class.create();
EMTypo.prototype = {
    label : null,
    name : null,
    tag : null,
    idArea : null,
    params: null,
    generalClass : null,
    requiredHtml : null,
    initialize : function(label,name,tag,generalClass,params){
        this.label = label;
        this.name = name;
        this.tag = tag;
        this.params = params;
        this.generalClass = generalClass;
        this.requiredHtml = '<span class="required">*</span>';
        this.setIdArea('typo-entity-' + this.name);
    },
    setIdArea : function(area){
        this.idArea = area;
    },
    getIdArea : function(){
        return this.idArea;
    },
    getOpenTag : function(values){
        return '<' + this.tag + this.getClass(values) + '>';
    },
    getCloseTag : function(){
        return '</' + this.tag + '>';
    },
    loadClassOption : function(){
        var html = '';
        if(this.params){
            if(typeof this.params.class_option === 'object'){
                html += '<p><label>' + this.params.class_option.label + '</label><select name="class">';
                var classes = this.params.class_option.values;
                for(var key in classes){
                    html += '<option value="' + key + '">' + classes[key] + '</option>';
                }
                html +='</select></p>';
            }
        }
        html += this.buildGeneralClassSelect();
        html += '<p><label>Custom class</label><input type="input" name="custom_class" class="input-text"/></p>';
        return html;
    },
    buildGeneralClassSelect : function(){
        var generalClass = this.generalClass;
        var list = generalClass.values_option;
        var html = '<p><label>' + generalClass.label + '</label>';
        html += '<select multiple name="general_class">';
        for(var value in list){
            html += '<option value=' + value + '>' + list[value] + '</option>';
        }
        html += '</select></p>';
        return html;
    },
    getClass : function(values){
        var classValue = [],classHtml = '';
        for(var key in values){
            if(key == 'class' || key == 'custom_class'){
                if(values[key] != '')
                    classValue.push(values[key]);
            }
        }

        if(this.params){
            if(typeof this.params.class_text === 'string'){
                classValue.push(this.params.class_text);
            }
        }

        if(values.general_class.length > 0){
            values.general_class.each(function(value){
                classValue.push(value);
            });
        }

        if(classValue.length > 0){
            classHtml = ' class="' + classValue.join(' ') + '"';
        }
        return classHtml;
    },
    validateData : function(){
        var elementsRequired = $(this.getIdArea()).select('.required-data'), flag = true;
        if(elementsRequired.length > 0){
            elementsRequired.each(function(item){
                if(item.value == ''){
                    flag = false;
                }
            });
        }
        return flag;
    },
    getValueHtml : function(){
        var values = $(this.getIdArea() + '_form').serialize(true), value;
        return this.getOpenTag(values) + values['typo_text'] + this.getCloseTag();
    },
    loadHtml : function(){
        return '<form method="POST" id="' + this.getIdArea() + '_form">' + this.loadClassOption() + this.loadContent() + '</form>';
    }
}

TextTypo = Class.create(EMTypo,{
    loadContent : function(){
        var html =  '<p><label>' + this.params.label + this.requiredHtml + '</label>' +
                    '<input type="text" name="typo_text" class="input-text required-data"/></p>';
        return html;
    }
});

HeadingTypo = Class.create(TextTypo,{
    getOpenTag : function(values){
		this.tag = values['class'];
        return '<' + this.tag + this.getClass(values) + '>';
    },
	getClass : function(values){
        var classValue = [],classHtml = '';
        
		if(values['custom_class'] != '')
			classValue.push(values['custom_class']);
        
        if(this.params){
            if(typeof this.params.class_text === 'string'){
                classValue.push(this.params.class_text);
            }
        }

        if(values.general_class.length > 0){
            values.general_class.each(function(value){
                classValue.push(value);
            });
        }

        if(classValue.length > 0){
            classHtml = ' class="' + classValue.join(' ') + '"';
        }
        return classHtml;
    }
});

TextareaTypo = Class.create(EMTypo,{
    loadContent : function(){
        return '<p><label>' + this.label + this.requiredHtml + '</label><textarea name="area_value" class="required-data" cols="70" rows="5"></textarea></p>';
    },
    getValueHtml : function(){
        var values = $(this.getIdArea() + '_form').serialize(true), value;
		value = values['area_value'].replace(/\n\r?/g, '<br />');
        return this.getOpenTag(values) + value + this.getCloseTag();
    }
});

DlTypo = Class.create(EMTypo,{
    loadContent : function(){
        return '<label>' + this.label + '</label>' +
                '<div class="def-content">' +
                    '<div class="row">' +
                        '<p><label>' + this.params.title_dt + this.requiredHtml + '</label><input type="text" name="dt" class="input-text required-data"/></p>' +
                        '<p><label> ' + this.params.title_dd + this.requiredHtml + ' </label><textarea name="dd" class="required-data" cols="70" rows="5"></textarea></p>' +
                    '</div>' +
                '</div>' +
               '<div class="action"><button id="' + this.getIdArea() + '_add-row" class="button"><span><span><span>' + this.params.button_add + '</span></span></span></button></div>';
    },
    buildRow : function(){
        return '<div class="row">' +
            '<a class="close" href="javascript:void(0);" onclick="$(this).up().remove();return false;">X</a>' +
            '<p><label>' + this.params.title_dt + '</label><input type="text" name="dt" class="input-text"/></p>' +
            '<p><label> ' + this.params.title_dd + ' </label><textarea name="dd" cols="70" rows="5"></textarea></p>' +
            '</div>';
    },
    fireAddRow : function(){
        var typography = this;
        Event.observe($(this.getIdArea() + '_add-row'),'click', function(e){
            e.preventDefault();
            var content = $$('#' + typography.getIdArea() + ' .def-content .row').last();
            content.insert({'after' : typography.buildRow()});
            return false;
        });
    },
    getValueHtml : function(){
        var values = $(this.getIdArea() + '_form').serialize(true);
        var dts = values.dt,dds = values.dd;
        var html = this.getOpenTag();
        if(typeof dts === 'string'){
            html += '<dt>' + dts + '</dt><dd>' + dds + '</dd>';
        } else {
            dts.each(function(dt,index){
                html += '<dt>' + dt + '</dt><dd>' + dds[index].replace(/\n\r?/g, '<br />') + '</dd>';
            });
        }
        html += this.getCloseTag(values);
        return html;
    }
});

TableTypo = Class.create(EMTypo,{
    loadContent : function(){
        return '<p><label>' + this.params.num_row_label + '</label><input type="text" name="' + this.name + '_num_row" id="' + this.getIdArea() + '_num_row" class="input-text"/></p>' +
               '<p><label>' + this.params.num_col_label + '</label><input type="text" name="' + this.name + '_num_col" id="' + this.getIdArea() + '_num_col" class="input-text"/></p>' +
               '<p><label>' + this.params.remove_thead_label + '</label>' +
               '<select name="remove_thead"><option value="">' + this.params.remove_thead.no + '</option><option value="1">' + this.params.remove_thead.yes + '</option></select></p>' +
               '<button class="buttons generate"><span><span><span>' + this.params.btn_generate_label + '</span></span></span></button>' +
               '<div class="table-content"></div>';
               // '</form>';
    },
    buildHead : function(numCol){
        var thead = new Element('thead');
        var tr = new Element('tr');
        for(var k = 0; k < numCol; k++){
            var th = new Element('th');
            th.appendChild(new Element('input',{name : 'thead',value : 'thead'}));
            tr.appendChild(th);
        }
        thead.appendChild(tr);
        return thead;
    },
    buildTable : function(numRow , numCol){
        var table = new Element('table');

        var tbody = new Element('tbody');

        /* Build table head */
        if(!$(this.getIdArea()).select('select[name="remove_thead"]').first().value){
            table.appendChild(this.buildHead(numCol));
        }

        /* Build table body */
        for(var i = 0; i < numRow; i++){
            tr = new Element('tr');
            for(j = 0; j < numCol; j++){
                var td = new Element('td');
                td.appendChild(new Element('input',{name : 'tbody'}));
                tr.appendChild(td);
            }
            tbody.appendChild(tr);
        }

        table.appendChild(tbody);
        return table;
    },
    onChangRemoveHead : function(){
        var typography = this;
        Event.observe($(this.getIdArea()).select('select[name="remove_thead"]').first(),'change', function(){
            if($(typography.getIdArea()).select('table').length > 0){
                var tbody = $(typography.getIdArea()).select('table tbody').first();
                if(!$(this).value){
                    tbody.insert({'before' : typography.buildHead($(typography.getIdArea() + '_num_col').value)});
                } else {
                    var thead = $(typography.getIdArea()).select('table thead').first();
                    thead.remove();
                }
            }
        });
    },
    fireAddRow : function(){
        var typography = this;
        var button = $$('#' + this.getIdArea() + ' .generate').first();
        Event.observe(button,'click', function(e){
            e.preventDefault();
            var content = $$('#' + typography.getIdArea() + ' .table-content').first();
            var numRow = $(typography.getIdArea() + '_num_row').value;
            var numCol = $(typography.getIdArea() + '_num_col').value;
            if(numRow && numCol){
                content.innerHTML = '';
                content.appendChild(typography.buildTable(numRow,numCol));
                content.appendChild(new Element('input',{name : 'table_row',value : numRow,type : 'hidden'}));
                content.appendChild(new Element('input',{name : 'table_col',value : numCol,type : 'hidden'}));
                typography.onChangRemoveHead();
            } else {
                alert(typography.params.alert_text);
            }
        });
    },
    getValueHtml : function(){
        var table = new Element('table');
        var thead = new Element('thead');
        var tbody = new Element('tbody');

        var values = $(this.getIdArea() + '_form').serialize(true);
        var theads = values.thead,tbodys = values.tbody;
        var numRow = $(this.getIdArea()).select('input[name="table_row"]').first().value;
        var numCol = $(this.getIdArea()).select('input[name="table_col"]').first().value;
        var html = '<table class="' + this.params.class_text + '" style="width:100%">';

        /* Build table head */
        if(!$(this.getIdArea()).select('select[name="remove_thead"]').first().value){
            var tr = new Element('tr');
            if(typeof theads === 'string'){
                var th = new Element('th');
                th.innerHTML = theads;
                tr.appendChild(th);
            } else {
                theads.each(function(thValue,index){
                    var th = new Element('th');
                    th.innerHTML = thValue;
                    tr.appendChild(th);
                });
            }
            thead.appendChild(tr);
            table.appendChild(thead);
        }

        if(typeof tbodys === 'string'){
            var tr = new Element('tr');
            var td = new Element('td');
            td.innerHTML = tbodys;
            tr.appendChild(td);
            tbody.appendChild(tr);
        } else {
            for(var i = 0; i < numRow*numCol; i += 3){
                var tr = new Element('tr');
                for(var j = 0;j < numCol; j++){
                    var td = new Element('td');
                    td.innerHTML = tbodys[i+j];
                    tr.appendChild(td);
                }
                tbody.appendChild(tr);
            }
        }

        table.appendChild(tbody);
        html += table.innerHTML;
        html += '</table>';
        return html;
    }
});

ListTypo = Class.create(EMTypo,{
    loadContent : function(){
        return '<label>' + this.label + '</label>' +
               '<div class="list-content">' +
                    '<div class="row">' +
                        '<p><label>' + this.params.title_item + this.requiredHtml + '</label><input type="text" name="item" class="input-text required-data"/></p>' +
                    '</div>' +
               '</div>' +
               '<div class="action"><button id="' + this.getIdArea() + '_add-item" class="button"><span><span><span>' + this.params.button_add + '</span></span></span></button></div>';
    },
    buildRow : function(){
        return '<div class="row">' +
            '<p><label>' + this.params.title_item + '</label><input type="text" name="item" class="input-text"/></p>' +
            '<a class="close" href="javascript:void(0);" onclick="$(this).up().remove();return false;">X</a>' +
            '</div>';
    },
    fireAddRow : function(){
        var typography = this;
        Event.observe($(this.getIdArea() + '_add-item'),'click', function(e){
            e.preventDefault();
            var content = $$('#' + typography.getIdArea() + ' .list-content .row').last();
            content.insert({'after' : typography.buildRow()});
        });
    },
    getValueHtml : function(){
        var values = $(this.getIdArea() + '_form').serialize(true);
        var list = values.item;
        var html = this.getOpenTag(values);
        if(typeof list === 'string'){
            html += '<li>' + list + '</li>';
        } else {
            list.each(function(li,index){
                html += '<li>' + li + '</li>';
            });
        }
        html += this.getCloseTag();
        return html;
    }
});

IconTypo = Class.create(EMTypo,{
    loadContent : function(){
        return '';
    },
    buildRow : function(){
        return '<div class="row">' +
            '<p><label>' + this.params.title_item + '</label><input type="text" name="item" class="input-text"/></p>' +
            '<a class="close" href="javascript:void(0);" onclick="$(this).up().remove();return false;">X</a>' +
            '</div>';
    },
    getValueHtml : function(){
        var values = $(this.getIdArea() + '_form').serialize(true), value;
		value = values['class'];
        return this.getOpenTag(values) + this.params.class_option.values[value] + this.getCloseTag();
    }
});

var Typography = {
    textareaElementId: null,
    variablesContent: null,
    selectId : 'typo-type',
    dialogWindow: null,
    dialogWindowId: 'typo-chooser',
    overlayShowEffectOptions: null,
    overlayHideEffectOptions: null,
    insertFunction: 'Typography.insertTypography',
    listTypo: null,
    listControl : null,
    firstTypo : null,
    active : null,
    global_messages : null,
    init: function(textareaElementId, insertFunction) {
        if ($(textareaElementId)) {
            this.textareaElementId = textareaElementId;
        }
        if (insertFunction) {
            this.insertFunction = insertFunction;
        }
    },

    resetData: function() {
        this.variablesContent = null;
        this.dialogWindow = null;
    },

    openVariableChooser: function(variables) {
        this.listTypo = variables.list;
        this.global_messages = variables.general.global_messages;
        var contentConfig = '';
        /* Add select typo type */
        if (this.variablesContent == null && this.listTypo){
            this.listControl = {};
            this.variablesContent = '<div class="typo-action"><button class="button" id="insert-typo"><span><span><span>Insert Typography</span></span></span></button>';
            this.variablesContent += '<select name="typo_type" id="' + this.selectId + '">';

            this.listTypo.each(function(typoEntity) {
                this.variablesContent += '<option value="' + typoEntity.type + '">' + typoEntity.title + '</option>';
                var controlString = (typoEntity.conf.frontend_input).ucfirst() + 'Typo';
                if(!typoEntity.conf.params)
                    var control = new (window[controlString])(typoEntity.title,typoEntity.type,typoEntity.tag,variables.general.class_list, null);
                else
                    var control = new (window[controlString])(typoEntity.title,typoEntity.type,typoEntity.tag,variables.general.class_list,typoEntity.conf.params);
                this.listControl[typoEntity.type] = control;

                contentConfig += '<div style="display: none;" id="' + control.getIdArea() + '" class="typo-item">' + control.loadHtml() + '</div>';
                //contentConfig += control.loadContent();
            }.bind(this));
            this.variablesContent += '</select></div>';
            this.variablesContent += '<div class="typo-list-item">' + contentConfig + '</div>';

        }

        if (this.variablesContent) {
            this.openDialogWindow(this.variablesContent);
            this.prepareEventItem();
            this.resetDialog();
            this.onChangeConfig();
        }
    },
    openDialogWindow: function(variablesContent) {
        if ($(this.dialogWindowId) && typeof(Windows) != 'undefined') {
            Windows.focus(this.dialogWindowId);
            return;
        }

        this.overlayShowEffectOptions = Windows.overlayShowEffectOptions;
        this.overlayHideEffectOptions = Windows.overlayHideEffectOptions;
        Windows.overlayShowEffectOptions = {duration:0};
        Windows.overlayHideEffectOptions = {duration:0};

        this.dialogWindow = Dialog.info(variablesContent, {
            draggable:true,
            resizable:true,
            closable:true,
            className:"magento",
            windowClassName:"popup-window",
            title:'Insert Typography...',
			top:50,
            width:700,
            height:300,
            zIndex:1000,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:this.dialogWindowId,
            onClose: this.closeDialogWindow.bind(this)
        });
        variablesContent.evalScripts.bind(variablesContent).defer();
    },
    closeDialogWindow: function(window) {
        if (!window) {
            window = this.dialogWindow;
        }
        if (window) {
            window.close();
            Windows.overlayShowEffectOptions = this.overlayShowEffectOptions;
            Windows.overlayHideEffectOptions = this.overlayHideEffectOptions;
        }
    },
    getValidateData : function(){
        var control = this.listControl[$(this.selectId).value];
        return control.validateData();
    },
    getValueHtml : function(){
        var control = this.listControl[$(this.selectId).value];
        return control.getValueHtml();
    },
    insertTypography: function() {
        if(!this.getValidateData()){
            alert(this.global_messages.required);
            return;
        }

        value = this.getValueHtml();
        this.closeDialogWindow(this.dialogWindow);
        var textareaElm = $(this.textareaElementId);
        if (textareaElm) {
            var scrollPos = textareaElm.scrollTop;
            updateElementAtCursor(textareaElm, value);
            textareaElm.focus();
            textareaElm.scrollTop = scrollPos;
            textareaElm = null;
        }
        return;
    },
    resetDialog : function(){
        this.firstTypo = this.listControl[this.listTypo.first().type];
        this.active = this.firstTypo;
        this.showActiveTypo();
    },
    prepareEventItem : function(){
        var typography = this;
        this.listTypo.each(function(typoEntity){
            var control = typography.listControl[typoEntity.type];
            if(typeof control.fireAddRow == 'function'){
                control.fireAddRow();
            };
        });

        /* Add event click "insert typography" button */
        Event.observe($('insert-typo'),'click', function(){
            MagentotypoPlugin.insertTypography();

        });
    },
    showActiveTypo : function(){
        $(this.active.getIdArea()).show();
    },
    hideActiveTypo : function(){
        $(this.active.getIdArea()).hide();
    },
    changeActiveTypo : function(newActiveTypo){
        this.hideActiveTypo();
        this.active = newActiveTypo;
        this.showActiveTypo();
        //$('typo-chooser_content').setStyle({'height':$('typo-chooser_content').getHeight() + 'px'});
    },
    onChangeConfig : function(){
        var typography = this;
        Event.observe($(this.selectId),'change', function(){
            var control = typography.listControl[$(this).value];
            typography.changeActiveTypo(control);
        });
    }
};

MagentotypoPlugin = {
    editor: null,
    variables: null,
    textareaId: null,
    setEditor: function(editor) {
        this.editor = editor;
    },
    loadChooser: function(url, textareaId) {
        this.textareaId = textareaId;
        if (this.variables == null) {
            new Ajax.Request(url, {
                parameters: {},
                onComplete: function (transport) {
                    if (transport.responseText.isJSON()) {
                        Typography.init(null, 'MagentotypoPlugin.insertTypography');
                        this.variables = transport.responseText.evalJSON();
                        this.openChooser(this.variables);
                    }
                }.bind(this)
             });
        } else {
            this.openChooser(this.variables);
        }
        return;
    },
    openChooser: function(variables) {
        Typography.openVariableChooser(variables);
    },
    insertTypography : function () {
        if (this.textareaId) {
            Typography.init(this.textareaId);
            Typography.insertTypography();
        } else {
            if(Typography.getValidateData()){
                var value = Typography.getValueHtml();
                Typography.closeDialogWindow();
                this.editor.execCommand('mceInsertContent', false, value);
            } else {
                alert(Typography.global_messages.required);
            }
        }
        return;
    }
};
