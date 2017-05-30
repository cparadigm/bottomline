<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Logic;

use VladimirPopov\WebForms\Controller\Adminhtml\AbstractMassDelete;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends AbstractMassDelete
{
    const ID_FIELD = 'logic';

    const REDIRECT_URL = 'webforms/form/edit';

    const MODEL = 'VladimirPopov\WebForms\Model\Logic';

    public function execute()
    {
        $this->redirect_params = ['id' => $this->getRequest()->getParam('id'), 'active_tab' => 'logic_section'];
        $Ids = $this->getRequest()->getParam(static::ID_FIELD);
        if (!is_array($Ids) || empty($Ids)) {
            $this->messageManager->addErrorMessage(__('Please select item(s).'));
        } else {
            try {
                foreach ($Ids as $id) {
                    $item = $this->_objectManager->get(static::MODEL)->load($id);
                    $item->delete();
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', count($Ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirectUrl = static::REDIRECT_URL;
        if($this->getRequest()->getParam('webform_id')){
            $redirectUrl = 'webforms/form/edit';
            $this->redirect_params['id'] = $this->getRequest()->getParam('webform_id');
        }
        return $resultRedirect->setPath($redirectUrl, $this->redirect_params);
    }

    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('id')){
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$model->getWebformId());
        }
        if($this->getRequest()->getParam('webform_id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('webform_id'));
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }
}
