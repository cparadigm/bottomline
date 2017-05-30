/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'priceBox'
], function($) {
    "use strict";
    $.widget('awacp.acpSwatchValuesSetter', {
        options: {
            classes: {
                productForm: '#product_addtocart_form_acp',
                attributeClass: 'swatch-attribute',
                attributeLabelClass: 'swatch-attribute-label',
                attributeSelectedOptionLabelClass: 'swatch-attribute-selected-option',
                attributeInput: 'swatch-input',
                optionClass: 'swatch-option'
            },
            values: ''
        },

        /**
         * Creates widget
         * @private
         */
        _create: function () {
            this._setSwatchValues();
        },

        /**
         * Set swatches
         * @private
         */
        _setSwatchValues: function () {
            var parent, label, input, selectedOption, attributeId, attributeValue,
                self = this,
                values = this.options.values;

            for (attributeId in values) {
                attributeValue = values[attributeId];
                if (!attributeValue) {
                    return true;
                }
                parent = this.element.find('[attribute-id="' + attributeId + '"]');
                label = parent.find('.' + self.options.classes.attributeSelectedOptionLabelClass);
                selectedOption = parent.find('[option-id="' + attributeValue + '"]');
                input = $(
                    self.options.classes.productForm +
                    ' .' + self.options.classes.attributeInput +
                    '[name="super_attribute[' + attributeId + ']"]'
                );

                label.text(selectedOption.attr('option-label'));
                input.val(attributeValue);
                selectedOption.addClass('selected');
            }
        }
    });

    return $.awacp.acpSwatchValuesSetter;
});