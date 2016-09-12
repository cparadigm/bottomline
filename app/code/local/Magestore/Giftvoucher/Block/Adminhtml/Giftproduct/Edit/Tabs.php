<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('giftproduct_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('giftvoucher')->__('Product Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('giftvoucher')->__('Settings'),
            'title' => Mage::helper('giftvoucher')->__('Settings'),
            'content' => $this->getLayout()->createBlock('giftvoucher/adminhtml_giftproduct_edit_tab_form')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }

}