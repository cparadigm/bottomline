<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifttemplate_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('giftvoucher_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('giftvoucher')->__('Gift Card Template Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('giftvoucher')->__('General Information'),
            'title' => Mage::helper('giftvoucher')->__('General Information'),
            'content' => $this->getLayout()->createBlock('giftvoucher/adminhtml_gifttemplate_edit_tab_form')->toHtml(),
        ));
        $this->addTab('images_section', array(
            'label' => Mage::helper('giftvoucher')->__('Images'),
            'title' => Mage::helper('giftvoucher')->__('Images'),
            'content' => $this->getLayout()->createBlock('giftvoucher/adminhtml_gifttemplate_edit_tab_images')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }

}
