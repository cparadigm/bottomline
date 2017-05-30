<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block;

use Magento\Framework\View\Element\Template;

class Form extends \Magento\Framework\View\Element\Template
{
    protected $_form;

    protected $_filterProvider;

    protected $_coreRegistry;

    protected $_formFactory;

    protected $_customerSession;

    protected $_customerSessionFactory;

    protected $_success;

    protected $_customerUrl;

    protected $_resultCollectionFactory;

    protected $_resultFactory;

    protected $_response;

    protected $_captcha;

    protected $_storeManager;

    protected $_uid;

    protected $random;

    protected $webformsHelper;

    public function __construct(
        \Magento\Customer\Model\SessionFactory $customerSessionFactory,
        Template\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Registry $coreRegistry,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \Magento\Customer\Model\Url $customerUrl,
        \VladimirPopov\WebForms\Model\ResourceModel\Result\CollectionFactory $resultCollectionFactory,
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \Magento\Framework\App\Response\Http $response,
        \VladimirPopov\WebForms\Model\CaptchaFactory $captcha,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Math\Random $random,
        \VladimirPopov\WebForms\Helper\Data $webformsHelper,
        array $data = []
    )
    {
        $this->_customerUrl = $customerUrl;
        $this->_filterProvider = $filterProvider;
        $this->_coreRegistry = $coreRegistry;
        $this->_formFactory = $formFactory;
        $this->_customerSessionFactory = $customerSessionFactory;
        $this->_resultCollectionFactory = $resultCollectionFactory;
        $this->_resultFactory = $resultFactory;
        $this->_response = $response;
        $this->_captcha = $captcha;
        $this->_storeManager = $storeManager;
        $this->random = $random;
        $this->webformsHelper = $webformsHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \VladimirPopov\WebForms\Model\Form
     */
    public function getForm()
    {
        return $this->_form;
    }

    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }

    public function setSuccess($value)
    {
        $this->_success = $value;
    }

    public function getSuccess()
    {
        return $this->_success;
    }

    public function getScope()
    {
        return \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    }

    public function getRegistry()
    {
        return $this->_coreRegistry;
    }

    public function getSession()
    {
        return $this->_customerSession;
    }

    protected function _toHtml()
    {
        if ($this->_coreRegistry->registry('webforms_preview')) {
            if (strlen($this->_storeManager->getStore()->getConfig('webforms/general/preview_template')) > 1)
                $this->setTemplate($this->_storeManager->getStore()->getConfig('webforms/general/preview_template'));
        }

        if (!$this->_coreRegistry->registry('webforms_preview'))
            $this->initForm();

        if (!$this->getForm()->canAccess())
            $this->setTemplate('VladimirPopov_WebForms::webforms/access_denied.phtml');

        $html = parent::_toHtml();

        $messages = $this->getLayout()->getMessagesBlock();
        if (!$this->webformsHelper->isProduction())
            $messages->addNotice($this->webformsHelper->getNote());
        $html = $messages->getGroupedHtml() . $html;

        return $html;
    }

    public function getPageFilter()
    {
        return $this->_filterProvider->getPageFilter();
    }

    protected function initForm()
    {
        $show_success = false;
        $data = $this->getFormData();
        //get form data
        $form = $this->_formFactory->create()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->load($data['webform_id']);

        $form->getFieldsToFieldsets();
        $this->setForm($form);
        $this->_coreRegistry->unregister('webform');
        $this->_coreRegistry->register('webform', $form);

        //delete form temporary data
        if($this->isAjax()){
            $this->_session->setData('webform_result_tmp_' . $form->getId(), false);
        }

        //proccess texts
        $form->setDescription($this->getPageFilter()->filter($form->getDescription()));
        $form->setSuccessText($this->getPageFilter()->filter($form->getSuccessText()));

        $this->_customerSession = $this->_customerSessionFactory->create();
        $loggedIn = $this->_customerSession->isLoggedIn();
        if ($form->getSurvey()) {
            $collection = $this->_resultCollectionFactory->create()->addFilter('webform_id', $data['webform_id']);

            if ($loggedIn)
                $collection->addFilter('customer_id', $this->_customerSession->getCustomerId());
            else {
                $customerSession_validator = $this->_customerSession->getData('_customerSession_validator_data');
                $collection->addFilter('customer_ip', ip2long($customerSession_validator['remote_addr']));
            }
            $count = $collection->count();

            if ($count > 0) {
                $show_success = true;
            }
        }

        if ($this->_customerSession->getFormSuccess() == $this->getForm()->getId() || $show_success) {
            $this->setSuccess(true);
            $this->_customerSession->setFormSuccess();
            if ($this->_customerSession->getData('webform_result_' . $form->getId())) {

                // apply custom variables
                $filter = $this->getPageFilter();
                $formObject = new \Magento\Framework\DataObject;
                $formObject->setData($form->getData());
                $resultObject = $this->_resultFactory->create()->load(($this->_customerSession->getData('webform_result_' . $form->getId())));
                $subject = $resultObject->getEmailSubject('customer');
                $filter->setVariables(array(
                    'webform_result' => $resultObject->toHtml('customer'),
                    'result' => $resultObject->getTemplateResultVar(),
                    'webform' => $formObject,
                    'webform_subject' => $subject
                ));

                $form->setData('success_text', $filter->filter($form->getSuccessText()));
                $this->_customerSession->setData('webform_result_' . $form->getId());
            }
        }
        if ($form->getAccessEnable() && !$loggedIn && !$this->getData('results')) {
            $this->_customerSession->setBeforeAuthUrl($this->_urlBuilder->getCurrentUrl());
            $login_url = $this->_customerUrl->getLoginUrl();
            $status = 301;

            if ($this->_storeManager->getStore()->getConfig('webforms/general/login_redirect')) {
                $login_url = $this->getUrl($this->_storeManager->getStore()->getConfig('webforms/general/login_redirect'));

                if (strstr($this->_storeManager->getStore()->getConfig('webforms/general/login_redirect'), '://'))
                    $login_url = $this->_storeManager->getStore()->getConfig('webforms/general/login_redirect');
            }
            $this->_response->setRedirect($login_url, $status);
        }
        //proccess the result
        if ($this->getRequest()->getParam('submitForm_' . $form->getId()) && $form->getIsActive()) {
            //validate
            $success = $form->savePostResult();
            if ($success) {
                $this->_customerSession->setFormSuccess($this->getForm()->getId());
                $this->_customerSession->setData('webform_result_' . $form->getId(), $success);
            }

            //redirect after successful submission
            $url = $this->_urlBuilder->getCurrentUrl();
            $url = strtok($url, '?');

            if (!$success)
                $this->_response->setRedirect($url);

            if ($form->getRedirectUrl()) {
                if (strstr($form->getRedirectUrl(), '://'))
                    $url = $form->getRedirectUrl();
                else
                    $url = $this->getUrl($form->getRedirectUrl());
            }

            if ($success)
                $this->_response->setRedirect($url);
        }
        return $this;
    }

    // check that form is available for direct access
    public function isDirectAvailable()
    {
        $available = new \Magento\Framework\DataObject;
        $status = true;
        if ($this->_coreRegistry->registry('webforms_preview') && !$this->_storeManager->getStore()->getConfig('webforms/general/preview_enabled'))
            $status = false;
        $available->setData('status', $status);

        $this->_eventManager->dispatch('webforms_direct_available', array
        (
            'available' => $available,
            'form_data' => $this->getFormData()
        ));

        return $available->getData('status');
    }

    public function getNotAvailableMessage()
    {
        $message = __('Web-form is not active.');

        if ($this->getForm()->getIsActive() && !$this->isDirectAvailable())
            $message = __('Web-form is locked by configuration and can not be accessed directly.');
        return $message;
    }

    public function getFormData()
    {
        $data = $this->getRequest()->getParams();

        if (isset($data['id'])) {
            $data['webform_id'] = $data['id'];
        }

        if ($this->getData('webform_id')) {
            $data['webform_id'] = $this->getData('webform_id');
        }

        if (empty($data['webform_id'])) {
            $data['webform_id'] = $this->_storeManager->getStore()->getConfig('webforms/contacts/webform');
        }
        return $data;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->_coreRegistry->registry('webforms_preview')) {

            $this->initForm();
            $this->pageConfig->getTitle()->set($this->getForm()->getName());
        }
    }

    public function getCaptcha()
    {
        return $this->getForm()->getCaptcha();
    }

    public function getEnctype()
    {
        return 'multipart/form-data';
    }

    public function isAjax()
    {
        return $this->_storeManager->getStore()->getConfig('webforms/general/ajax');
    }

    public function getFormAction()
    {
        if ($this->isAjax()) {
            $secure = strstr($this->_urlBuilder->getCurrentUrl(), 'https://') ? true : false;
            // avoid trailing slash issue
            return $this->getUrl('', array('_secure' => $secure)) . 'webforms/form/submit';
        }
        return $this->_urlBuilder->getCurrentUrl();
    }

    public function honeypot()
    {
        return $this->_storeManager->getStore()->getConfig('webforms/honeypot/enable');
    }

    public function getUid()
    {
        if (!$this->_uid) {
            $this->_uid = $this->random->getRandomString(6);
        }
        return $this->_uid;
    }

    public function getFieldUid($field_id)
    {
        return $this->getUid() . $field_id;
    }
}