<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-02-18T21:55:37+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Block/System/Config/Frontend/Import/Importfield.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Block_System_Config_Frontend_Import_Importfield extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        return '<input type="file" class="" value="'.$element->getEscapedValue().'" name="'.$element->getName().'" id="'.$element->getHtmlId().'"/>';
    }

}
