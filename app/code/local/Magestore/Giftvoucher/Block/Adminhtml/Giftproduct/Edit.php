<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'giftvoucher';
        $this->_controller = 'adminhtml_giftproduct';

        $this->_removeButton('save');
        $this->_removeButton('delete');
    }

    public function getHeaderText() {
        return Mage::helper('giftvoucher')->__('Add New Gift Card Product');
    }

}