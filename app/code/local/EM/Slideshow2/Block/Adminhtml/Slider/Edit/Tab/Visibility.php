<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Visibility extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_visibility', array('legend'=>Mage::helper('slideshow2')->__('Mobile Visibility')));

		$fieldset->addField('hide_slider_under', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Hide Slider Under Width'),
		  'name'      => 'visibility[hide_slider_under]',
		  'class'     => 'required-entry',
		  'required'  => true,
		  'note'	  => 'Works only in Responsive style. Not available for Fullwidth',
		))->setAfterElementHtml(' px');

		$fieldset->addField('hide_defined_layers_under', 'hidden', array(
		  'label'     => Mage::helper('slideshow2')->__('Hide Defined Layers Under Width'),
		  'name'      => 'visibility[hide_defined_layers_under]',
		));

		$fieldset->addField('hide_all_layers_under', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Hide All Layers Under Width'),
		  'name'      => 'visibility[hide_all_layers_under]',
		  'class'     => 'required-entry',
		  'required'  => true,
		))->setAfterElementHtml(' px');

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