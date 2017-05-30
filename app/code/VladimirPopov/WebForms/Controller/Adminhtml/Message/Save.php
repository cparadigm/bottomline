<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Message;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    protected $backendHelper;

    protected $_userFactory;

    protected $_filterProvider;

    protected $_resultFactory;

    protected $_messageFactory;

    protected $_scopeConfig;

    public function __construct(
        Action\Context $context,
        \Magento\User\Model\UserFactory $userFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \VladimirPopov\WebForms\Model\MessageFactory $messageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->backendHelper = $context->getHelper();
        $this->_userFactory = $userFactory;
        $this->_filterProvider = $filterProvider;
        $this->_resultFactory = $resultFactory;
        $this->_messageFactory = $messageFactory;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        $post = $this->getRequest()->getPostValue('message');
        $Ids = unserialize($post['result_id']);
        $result = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result');
        $result->load($Ids[0]);
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$result->getWebformId());
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue('message');
        $Ids = unserialize($post['result_id']);

        $user = $this->_userFactory->create()->load($this->backendHelper->getCurrentUserId());
        $i = 0;

        $filter = $this->_filterProvider->getPageFilter();

        $customerId = $this->getRequest()->getParam('customer_id');

        $resultRedirect = $this->resultRedirectFactory->create();

        foreach ($Ids as $id) {
            $result = $this->_resultFactory->create()->load($id);

            /** @var \VladimirPopov\WebForms\Model\Message $message */
            $message = $this->_messageFactory->create()
                ->setAuthor($user->getName())
                ->setUserId($user->getId())
                ->setResultId($id)
                ->save();

            // add template processing
            $filter->setStoreId($result->getStoreId());
            $filter->setVariables($message->getTemplateVars());
            $content = $filter->filter($post['message']);
            if ($this->_scopeConfig->getValue('webforms/message/nl2br')) {
                $content = str_replace("</p><br>", "</p>", nl2br($content, true));
            }

            $message->setMessage($content)->save();


            if ($post['email']) {

                if ($result->getCustomerEmail()) {

                    $success = $message->sendEmail();

                    if ($success) {
                        $i++;
                        $message->setIsCustomerEmailed(1)->save();
                    }
                }

            }
        }

        $this->messageManager->addSuccessMessage(__('Total of %1 reply(s) has been saved.', count($Ids)));

        if ($i) {
            $this->messageManager->addSuccessMessage(__('Total of %1 reply(s) has been emailed.', $i));
        }

        if ($post['email'] && $i < count($Ids)) {
            $this->messageManager->addErrorMessage(__('Total of %1 result(s) has no reply-to e-mail address.', count($Ids) - $i));
        }

        if ($customerId) {
            return $resultRedirect->setPath('adminhtml/customer/edit', array('id' => $customerId, 'tab' => 'webform_results'));
        }

        return $resultRedirect->setPath('*/result/', array('webform_id' => $post['webform_id']));
    }
}