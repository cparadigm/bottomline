<?php

class Magik_Autocomplete_Block_Adminhtml_Autocomplete_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('autocomplete_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('autocomplete')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('autocomplete')->__('Item Information'),
          'title'     => Mage::helper('autocomplete')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('autocomplete/adminhtml_autocomplete_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}