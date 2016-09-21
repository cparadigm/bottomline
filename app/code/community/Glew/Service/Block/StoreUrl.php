<?php

class Glew_Service_Block_StoreUrl extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true);
    }
}
