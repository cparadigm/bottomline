<?php
class EM_Productlabels_Block_Adminhtml_Productlabels_Edit_Form extends EM_Productlabels_Block_Adminhtml_Element_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('em_productlabels/edit/form.phtml');
	}

	public function getLabel(){
		return Mage::registry('label');
	}

	protected function _prepareForm()
	{
		$label = $this->getLabel();
		$form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
        							  'enctype' => 'multipart/form-data'
                                   )
		);

		$form->setUseContainer(true);
		$this->setForm($form);
		$form->setDataObject($label);
		
		$fieldset = $form->addFieldset('productlabels_form', array('legend'=>Mage::helper('productlabels')->__('General information')));

		$group = array(
			'name','image','background','texthtml','css_class','status'
		);
		$attributes = $label->getAttributes($group);
		
		$this->_setFieldset($attributes,$fieldset);
		$this->initFormCondition($form);
		
		$fieldset->addField('store', 'hidden', array(
			'name'    => 'store',
		));

		$fieldset->addField('id', 'hidden', array(
			'name'    => 'id',
		));

		if ( Mage::getSingleton('adminhtml/session')->getLabelData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getLabelData());
			Mage::getSingleton('adminhtml/session')->setLabelData(null);
		} elseif ( $label ) {
			$form->setValues($label->getData());
		}
		return parent::_prepareForm();
	}

  

    public function initFormCondition($form)
    {
         $model = Mage::getModel('productlabels/rule');
         
         $actionsArr = unserialize($this->getLabel()->getActions());
         
         if (!empty($actionsArr) && is_array($actionsArr)) {
             $model->getActions()->loadArray($actionsArr);
         }
        
         $model->getActions()->setJsFormObject('rule_actions_fieldset');

         $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/promo_quote/newActionHtml/form/rule_actions_fieldset'));

        $fieldset = $form->addFieldset('rule_actions_fieldset', array(
            'legend'=>Mage::helper('productlabels')->__('Conditions')
        ))->setRenderer($renderer);

        $element = $fieldset->addField('actions', 'text', array(
            'name' => 'actions',
            'label' => Mage::helper('salesrule')->__('Apply To'),
            'title' => Mage::helper('salesrule')->__('Apply To'),
            'required' => true,
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/actions'));

        $element->setAfterElementHtml('<span class="value scope-label">'.Mage::helper('productlabels')->__('[GLOBAL]').'</span>');
        
        $form->setValues($model->getData());

    }
	
	protected function _getAdditionalElementTypes()
    {
        return array(
            'image' => Mage::getConfig()->getBlockClassName('productlabels/adminhtml_productlabels_helper_image')
        );
    }

}