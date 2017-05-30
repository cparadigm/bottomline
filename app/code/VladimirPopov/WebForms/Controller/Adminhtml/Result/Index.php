<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    protected $_coreRegistry;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('webform_id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('webform_id'));
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initForm($idFieldName = 'id')
    {
        $formId = (int)$this->getRequest()->getParam($idFieldName);
        $store = $this->getRequest()->getParam('store');
        $form = $this->_objectManager->create('VladimirPopov\WebForms\Model\Form');
        $form->setStoreId($store);
        if ($formId) {
            $form->load($formId);
        }

        $this->_coreRegistry->register('webforms_form', $form);
        return $formId;
    }

    public function execute()
    {
        $this->_initForm('webform_id');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('VladimirPopov_WebForms::manage_forms');
        $resultPage->addBreadcrumb(__('Web-forms'), __('Web-forms'));
        $resultPage->addBreadcrumb(__('Manage Results'), __('Manage Results'));
        $resultPage->getConfig()->getTitle()->prepend($this->_coreRegistry->registry('webforms_form')->getName());

        return $resultPage;
    }
}