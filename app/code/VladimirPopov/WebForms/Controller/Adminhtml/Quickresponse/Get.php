<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Quickresponse;

class Get extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    protected $_jsonEncoder;

    protected $_quickresponseFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Json\Encoder $jsonEncoder,
        \VladimirPopov\WebForms\Model\QuickresponseFactory $quickresponseFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->_jsonEncoder = $jsonEncoder;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_quickresponseFactory = $quickresponseFactory;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::webforms');
    }

    /**
     * Categories tree node (Ajax version)
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if($id) {
            $quickresponse = $this->_quickresponseFactory->create()
                ->load($id)
            ;
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $json = $this->_jsonEncoder->encode($quickresponse->getData());
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setJsonData($json);
        }
    }
}
