<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class MassStatus extends \Magento\Backend\App\Action
{
    const ID_FIELD = 'results';

    const REDIRECT_URL = '*/*/';

    const MODEL = 'VladimirPopov\WebForms\Model\Result';

    protected $redirect_params = ['_current' => true];

    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('webform_id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('webform_id'));
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $Ids = $this->getRequest()->getParam(static::ID_FIELD);
        $status = $this->getRequest()->getParam('status');

        $formId = $this->getRequest()->getParam('webform_id');
        $modelForm = $this->_objectManager->get('VladimirPopov\WebForms\Model\Form')->load($formId);
        if (!is_array($Ids) || empty($Ids)) {
            $this->messageManager->addErrorMessage(__('Please select item(s).'));
        } else {
            try {
                foreach ($Ids as $id) {
                    $item = $this->_objectManager->get(static::MODEL)->load($id);
                    $item->setApproved(intval($status))->save();

                    if ($modelForm->getEmailResultApproval()) {
                        $item->sendApprovalEmail();
                    }

                    $this->_eventManager->dispatch('webforms_result_approve', array('result' => $item));
                }
                $this->messageManager->addSuccessMessage(
                    __('Total of %1 result(s) have been updated.', count($Ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($this->getRequest()->getParam('customer_id')) {
            return $resultRedirect->setPath('customer/index/edit', [
                'id' => $this->getRequest()->getParam('customer_id')
            ]);
        }
        return $resultRedirect->setPath(static::REDIRECT_URL, $this->redirect_params);
    }
}
