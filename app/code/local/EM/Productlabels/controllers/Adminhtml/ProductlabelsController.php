<?php
class EM_Productlabels_Adminhtml_ProductlabelsController extends Mage_Adminhtml_Controller_Action
{
	protected function _initLabel(){
		$id = $this->getRequest()->getParam('id');
		
        if(!Mage::registry('label') || Mage::registry('label')->getId()!=$id)
        {
            $label = Mage::getModel('productlabels/productlabels')->setStoreId($this->getRequest()->getParam('store',0))->load($id);
			if(!$id)
				$label->setStatus(1);
			$label->setData('_edit_mode', true);
            if(Mage::registry('label'))
                Mage::unregister ('label');
            Mage::register('label', $label);
        }
        return Mage::registry('label');
	}
    protected function _initAction() {
            $this->loadLayout()
                    ->_setActiveMenu('productlabels/items')
                    ->_addBreadcrumb(Mage::helper('adminhtml')->__('Labels Manager'), Mage::helper('adminhtml')->__('Labels Manager'));

            return $this;
    }

    public function indexAction() {
            $this->_initAction();
			$this->getLayout()->getBlock('head')->setTitle(Mage::helper('productlabels')->__('Manage labels'));
            $this->renderLayout();
    }

    public function loadCondition($conditions)
    {
        $rule = Mage::getModel('rule/rule');
        $actionsArr = unserialize($rule->getActionsSerialized());
        if (!empty($actionsArr) && is_array($actionsArr)) {
            $rule->getActions()->loadArray($actionsArr);
        }
    }

    public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$label  = $this->_initLabel();
		$label->setData('variables',Zend_Json::encode($this->getVariables()));
		$storeId = $this->getRequest()->getParam('store',0);
		$label->setData('store',$storeId);
		if ($label->getId() || $id == 0) {
			  

				$this->loadLayout();
				
				$this->renderLayout();
		} else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabels')->__('Item does not exist'));
				$this->_redirect('*/*/');
		}
    }

    public function newAction() {
        $this->_forward('edit');
            
    }

	/**
     * Initialize label before saving
     */
    protected function _initLabelSave()
    {
        $label     = $this->_initLabel();
        if($data = $this->getRequest()->getPost()){
			if($data['store'])
				$label->setStoreId($data['store']);
			$label->addData($data);
			/**
			 * Check "Use Default Value" checkboxes values
			 */
			
			if ($useDefaults = $this->getRequest()->getPost('use_default')) {
				foreach ($useDefaults as $attributeCode) {
					$label->setData($attributeCode, false);
				}
			}
			$label->setActions($this->initCondition($data['rule']));
		}
		return $label;
    }

    public function initCondition($actions)
    {
        $rule = Mage::getModel('salesrule/rule');
        $rule->loadPost($actions);
        
        
        if ($rule->getActions()) {
            $rule->setActionsSerialized(serialize($rule->getActions()->asArray()));
            $rule->unsActions();
        }
        return $rule->getActionsSerialized();
    }

    public function saveAction() {
        if ($label = $this->_initLabelSave()) {
            try {
                $label->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productlabels')->__('Label was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $label->getId(),
                                                           'store' => $this->getRequest()->getParam('store',0)
                                                           )
                                        );
                        return;
                }
                $this->_redirect('*/*/',array('store' => $this->getRequest()->getParam('store',0)));
                    return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'),
                                                   'store' => $this->getRequest()->getParam('store',0))
                                );
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabels')->__('Unable to find label to save'));
        $this->_redirect('*/*/',array('store' => $this->getRequest()->getParam('store',0)));
    }

    public function deleteAction() {
            if( $this->getRequest()->getParam('id') > 0 ) {
                    try {
                            $model = Mage::getModel('productlabels/productlabels');

                            $model->setId($this->getRequest()->getParam('id'))
                                    ->delete();

                            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Label was successfully deleted'));
                            $this->_redirect('*/*/');
                    } catch (Exception $e) {
                            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'),'store' => $this->getRequest()->getParam('store',0)));
                    }
            }
            $this->_redirect('*/*/',array('store' => $this->getRequest()->getParam('store',0)));
    }

    public function massDeleteAction() {
        $productlabelsIds = $this->getRequest()->getParam('productlabels');
        if(!is_array($productlabelsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select label(s)'));
        } else {
            try {
                foreach ($productlabelsIds as $productlabelsId) {
                    $productlabels = Mage::getModel('productlabels/productlabels')->load($productlabelsId);
                    $productlabels->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($productlabelsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index',array('store' => $this->getRequest()->getParam('store',0)));
    }
	
    public function massStatusAction()
    {
        $productlabelsIds = $this->getRequest()->getParam('productlabels');
		$storeId = $this->getRequest()->getParam('store',0);
        if(!is_array($productlabelsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select label(s)'));
        } else {
            try {
                foreach ($productlabelsIds as $productlabelsId) {
                    $productlabels = Mage::getSingleton('productlabels/productlabels')
						->setStoreId($storeId)
                        ->load($productlabelsId);
                    $productlabels->setStatus(2-$this->getRequest()->getParam('status'));
                    $productlabels->save();    
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($productlabelsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index',array('store' => $this->getRequest()->getParam('store',0)));
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'productlabels.csv';
        $content    = $this->getLayout()->createBlock('productlabels/adminhtml_productlabels_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'productlabels.xml';
        $content    = $this->getLayout()->createBlock('productlabels/adminhtml_productlabels_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    public function getVariables()
    {
        $data = array(
            'label' => 'Variables',
            'value' => array(
                array(
                'label' => 'Save Percent',
                'value' => '{{var save_percent}}'
                ),
                array(
                    'label' => 'Save Price',
                    'value' => '{{var save_price}}'
                ),
                array(
                    'label' => 'Product Price',
                    'value' => '{{var product.price}}'
                ),
                array(
                    'label' => 'Product Special Price',
                    'value' => '{{var product.special_price}}'
                ),
                array(
                    'label' => 'The Quantity Of Product',
                    'value' => '{{var product.qty}}'
                )
            )
        );
        $variables = array($data);

        return $variables;
    }
}