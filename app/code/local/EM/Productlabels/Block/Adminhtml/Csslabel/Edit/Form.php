<?php
class EM_Productlabels_Block_Adminhtml_Csslabel_Edit_Form extends EM_Productlabels_Block_Adminhtml_Element_Form
{
	
	protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $form->setDataObject(Mage::registry('productlabels_css_data'));
      $fieldset = $form->addFieldset('productlabels_css', array('legend'=>Mage::helper('productlabels')->__('Css information')));
      
		$group = array(
			'content'
		);
		$attributes = Mage::registry('productlabels_css_data')->getAttributes($group);
		
		$this->_setFieldset($attributes,$fieldset);
		$fieldset->addField('store', 'hidden', array(
			'name'    => 'store',
		));
      if($id = Mage::registry('productlabels_css_data')->getId())
      {
          $fieldset->addField('entity_id', 'hidden', array(
              'name'    => 'id'
          ));
      }
      
      if ( Mage::getSingleton('adminhtml/session')->getProductlabelsCssData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getProductlabelsCssData());
          Mage::getSingleton('adminhtml/session')->setProductlabelsCssData(null);
      } elseif ( Mage::registry('productlabels_css_data') ) {
          $form->setValues(Mage::registry('productlabels_css_data')->getData());
      }
      return parent::_prepareForm();
  }

   public function getStoreCode()
    {
        return $this->getRequest()->getParam('store', '');
    }
}