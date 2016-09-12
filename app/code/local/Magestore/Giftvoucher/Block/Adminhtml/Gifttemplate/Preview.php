<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifttemplate_Preview extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('giftvoucher/template/pattern/main.phtml');
        return $this;
    }

    public function getGiftTemplate() {
        return Mage::registry('template_data');
    }

}
