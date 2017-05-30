<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Customer\Account\Result;

use Magento\Framework\View\Element\Template;
use VladimirPopov\WebForms\Model\ResourceModel;

class View extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry;

    public function __construct(
        Template\Context $context,
        ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = [])
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_messageCollectionFactory = $messageCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getResult()
    {
        if ($this->_coreRegistry->registry('webforms_result'))
            return $this->_coreRegistry->registry('webforms_result');
    }

    public function getMessages()
    {
        $result = $this->getResult();
        if ($result->getId()) {
            $collection = $this->_messageCollectionFactory->create()
                ->addFilter('result_id', $result->getId())
                ->addOrder('created_time', 'desc');
            return $collection;
        }
    }
}