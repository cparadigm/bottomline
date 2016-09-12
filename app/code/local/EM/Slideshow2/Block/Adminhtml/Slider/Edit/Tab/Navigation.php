<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Navigation extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slideshow2_navigation', array('legend'=>Mage::helper('slideshow2')->__('Navigation')));
     
      $fieldset->addField('nav_type', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('Navigation Type'),
		  'name'      => 'navigation[nav_type]',
		  'values'    => array(
			  array(
				  'value'     => 'none',
				  'label'     => Mage::helper('slideshow2')->__('None'),
			  ),
			  array(
				  'value'     => 'bullet',
				  'label'     => Mage::helper('slideshow2')->__('Bullet'),
			  ),
			  array(
				  'value'     => 'thumb',
				  'label'     => Mage::helper('slideshow2')->__('Thumb'),
			  ),
			  /*array(
				  'value'     => 'both',
				  'label'     => Mage::helper('slideshow2')->__('Both'),
			  ),*/
		  ),
		));
		
		$fieldset->addField('nav_arrows', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('Navigation Arrows'),
		  'name'      => 'navigation[nav_arrows]',
		  'values'    => array(
			  array(
				  'value'     => 'nexttobullets',
				  'label'     => Mage::helper('slideshow2')->__('Next To Bullets'),
			  ),
			  array(
				  'value'     => 'verticalcentered',
				  'label'     => Mage::helper('slideshow2')->__('Vertical Centered'),
			  ),
			  array(
				  'value'     => 'none',
				  'label'     => Mage::helper('slideshow2')->__('None'),
			  ),
		  ),
		));
		
		$fieldset->addField('nav_style', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('Navigation Style'),
		  'name'      => 'navigation[nav_style]',
		  'values'    => array(
			  array(
				  'value'     => 'round',
				  'label'     => Mage::helper('slideshow2')->__('Round'),
			  ),
			  array(
				  'value'     => 'navbar',
				  'label'     => Mage::helper('slideshow2')->__('Navbar'),
			  ),
			  array(
				  'value'     => 'round-old',
				  'label'     => Mage::helper('slideshow2')->__('Old Round'),
			  ),
			  array(
				  'value'     => 'square-old',
				  'label'     => Mage::helper('slideshow2')->__('Old Square'),
			  ),
			  array(
				  'value'     => 'navbar-old',
				  'label'     => Mage::helper('slideshow2')->__('Old Navbar'),
			  ),
		  ),
		));
		
		$fieldset->addField('nav_offset_hor', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Nav. Offset Horizontal'),
		  'name'      => 'navigation[nav_offset_hor]',
		))->setAfterElementHtml(' px');
		
		$fieldset->addField('nav_offset_vert', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Nav. Offset Vertical'),
		  'name'      => 'navigation[nav_offset_vert]',
		))->setAfterElementHtml(' px');
		
		$fieldset->addField('nav_always_on', 'select', array(
		  'label'     => Mage::helper('slideshow2')->__('Always Show Navigation'),
		  'name'      => 'navigation[nav_always_on]',
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
		
		$fieldset->addField('hide_thumbs', 'text', array(
		  'label'     => Mage::helper('slideshow2')->__('Hide Navitagion After'),
		  'name'      => 'navigation[hide_thumbs]',
		))->setAfterElementHtml(' ms');
     
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