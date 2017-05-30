<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        if ($this->getRequest()->getParam('id')) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $model->getWebformId());
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('The result has been deleted.'));
                return $resultRedirect->setPath('*/*/', ['webform_id' => $model->getWebformId()]);
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit result
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a result to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/form/');
    }
}
