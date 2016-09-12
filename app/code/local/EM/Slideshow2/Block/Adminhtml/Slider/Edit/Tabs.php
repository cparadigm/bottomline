<?php

class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('slideshow2_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('slideshow2')->__('Slideshow Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_general', array(
          'label'     => Mage::helper('slideshow2')->__('General'),
          'title'     => Mage::helper('slideshow2')->__('General'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_general')->toHtml(),
      ));
	  $this->addTab('form_images', array(
          'label'     => Mage::helper('slideshow2')->__('Images'),
          'title'     => Mage::helper('slideshow2')->__('Images'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_images')->toHtml(),
      ));
	  $this->addTab('form_position', array(
          'label'     => Mage::helper('slideshow2')->__('Position'),
          'title'     => Mage::helper('slideshow2')->__('Position'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_position')->toHtml(),
      ));
	  $this->addTab('form_appearance', array(
          'label'     => Mage::helper('slideshow2')->__('Appearance'),
          'title'     => Mage::helper('slideshow2')->__('Appearance'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_appearance')->toHtml(),
      ));
	  $this->addTab('form_navigation', array(
          'label'     => Mage::helper('slideshow2')->__('Navigation'),
          'title'     => Mage::helper('slideshow2')->__('Navigation'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_navigation')->toHtml(),
      ));
	  $this->addTab('form_thumbnail', array(
          'label'     => Mage::helper('slideshow2')->__('Thumbnail'),
          'title'     => Mage::helper('slideshow2')->__('Thumbnail'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_thumbnail')->toHtml(),
      ));
	  $this->addTab('form_visibility', array(
          'label'     => Mage::helper('slideshow2')->__('Mobile Visibility'),
          'title'     => Mage::helper('slideshow2')->__('Mobile Visibility'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_visibility')->toHtml(),
      ));
	   $this->addTab('form_troubleshooting', array(
          'label'     => Mage::helper('slideshow2')->__('Troubleshooting'),
          'title'     => Mage::helper('slideshow2')->__('Troubleshooting'),
          'content'   => $this->getLayout()->createBlock('slideshow2/adminhtml_slider_edit_tab_troubleshooting')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}