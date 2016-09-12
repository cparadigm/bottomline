<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifttemplate_Viewdemo extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('giftvoucher/template/serializer.phtml');
        return $this;
    }

    public function getPattern() {
        return Mage::registry('pattern');
    }

}
