<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassDelete
 */
class AbstractMassStatus extends \Magento\Backend\App\Action
{
    const ID_FIELD = 'id';

    const REDIRECT_URL = '*/*/';

    const MODEL = 'Magento\Framework\Model\AbstractModel';

    protected $status = 0;

    protected $redirect_params = [];

    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('webform_id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('webform_id'));
        }
        if($this->getRequest()->getParam('id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('id'));
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
                foreach ($Ids as $id) {
                    $item = $this->_objectManager->get(static::MODEL)->load($id);
                    $item->setIsActive($this->status)->save();
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been updated.', count($Ids))
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
