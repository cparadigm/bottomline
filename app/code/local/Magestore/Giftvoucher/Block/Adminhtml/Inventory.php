<?php

class Magestore_Giftvoucher_Block_Adminhtml_Inventory extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Inventory {

    public function isNew() {
        if ($this->getRequest()->getParam('type') == 'giftvoucher' && Mage::app()->getRequest()->getActionName() == 'new') {
            return false;
        }
        if ($this->getProduct()->getId()) {
            return false;
        }
        return true;
    }

}