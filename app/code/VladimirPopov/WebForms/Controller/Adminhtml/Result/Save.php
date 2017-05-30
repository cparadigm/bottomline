<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        if ($this->getRequest()->getParam('webform_id')) {
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $this->getRequest()->getParam('webform_id'));
        }

        if ($this->getRequest()->getParam('id')) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $model->getWebformId());
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue('result');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $modelResult = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result');
            $modelForm = $this->_objectManager->create('VladimirPopov\WebForms\Model\Form');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $modelResult->load($id);
                $webformId = $modelResult->getWebformId();
            } else {
                $webformId = $data['webform_id'];
            }
            $customerId = $this->getRequest()->getParam('customer_id');

            if ($webformId) {

                $modelForm->load($webformId);
                $modelForm->setData('disable_captcha', true);
                if ($data['store_id'])
                    $storeId = $data['store_id'];
                else
                    $storeId = $modelResult->getStoreId();

                $this->_eventManager->dispatch(
                    'webforms_fieldset_prepare_save',
                    ['result' => $modelResult, 'form' => $modelForm, 'request' => $this->getRequest()]
                );

                $resultId = $modelForm->savePostResult(
                    array(
                        'prefix' => 'result'
                    )
                );
                if ($resultId) {
                    $modelResult->load($resultId);
                    if ($data['customer_id'])
                        $modelResult->setCustomerId($data['customer_id']);
                    $modelResult->setStoreId($storeId)->save();
                }

                // if we get validation error
                if (!$resultId) {
                    if ($data['result_id']) {
                        $resultId = $data['result_id'];
                        if ($customerId) {
                            return $resultRedirect->setPath('adminhtml/customer/edit', array('id' => $customerId, 'tab' => 'webform_results'));
                        }
                        return $resultRedirect->setPath('*/*/edit', array('_current' => true, 'id' => $resultId));
                    }
                    return $resultRedirect->setPath('*/*/new', array('webform_id' => $webformId));
                }

                // recover store id
                $modelResult->load($resultId)->setStoreId($storeId)->save();
                $this->messageManager->addSuccessMessage(__('Result was successfully saved'));

                if ($this->getRequest()->getParam('customer_id')) {
                    return $resultRedirect->setPath('customer/index/edit', [
                        'id' => $this->getRequest()->getParam('customer_id')
                    ]);
                }

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', array('_current' => true, 'id' => $resultId));
                } else {
                    if ($customerId) {
                        return $resultRedirect->setPath('adminhtml/customer/edit', array('id' => $customerId, 'tab' => 'webform_results'));
                    }
                    return $resultRedirect->setPath('*/*/index', array('webform_id' => $webformId));
                }
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath('*/*/edit', ['id' => $id, 'webform_id' => $this->getRequest()->getParam('webform_id')]);
        }
        return $resultRedirect->setPath('webforms/form/');
    }
}