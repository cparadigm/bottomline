<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class MassEmail extends \Magento\Backend\App\Action
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
        if (!is_array($Ids) || empty($Ids)) {
            $this->messageManager->addErrorMessage(__('Please select item(s).'));
        } else {
            try {
                $contact = false;
                $recipient = 'admin';
                if ($this->getRequest()->getParam('recipient_email')) {
                    $contact = array(
                        'name' => $this->getRequest()->getParam('recipient_email'),
                        'email' => $this->getRequest()->getParam('recipient_email'));
                    $recipient = 'contact';
                }
                foreach ($Ids as $id) {
                    /** @var \VladimirPopov\WebForms\Model\Result $item */
                    $item = $this->_objectManager->get(static::MODEL)->load($id);
                    $item->sendEmail($recipient, $contact);
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been emailed.', count($Ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath(static::REDIRECT_URL, $this->redirect_params);
    }
}
