<?php
class EM_Recentreviewproducts_Block_Adminhtml_Recentreviewproducts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_recentreviewproducts';
    $this->_blockGroup = 'recentreviewproducts';
    $this->_headerText = Mage::helper('recentreviewproducts')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('recentreviewproducts')->__('Add Item');
    parent::__construct();
  }
}