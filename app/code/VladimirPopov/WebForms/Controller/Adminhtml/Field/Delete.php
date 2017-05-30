<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Field;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('id')){
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$model->getWebformId());
        } else if($this->getRequest()->getParam('webform_id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('webform_id'));
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
                $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('The field has been deleted.'));
                return $resultRedirect->setPath('*/form/', ['id' => $model->getWebformId(), 'active_tab' => 'fields_section']);
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a field to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/form/');
    }
}
