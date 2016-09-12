<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftproduct extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_giftproduct';
        $this->_blockGroup = 'giftvoucher';
        $this->_headerText = Mage::helper('giftvoucher')->__('Gift Card Product Manager');
        $this->_addButtonLabel = Mage::helper('giftvoucher')->__('Add Gift Card Product');
        parent::__construct();
    }

}