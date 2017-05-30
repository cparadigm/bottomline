<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Customer\Account;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Context;


class Navigation extends \Magento\Framework\View\Element\Template
{
    protected $_links = [];

    protected $_path = 'webforms/customer/account';

    protected $_formCollectionFactory;

    protected $httpContext;

    public function __construct(
        Template\Context $context,
        \VladimirPopov\WebForms\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = [])
    {
        $this->_formCollectionFactory = $formCollectionFactory;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        if (!$this->isLoggedIn()) return;

        $groupId = $this->getGroupId();
        $storeId = $this->_storeManager->getStore()->getId();

        $collection = $this->_formCollectionFactory->create()->setStoreId($storeId)->addFilter('dashboard_enable',1);
        $links = array();
        foreach ($collection as $form) {
            $accessGroups = [];
            $dashboardGroups = [];
            if(is_array($form->getAccessGroups())) $accessGroups = $form->getAccessGroups();
            if(is_array($form->getDashboardGroups())) $dashboardGroups = $form->getDashboardGroups();
            if (
            (($form->getAccessEnable() && in_array($groupId, $accessGroups) || !$form->getAccessEnable())
                && $form->getIsActive()
                && $form->getDashboardEnable()
                && in_array($groupId, $dashboardGroups))
            ) {
                $active = false;
                if($this->getRequest()->getParam('webform_id') == $form->getId()) $active =true;
                $links[] = new \Magento\Framework\DataObject(array('label' => $form->getName(), 'url' => $this->getFormUrl($form), 'active' => $active));
            }
        }
        $this->_links = $links;
    }

    public function getLinks()
    {
        return $this->_links;
    }

    public function getFormUrl($form)
    {
        return $this->getUrl($this->_path, array('webform_id' => $form->getId()));
    }

    public function getBlockTitle()
    {
        return $this->_storeManager->getStore()->getConfig('webforms/general/customer_navigation_block_title');
    }

    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    public function getGroupId()
    {
        return $this->httpContext->getvalue(Context::CONTEXT_GROUP);
    }
}