<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-04-02T15:16:12+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Processor/Mapping/Abstract.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

abstract class Xtento_OrderStatusImport_Model_Processor_Mapping_Abstract extends Varien_Object
{
    protected $_mapping = null;
    protected $_importFields = null;

    public function getMapping()
    {
        if ($this->_mapping !== NULL) {
            return $this->_mapping;
        }

        $serializedData = Mage::getStoreConfig($this->getDataPath());
        if (!$serializedData) {
            return array();
        }
        $data = unserialize($serializedData);
        if (!$data) {
            return array();
        }

        $mapping = array();
        foreach ($data as $id => $field) {
            if (!isset($field['field'])) {
                continue;
            }
            if (!isset($field['value'])) {
                $value = '';
            } else {
                $value = $field['value'];
            }
            if (!isset($field['default_value'])) {
                $default_value = false;
            } else {
                $default_value = $field['default_value'];
            }
            $mapping[$field['field']] = array('id' => $id, 'field' => $field['field'], 'value' => $value, 'default_value' => $default_value);
        }
        $this->_mapping = $mapping;

        return $this->_mapping;
    }

    public function getMappingField($field)
    {
        $mapping = $this->getMapping();
        if (isset($mapping[$field])) {
            return $mapping[$field]['value'];
        }
        return '';
    }

    public function getMappingConfig() {
        $mappingConfig = new Varien_Object();
        foreach ($this->getMapping() as $field => $data) {
            if (!isset($data['value']) || $data['value'] == '') {
                continue;
            }
            $mappingConfig->setData($field, $data['value']);
        }
        return $mappingConfig;
    }

    public function getDefaultValues($entity)
    {
        $defaultValues = array();
        if ($entity == 'shipping_carriers') {
            $carriers = Mage::getSingleton('xtcore/system_config_source_carriers')->toOptionArray();
            foreach ($carriers as $carrier) {
                $defaultValues[$carrier['value']] = $carrier['label'];
            }
        }
        if ($entity == 'order_status') {
            $statuses = Mage::getSingleton('orderstatusimport/system_config_source_order_status')->toOptionArray();
            foreach ($statuses as $status) {
                if ($status['value'] == 'no_change') continue;
                $defaultValues[$status['value']] = $status['label'];
            }
        }
        if ($entity == 'yesno') {
            $defaultValues[0] = Mage::helper('orderstatusimport')->__('No');
            $defaultValues[1] = Mage::helper('orderstatusimport')->__('Yes');
        }
        return $defaultValues;
    }

    public function getDefaultValue($fieldName)
    {
        $mapping = $this->getMapping();
        if (isset($mapping[$fieldName])) {
            return $mapping[$fieldName]['default_value'];
        }
        return '';
    }
}
