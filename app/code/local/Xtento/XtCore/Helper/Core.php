<?php

/**
 * Product:       Xtento_XtCore (1.0.0)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-04-29T15:53:27+02:00
 * File:          app/code/local/Xtento/XtCore/Helper/Core.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Helper_Core extends Mage_Core_Helper_Abstract
{
    /* Fix for compatibility with Magento version <1.4 */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.4.1.0', '>=')) {
            return Mage::helper('core')->escapeHtml($data, $allowedTags);
        } else {
            return Mage::helper('core')->htmlEscape($data, $allowedTags);
        }
    }
}