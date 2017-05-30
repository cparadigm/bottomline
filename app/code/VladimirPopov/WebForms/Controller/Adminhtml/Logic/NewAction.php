<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Logic;

use VladimirPopov\WebForms\Model;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('field_id')){
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')->load($this->getRequest()->getParam('field_id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$model->getWebformId());
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
