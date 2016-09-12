<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Troubleshooting extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_troubleshooting', array('legend'=>Mage::helper('slideshow2')->__('Troubleshooting')));

		$fieldset->addField('jquery_noconflict', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('JQuery No Conflict Mode'),
		  'name'      => 'trouble[jquery_noconflict]',
		  'values'    => array(
			  array(
				  'value'     => 'on',
				  'label'     => Mage::helper('slideshow2')->__('On'),
			  ),
			  array(
				  'value'     => 'off',
				  'label'     => Mage::helper('slideshow2')->__('Off'),
			  ),
		  ),
		));

		$fieldset->addField('js_to_body', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Put JS Includes To Body'),
		  'name'      => 'trouble[js_to_body]',
		  'values'    => array(
			  array(
				  'value'     => 'true',
				  'label'     => Mage::helper('slideshow2')->__('True'),
			  ),
			  array(
				  'value'     => 'false',
				  'label'     => Mage::helper('slideshow2')->__('False'),
			  ),
		  ),
		));

      if ( Mage::getSingleton('adminhtml/session')->getSlideshow2Data() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshow2Data());
          Mage::getSingleton('adminhtml/session')->setSlideshow2Data(null);
      } elseif ( Mage::registry('slideshow2_data') ) {
          $form->setValues(Mage::registry('slideshow2_data')->getData());
      }
      return parent::_prepareForm();
  }
}