<?php
class EM_Slideshow2_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_slider';
    $this->_blockGroup = 'slideshow2';
    $this->_headerText = Mage::helper('slideshow2')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('slideshow2')->__('Add Item');
    parent::__construct();
  }
}