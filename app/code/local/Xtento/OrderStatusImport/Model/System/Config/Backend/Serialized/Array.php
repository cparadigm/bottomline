<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-02-21T14:14:18+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/System/Config/Backend/Serialized/Array.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_System_Config_Backend_Serialized_Array extends Mage_Adminhtml_Model_System_Config_Backend_Serialized
{
    protected function _beforeSave()
    {
        $value = $this->getValue();

        if (!isset($value['__save_data']) && isset($value['__type'])) {
            // save_data was not set by our Javascript.. let's better load the fail-safe database configuration instead of risking losing the mapping
            $dbData = unserialize(Mage::getStoreConfig('orderstatusimport/processor_' . $value['__type'] . '/import_mapping'));
            if (!empty($dbData)) {
                $value = $dbData;
            }
        }

        if (is_array($value)) {
            unset($value['__empty']);
            unset($value['__type']);
            unset($value['__save_data']);
            foreach ($value as $id => $data) {
                if (!isset($data['field'])) {
                    unset($value[$id]);
                    continue;
                }
                if ($data['field'] == '') {
                    unset($value[$id]);
                }
            }
        }
        $this->setValue($value);
        parent::_beforeSave();
    }
}
