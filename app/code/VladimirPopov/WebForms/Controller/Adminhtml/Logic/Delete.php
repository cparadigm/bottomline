<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Logic;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Logic')->load($id);
        $fieldId = $model->getFieldId();
        if ($fieldId) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')->load($fieldId);
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
                $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Logic');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('The logic has been deleted.'));
                if($this->getRequest()->getParam('webform_id')){
                    return $resultRedirect->setPath('*/form/edit', ['id' => $this->getRequest()->getParam('webform_id')]);
                }
                return $resultRedirect->setPath('*/field/edit', ['id' => $model->getFieldId()]);
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit logic
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a logic to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/form/');
    }
}
