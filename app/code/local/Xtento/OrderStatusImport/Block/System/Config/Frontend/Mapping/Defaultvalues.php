<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-04-02T15:19:11+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Block/System/Config/Frontend/Mapping/Defaultvalues.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Block_System_Config_Frontend_Mapping_Defaultvalues extends Mage_Core_Block_Abstract
{
    public function _toHtml()
    {
        $column = $this->getColumn();

        $html = '<input type="text" id="' . $this->getInputName() . '" name="' . $this->getInputName() . '" value="#{' . $this->getColumnName() . '}" ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
            (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';

        // Is it a select or a text field?
        $html .= <<<JS
        <script>
            var default_values = {$this->getMappingId()}_possible_default_values.get($(\'select_#{_id}\').value);
            if (default_values) {
                var field = $(\'groups[processor_{$this->getMappingId()}][fields][import_mapping][value][#{_id}][default_value]\').parentNode;
                field.innerHTML = \'\';
                select = document.createElement(\'select\');
                select.setAttribute(\'style\', \'width: 146px;\');
                select.setAttribute(\'id\', \'select_default_#{_id}\');
                select.setAttribute(\'name\', \'{$this->getInputName()}\');
                select.setAttribute(\'class\', \'select\');
                option = document.createElement(\'option\');
                optionText = document.createTextNode(\'--- Select value ---\');
                option.appendChild(optionText);
                option.setAttribute(\'value\', \'\');
                select.appendChild(option);
                \$H(default_values).each(function(pair) {
                    option = document.createElement(\'option\');
                    optionText = document.createTextNode(pair.value);
                    option.appendChild(optionText);
                    option.setAttribute(\'value\', pair.key);
                    select.appendChild(option);
                });
                field.appendChild(select);
                if ({$this->getMappingId()}_default_values[\'#{_id}\']) {
                    $(select).setValue({$this->getMappingId()}_default_values[\'#{_id}\']);
                }
            }

            if ($(\'{$this->getMappingId()}DivOverlay\')) $(\'{$this->getMappingId()}DivOverlay\').hide();
            if (!$(\'{$this->getMappingId()}_save_data\')) {
                input = document.createElement(\'input\');
                input.setAttribute(\'type\', \'hidden\');
                input.setAttribute(\'name\', \'groups[processor_{$this->getMappingId()}][fields][import_mapping][value][__save_data]\');
                input.setAttribute(\'id\', \'{$this->getMappingId()}_save_data\');
                input.setAttribute(\'value\', \'1\');
                $(\'grid_{$this->getMappingId()}\').appendChild(input);
            }
        <\/script>
JS;

        return str_replace(array("\r", "\n", "\r\n"), "", $html);
    }
}
