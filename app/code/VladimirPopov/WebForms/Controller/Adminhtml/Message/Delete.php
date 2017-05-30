<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Message;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        $model = $this->_initMessage();
        $result = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result');
        $result->load($model->getResultId());
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$result->getWebformId());
    }

    protected function _initMessage(){
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->get('VladimirPopov\WebForms\Model\Message');
        $model->load($id);
        return $model;
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
        if ($id) {
            try {
                // init model and delete
                $model = $this->_initMessage();
                $model->delete();
                // display success message
            } catch (\Exception $e) {
            }
        }
    }
}
