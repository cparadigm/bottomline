<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Customer;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use VladimirPopov\WebForms\Model;

class Result extends Action
{
    protected $_session;

    protected $_coreRegistry;

    protected $resultPageFactory;

    protected $_formFactory;

    protected $_resultFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $session,
        Model\FormFactory $formFactory,
        Model\ResultFactory $resultFactory,
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_session = $session;
        $this->_formFactory = $formFactory;
        $this->_resultFactory = $resultFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_session->authenticate($this);

        $resultId = $this->getRequest()->getParam('id');
        $result = $this->_resultFactory->create()->load($resultId);
        if($result->getCustomerId() != $this->_session->getCustomerId()) $this->_redirect('customer/account');
        $groupId = $this->_session->getCustomerGroupId();
        $webform = $this->_formFactory->create()->setStoreId($result->getStoreId())->load($result->getWebformId());
        if(!$webform->getIsActive() || !$webform->getDashboardEnable() || !in_array($groupId, $webform->getDashboardGroups())) $this->_redirect('customer/account');

        $this->_coreRegistry->register('webforms_result', $result);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()->getBlock('page.main.title')->setPageTitle($result->getEmailSubject());
        $resultPage->getConfig()->getTitle()->set($result->getEmailSubject());

        return $resultPage;
    }
}
