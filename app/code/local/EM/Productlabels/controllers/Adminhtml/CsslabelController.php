<?php
class EM_Productlabels_Adminhtml_CsslabelController extends Mage_Adminhtml_Controller_Action
{
    public function editAction() {
        $storeId = $this->getRequest()->getParam('store',0);
        $collection = Mage::getModel('productlabels/css')->getCollection();
		$model = Mage::getModel('productlabels/css');
		if($collection->count())
			$model = Mage::getModel('productlabels/css')->setStoreId($storeId)->load($collection->getFirstItem()->getId());
      
		$model->setData('store',$this->getRequest()->getParam('store',0));
        Mage::register('productlabels_css_data', $model);
		$this->loadLayout();
		$this->_setActiveMenu('productlabels/items');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Css Manager'), Mage::helper('adminhtml')->__('Css Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Css News'), Mage::helper('adminhtml')->__('Css News'));
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('productlabels')->__("Css Editor"));
        $this->renderLayout();
    }

    

    public function saveAction() {
		try {
			if($data = $this->getRequest()->getPost())
			{
				$css = Mage::getModel('productlabels/css');
				$storeId = $data['store'];
				if($storeId)
					$css->setStoreId($storeId);
				if($data['id'])
					$css->load($data['id']);
				unset($data['id']);	
				unset($data['store']);	
				$css->addData($data);
				
				/**
				 * Check "Use Default Value" checkboxes values
				 */
				
				if ($useDefaults = $this->getRequest()->getPost('use_default')) {
					foreach ($useDefaults as $attributeCode) {
						$css->setData($attributeCode, false);
					}
				}
				$css->save();	
			}
			
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productlabels')->__('Css was successfully saved'));
			Mage::getSingleton('adminhtml/session')->setFormData(false);

			if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('store' => $storeId));
					return;
			}
			$this->_redirect('*/productlabels',array('store' => $storeId));
				return;
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			Mage::getSingleton('adminhtml/session')->setFormData($data);
			$this->_redirect('*/*/edit', array('store' => $storeId));
			return;
		}
        
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabels')->__('Unable to find css to save'));
        $this->_redirect('*/*/',array('store' => $data['store']));
    }

  
}