<?php

class EM_Recentreviewproducts_Block_Adminhtml_Recentreviewproducts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('recentreviewproducts_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('recentreviewproducts')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('recentreviewproducts')->__('Item Information'),
          'title'     => Mage::helper('recentreviewproducts')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('recentreviewproducts/adminhtml_recentreviewproducts_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}