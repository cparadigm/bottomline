<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-11-27T18:16:39+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Block/System/Config/Frontend/Mapping/Abstract.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

abstract class Xtento_OrderStatusImport_Block_System_Config_Frontend_Mapping_Abstract extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $mappingModel = Mage::getModel('orderstatusimport/processor_mapping_fields');
        $mappingModel->setDataPath($this->DATA_PATH);

        $importFieldRenderer = Mage::app()->getLayout()->createBlock('orderstatusimport/system_config_frontend_mapping_importfields');
        $importFieldRenderer->setImportFields($mappingModel->getMappingFields());
        $importFieldRenderer->setMappingId($this->MAPPING_ID);
        $importFieldRenderer->setStyle('width: 160px');

        $this->addColumn('field', array(
            'label' => Mage::helper('adminhtml')->__('Import Field'),
            'style' => 'width:160px',
            'renderer' => $importFieldRenderer
        ));

        $this->addColumn('value', array(
            'label' => Mage::helper('adminhtml')->__($this->VALUE_FIELD_NAME),
            'style' => 'width:140px',
        ));

        $defaultValuesRenderer = Mage::app()->getLayout()->createBlock('orderstatusimport/system_config_frontend_mapping_defaultvalues');
        $defaultValuesRenderer->setImportFields($mappingModel->getMappingFields());
        $defaultValuesRenderer->setMappingModel($mappingModel);
        $defaultValuesRenderer->setMappingId($this->MAPPING_ID);
        $defaultValuesRenderer->setStyle('width: 140px');

        $this->addColumn('default_value', array(
            'label' => Mage::helper('adminhtml')->__('Default Value'),
            'style' => 'width:140px',
            'renderer' => $defaultValuesRenderer
        ));

        $this->_addAfter = true;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add field to mapping');
        parent::__construct();
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $mappingModel = Mage::getModel('orderstatusimport/processor_mapping_fields');
        $mappingModel->setDataPath($this->DATA_PATH);
        $mappingFields = $mappingModel->getMappingFields();

        // Add the actual mapped fields
        $html = '<script>' . "\n";
        $html .= 'var ' . $this->MAPPING_ID . '_mapping_values = new Hash();' . "\n";
        foreach ($mappingModel->getMapping() as $field => $fieldData) {
            $html .= $this->MAPPING_ID . '_mapping_values[\'' . $fieldData['id'] . '\'] = \'' . $this->_escapeStringJs($field) . '\';' . "\n";
        }
        // Add the default values
        $html .= 'var ' . $this->MAPPING_ID . '_default_values = new Hash();' . "\n";
        foreach ($mappingModel->getMapping() as $field => $fieldData) {
            $html .= $this->MAPPING_ID . '_default_values[\'' . $fieldData['id'] . '\'] = \'' . $this->_escapeStringJs($fieldData['default_value']) . '\';' . "\n";
        }
        // Add the possible default values
        $html .= 'var ' . $this->MAPPING_ID . '_possible_default_values = $H({' . "\n";
        $loopLength = 0;
        foreach ($mappingFields as $field => $fieldData) {
            if (isset($fieldData['default_values']) && is_array($fieldData['default_values'])) {
                $loopLength++;
            }
        }
        $loopCounter = 0;
        foreach ($mappingFields as $field => $fieldData) {
            if (isset($fieldData['default_values']) && is_array($fieldData['default_values'])) {
                $loopCounter++;
                $loopLength2 = count($fieldData['default_values']);
                $loopCounter2 = 0;
                $html .= '\''.$this->_escapeStringJs($field) . '\': {' . "\n";
                foreach ($fieldData['default_values'] as $code => $label) {
                    $loopCounter2++;
                    $html .= '\''.$this->_escapeStringJs($code) . '\': \'' . $this->_escapeStringJs($label) . '\'';
                    if ($loopCounter2 !== $loopLength2) {
                        $html .= ',';
                    }
                    $html .= "\n";
                }
                $html .= '}';
                if ($loopCounter !== $loopLength) {
                    $html .= ',';
                }
            }
        }
        $html .= '});';
        $html .= '</script>' . "\n";

        $html .= parent::render($element);

        return $html;
    }

    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
                ->toHtml();
        }

        return '<input type="text" id="' . $inputName . '" name="' . $inputName . '" value="#{' . $columnName . '}" ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
            (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';
    }

    public function fetchView($fileName)
    {
        extract($this->_viewVars);
        $do = $this->getDirectOutput();

        if (!$do) {
            ob_start();
        }

        include Mage::getBaseDir() . '/app/code/local/Xtento/OrderStatusImport/template/system/config/mapping.phtml';

        if (!$do) {
            $html = ob_get_clean();
        }
        else {
            $html = '';
        }

        return $html;
    }

    private function _escapeStringJs($string) {
        return str_replace("'", "\\'", $string);
    }
}
