<?php

class EM_Recentreviewproducts_Block_Adminhtml_Recentreviewproducts_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('recentreviewproducts_form', array('legend'=>Mage::helper('recentreviewproducts')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('recentreviewproducts')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('recentreviewproducts')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('recentreviewproducts')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('recentreviewproducts')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('recentreviewproducts')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('recentreviewproducts')->__('Content'),
          'title'     => Mage::helper('recentreviewproducts')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getRecentreviewproductsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRecentreviewproductsData());
          Mage::getSingleton('adminhtml/session')->setRecentreviewproductsData(null);
      } elseif ( Mage::registry('recentreviewproducts_data') ) {
          $form->setValues(Mage::registry('recentreviewproducts_data')->getData());
      }
      return parent::_prepareForm();
  }
}