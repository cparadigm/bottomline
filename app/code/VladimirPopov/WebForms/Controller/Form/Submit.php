<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Form;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Submit extends Action
{
    protected $_coreRegistry;

    protected $resultPageFactory;

    protected $resultHttpFactory;

    protected $_jsonEncoder;

    protected $_formFactory;

    protected $_resultFactory;

    protected $_filterProvider;

    protected $messageManager;

    protected $_storeManager;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Json\Encoder $jsonEncoder,
        PageFactory $resultPageFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\HttpFactory $resultHttpFactory,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \Magento\Store\Model\StoreManager $storeManager
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->resultHttpFactory = $resultHttpFactory;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_formFactory = $formFactory;
        $this->_resultFactory = $resultFactory;
        $this->_filterProvider = $filterProvider;
        $this->messageManager = $context->getMessageManager();
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $webform = $this->_formFactory->create()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->load($this->getRequest()->getParam("webform_id"));
        $result = ["success" => false, "errors" => []];
        if ($this->getRequest()->getParam('submitForm_' . $webform->getId()) && $webform->getIsActive()) {
            $result["success"] = $webform->savePostResult();
            if ($result["success"]) {
                if($webform->getSuccessText()) $result["success_text"] = $webform->getSuccessText();

                // apply custom variables
                $filter = $this->_filterProvider->getPageFilter();
                $webformObject = new \Magento\Framework\DataObject;
                $webformObject->setData($webform->getData());
                $resultObject = $this->_resultFactory->create()->load($result['success']);
                $subject = $resultObject->getEmailSubject('customer');
                $filter->setVariables(array(
                    'webform_result' => $resultObject->toHtml('customer'),
                    'result' => $resultObject->getTemplateResultVar(),
                    'webform' => $webformObject,
                    'webform_subject' => $subject
                ));

                $result["success_text"] = $filter->filter($webform->getSuccessText());
                if(!$result["success_text"]) $result["success_text"] = "&nbsp;";

                if ($webform->getRedirectUrl()) {
                    if (strstr($webform->getRedirectUrl(), '://'))
                        $redirectUrl = $webform->getRedirectUrl();
                    else
                        $redirectUrl = $this->_url->getUrl($webform->getRedirectUrl());
                    $result["redirect_url"] = $redirectUrl;
                }
            } else {
                $errors = $this->messageManager->getMessages(true)->getItems();
                foreach ($errors as $err) {
                    $result["errors"][] = $err->getText();
                }
                $html_errors = "";
                if (count($result["errors"]) > 1) {
                    foreach ($result["errors"] as $err) {
                        $html_errors .= '<p>' . $err . '</p>';
                    }
                    $result["errors"] = $html_errors;
                } else {
                    $result["errors"] = $result["errors"][0];
                }
            }
        }

        if (!$webform->getIsActive()) $result["errors"][] = __('Web-form is not active.');

        $json = $this->_jsonEncoder->encode($result);
        $resultHttp = $this->resultHttpFactory->create();
        $resultHttp->setNoCacheHeaders();
        $resultHttp->setHeader('Content-Type', 'text/plain', true);
        return $resultHttp->setContent($json);
    }
}
