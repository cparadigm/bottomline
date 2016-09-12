<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Position extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_position', array('legend'=>Mage::helper('slideshow2')->__('Position')));
     
		$fieldset->addField('type', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('Position on the page'),
		  'name'      => 'position[type]',
		  'values'    => array(
			  array(
				  'value'     => 'left',
				  'label'     => Mage::helper('slideshow2')->__('Left'),
			  ),
			  array(
				  'value'     => 'center',
				  'label'     => Mage::helper('slideshow2')->__('Center'),
			  ),
			   array(
				  'value'     => 'right',
				  'label'     => Mage::helper('slideshow2')->__('Right'),
			  ),
		  ),
		));
		
		$fieldset->addField('mg_top', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Margin Top'),
		  'name'      => 'position[mg_top]',
		))->setAfterElementHtml(' px');
		
		$fieldset->addField('mg_bottom', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Margin Bottom'),
		  'name'      => 'position[mg_bottom]',
		))->setAfterElementHtml(' px');
		
		$fieldset->addField('mg_left', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Margin Left'),
		  'name'      => 'position[mg_left]',
		))->setAfterElementHtml(' px');
		
		$fieldset->addField('mg_right', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Margin Right'),
		  'name'      => 'position[mg_right]',
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