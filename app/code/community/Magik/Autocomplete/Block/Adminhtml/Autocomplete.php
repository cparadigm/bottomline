<?php
class Magik_Autocomplete_Block_Adminhtml_Autocomplete extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_autocomplete';
    $this->_blockGroup = 'autocomplete';
    $this->_headerText = Mage::helper('autocomplete')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('autocomplete')->__('Add Item');
    parent::__construct();
  }
}