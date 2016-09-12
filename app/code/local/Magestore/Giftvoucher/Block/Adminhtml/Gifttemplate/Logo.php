<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifttemplate_Logo extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $actionName = $this->getRequest()->getActionName();
        $image = $row->getData($this->getColumn()->getIndex());

        if (strpos($actionName, 'export') === 0) {
            return $image;
        }
        if ($image) {
            return '<img src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'giftvoucher/template/logo/' . $image . ' " width="40 px" height="40px" />';
        } else {
            return null;
        }
    }

}