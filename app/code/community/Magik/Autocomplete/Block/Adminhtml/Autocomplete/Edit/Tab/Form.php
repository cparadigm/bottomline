<?php

class Magik_Autocomplete_Block_Adminhtml_Autocomplete_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('autocomplete_form', array('legend'=>Mage::helper('autocomplete')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('autocomplete')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('autocomplete')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('autocomplete')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('autocomplete')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('autocomplete')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('autocomplete')->__('Content'),
          'title'     => Mage::helper('autocomplete')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getAutocompleteData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getAutocompleteData());
          Mage::getSingleton('adminhtml/session')->setAutocompleteData(null);
      } elseif ( Mage::registry('autocomplete_data') ) {
          $form->setValues(Mage::registry('autocomplete_data')->getData());
      }
      return parent::_prepareForm();
  }
}