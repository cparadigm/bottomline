<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Customer\Account;

use Magento\Framework\View\Element\Template;
use VladimirPopov\WebForms\Model\ResourceModel;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Session;


class Results extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry;

    /** @var  ResourceModel\Result\Collection */
    protected $_resultsCollection;

    protected $_resultCollectionFactory;

    protected $_messageCollectionFactory;

    protected $_htmlPagerBlock;

    protected $_session;

    public function __construct(
        Template\Context $context,
        ResourceModel\Result\CollectionFactory $resultCollectionFactory,
        ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        Session $session,
        array $data = [])
    {
        $this->_resultsCollectionFactory = $resultCollectionFactory;
        $this->httpContext = $httpContext;
        $this->_coreRegistry = $coreRegistry;
        $this->_messageCollectionFactory = $messageCollectionFactory;
        $this->_htmlPagerBlock = $htmlPagerBlock;
        $this->_session = $session;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($toolbar = $this->_htmlPagerBlock) {
            $toolbar->setCollection($this->getCollection());
            $this->addChild('toolbar', $toolbar);
        }

        return $this;
    }

    public function getCollection()
    {
        if (null === $this->_resultsCollection) {
            $webform = $this->_coreRegistry->registry('webforms_form');
            $this->_resultsCollection = $this->_resultsCollectionFactory->create()
                ->setLoadValues(true)
                ->addFilter('webform_id', $webform->getId())
                ->addFilter('customer_id', $this->_coreRegistry->registry('customer_id'))
                ->addOrder('created_time','desc');
        }
        return $this->_resultsCollection;
    }

    public function getForm()
    {
        return $this->_coreRegistry->registry('webforms_form');
    }

    public function getUrlResultView(\VladimirPopov\WebForms\Model\Result $result)
    {
        return $this->getUrl('webforms/customer/result', array('id' => $result->getId()));
    }

    public function getRepliedStatus(\VladimirPopov\WebForms\Model\Result $result)
    {
        $messages = $this->_messageCollectionFactory->create()->addFilter('result_id', $result->getId())->count();
        if ($messages) return __('Yes');
        return __('No');
    }

    public function getApproveStatus(\VladimirPopov\WebForms\Model\Result $result)
    {
        $statuses = $result->getApprovalStatuses();
        foreach ($statuses as $id => $text)
            if ($result->getApproved() == $id)
                return $text;

    }
}