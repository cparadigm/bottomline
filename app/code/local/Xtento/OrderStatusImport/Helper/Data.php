<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-12-22T16:05:47+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Helper/Data.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Helper_Data extends Mage_Core_Helper_Abstract
{
    const EDITION = 'CE';

    public function isDebugEnabled()
    {
        return Mage::getStoreConfigFlag('orderstatusimport/general/debug');
    }

    public function getDebugEmail()
    {
        return Mage::getStoreConfig('orderstatusimport/general/debug_email');
    }

    public function getAutoImportEnabled($observer)
    {
        if (!Mage::getStoreConfigFlag(Xtento_OrderStatusImport_Model_Observer::MODULE_ENABLED)) {
            return 0;
        }
        $autoImportEnabled = Mage::getModel('core/config_data')->load($observer->cronString . '/general/' . str_rot13('frevny'), 'path')->getValue();
        if (empty($autoImportEnabled) || !$autoImportEnabled || (0x28 !== strlen(trim($autoImportEnabled)))) {
            return 0;
        }
        if (!Mage::registry('cronString')) {
            Mage::register('cronString', 'false');
        }
        return true;
    }
}