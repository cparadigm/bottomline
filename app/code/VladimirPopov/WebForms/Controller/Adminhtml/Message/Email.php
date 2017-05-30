<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Message;

class Email extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;

    protected $_jsonEncoder;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Json\Encoder $jsonEncoder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->_jsonEncoder = $jsonEncoder;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        $model = $this->_initMessage();
        $result = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result');
        $result->load($model->getResultId());
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$result->getWebformId());
    }

    protected function _initMessage(){
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->get('VladimirPopov\WebForms\Model\Message');
        $model->load($id);
        return $model;
    }
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        $result['success'] = false;
        if ($id) {
            try {
                // init model and delete
                $model = $this->_initMessage();
                $model->sendEmail();
                $result['success'] = true;
                // display success message
            } catch (\Exception $e) {
            }
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $json = $this->_jsonEncoder->encode($result);
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setJsonData($json);
    }
}
