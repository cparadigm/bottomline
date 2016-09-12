<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Appearance extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_appearance', array('legend'=>Mage::helper('slideshow2')->__('Appearance')));
     
		$fieldset->addField('shadow_type', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('Shadow Type'),
		  'name'      => 'appearance[shadow_type]',
		  'values'    => array(
			  array(
				  'value'     => 0,
				  'label'     => Mage::helper('slideshow2')->__('No Shadow'),
			  ),
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('slideshow2')->__('1'),
			  ),
			   array(
				  'value'     => 2,
				  'label'     => Mage::helper('slideshow2')->__('2'),
			  ),
			   array(
				  'value'     => 3,
				  'label'     => Mage::helper('slideshow2')->__('3'),
			  ),
		  ),
		));
		
		$fieldset->addField('show_time', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Show Timer Line'),
		  'name'      => 'appearance[show_time]',
		  'values'    => array(
			  array(
				  'value'     => 'true',
				  'label'     => Mage::helper('slideshow2')->__('Show'),
			  ),
			  array(
				  'value'     => 'false',
				  'label'     => Mage::helper('slideshow2')->__('Hide'),
			  ),
		  ),
		));
		
		$fieldset->addField('time_position', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Timer Line Position'),
		  'name'      => 'appearance[time_position]',
		  'values'    => array(
			  array(
				  'value'     => 'top',
				  'label'     => Mage::helper('slideshow2')->__('Top'),
			  ),
			  array(
				  'value'     => 'bottom',
				  'label'     => Mage::helper('slideshow2')->__('Bottom'),
			  ),
		  ),
		));
		
		$fieldset->addField('bg_color', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Background color'),
		  'name'      => 'appearance[bg_color]',
		));
		
		$fieldset->addField('padding', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Padding (border)'),
		  'name'      => 'appearance[padding]',
		));
		
		$fieldset->addField('show_bg_img', 'radios', array(
		  'label'     => Mage::helper('slideshow2')->__('Show Background Image'),
		  'name'      => 'appearance[show_bg_img]',
		  'values'    => array(
			  array(
				  'value'     => 'true',
				  'label'     => Mage::helper('slideshow2')->__('Yes'),
			  ),
			  array(
				  'value'     => 'false',
				  'label'     => Mage::helper('slideshow2')->__('No'),
			  ),
		  ),
		));
		
		$fieldset->addField('bg_img', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Background Image Url'),
		  'name'      => 'appearance[bg_img]',
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