<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Thumbnail extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_thumbnail', array('legend'=>Mage::helper('slideshow2')->__('Thumbnail')));

		$fieldset->addField('thumb_width', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Thumb Width'),
		  'name'      => 'thumbnail[thumb_width]',
		))->setAfterElementHtml(' px');

		$fieldset->addField('thumb_height', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Thumb Height'),
		  'name'      => 'thumbnail[thumb_height]',
		))->setAfterElementHtml(' px');

		$fieldset->addField('thumb_amount', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Thumb Amount'),
		  'name'      => 'thumbnail[thumb_amount]',
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