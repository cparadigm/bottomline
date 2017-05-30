<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Reply;

class History
    extends \Magento\Backend\Block\Template
{
    protected $_result;

    protected $_resultFactory;

    protected $_messageCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        array $data = []
    )
    {
        $this->_resultFactory = $resultFactory;
        $this->_messageCollectionFactory = $messageCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getResult()
    {
        return $this->_result;
    }

    protected function _construct()
    {
        parent::_construct();

        $id = $this->getRequest()->getParam('id');
        if (!is_array($id)) {
            $this->_result = $this->_resultFactory->create()->load($id);
        } else {
            $this->_result = false;
        }

        $this->setTemplate('webforms/reply/history.phtml');
    }

    public function getMessages()
    {
        $id = $this->getRequest()->getParam('id');
        if (!is_array($id)) {
            $collection = $this->_messageCollectionFactory->create()
                ->addFilter('result_id', $id);
            $collection->addOrder('created_time', 'desc');
            return $collection;
        }
        return false;
    }

}
