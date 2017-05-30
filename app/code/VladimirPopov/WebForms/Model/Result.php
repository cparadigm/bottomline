<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Result extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_NOTAPPROVED = -1;
    const STATUS_COMPLETED = 2;

    protected $_webform;

    /**
     * Result cache tag
     */
    const CACHE_TAG = 'webforms_result';

    /**
     * @var string
     */
    protected $_cacheTag = 'webforms_result';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'webforms_result';

    protected $_fieldFactory;

    protected $_customerFactory;

    protected $_formFactory;

    protected $_storeManager;

    protected $_scopeConfig;

    protected $_transportBuilder;

    protected $_request;

    protected $_localeDate;

    protected $_regionFactory;

    protected $translatedLists;

    protected $_customerSession;

    protected $_filterProvider;

    protected $_templateFactory;

    protected $_imageFactory;

    protected $uploaderFactory;

    protected $fileCollectionFactory;

    public function __construct(
        \Magento\Framework\Locale\TranslatedLists $translatedLists,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \VladimirPopov\WebForms\Model\UploaderFactory $uploaderFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Email\Model\TemplateFactory $templateFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_fieldFactory = $fieldFactory;
        $this->_customerFactory = $customerFactory;
        $this->_formFactory = $formFactory;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_request = $request;
        $this->_localeDate = $localeDate;
        $this->_regionFactory = $regionFactory;
        $this->translatedLists = $translatedLists;
        $this->_customerSession = $sessionFactory->create();
        $this->_filterProvider = $filterProvider;
        $this->_templateFactory = $templateFactory;
        $this->_imageFactory = $imageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->fileCollectionFactory = $fileCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\Result');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData('id');
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    public function getScope()
    {
        return \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    }

    public function addFieldArray($preserveFrontend = false, $field_types = array())
    {
        $data = $this->getData();
        $field_array = array();
        foreach ($data as $key => $value) {
            if (strstr($key, 'field_')) {
                $field_id = str_replace('field_', '', $key);
                $field = $this->_fieldFactory->create()->load($field_id);
                if ($field->getType() == 'select/checkbox' && !is_array($value)) $value = explode("\n", $value);
                if ($field->getType() == 'select/contact' && $preserveFrontend) {
                    $contact_array = $field->getContactArray($field->getValue('options'));
                    for ($i = 0; $i < count($contact_array); $i++) {

                        if ($field->getContactValueById($i) == $value) {
                            $value = $i;
                            break;
                        }
                    }
                }
                if (!count($field_types) || (count($field_types) && in_array($field->getType(), $field_types)))
                    $field_array[$field_id] = $value;
            }
        }
        $this->setData('field', $field_array);
        return $this;
    }

    public function getApprovalStatuses()
    {
        $statuses = new \Magento\Framework\DataObject(array(
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_COMPLETED => __('Completed'),
            self::STATUS_NOTAPPROVED => __('Not Approved'),
        ));

        $this->_eventManager->dispatch('webforms_results_statuses', array('statuses' => $statuses));

        return $statuses->getData();
    }

    public function getCustomer()
    {
        if (!$this->getCustomerId()) return false;

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->_customerFactory->create()->load($this->getCustomerId());
        return $customer;
    }

    public function sendEmail($recipient = 'admin', $contact = false)
    {
        $webform = $this->_formFactory->create()
            ->setStoreId($this->getStoreId())
            ->load($this->getWebformId());

        $emailSettings = $webform->getEmailSettings();

        // for admin
        $sender = Array(
            'name' => $this->getCustomerName(),
            'email' => $this->getReplyTo($recipient),
        );

        if (!$sender['name']) {
            $sender['name'] = $sender['email'];
        }

        // for customer
        if ($recipient == 'customer') {
            $sender_name = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();
            if(strlen(trim($webform->getEmailCustomerSenderName()))>0)
                $sender_name = $webform->getEmailCustomerSenderName();
            $sender['name'] = $sender_name;

            $contact_array = $this->getContactArray();

            // send letter from selected contact
            if ($contact_array) {
                $sender = $contact_array;
            }
        }

        if ($this->_scopeConfig->getValue('webforms/email/email_from', $this->getScope())) {
            $sender['email'] = $this->_scopeConfig->getValue('webforms/email/email_from', $this->getScope());
        }

        $subject = $this->getEmailSubject($recipient);

        $email = $emailSettings['email'];

        //for customer
        if ($recipient == 'customer') {
            $email = $this->getCustomerEmail();
        }

        $name = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();

        if ($recipient == 'customer') {
            $name = $this->getCustomerName();
        }

        if ($recipient == 'contact') {
            if (empty($contact['email'])) return false;
            $email = $contact['email'];
            $name = $contact['name'];
            $recipient = 'admin';
        }

        $webformObject = new \Magento\Framework\DataObject();
        $webformObject->setData($webform->getData());

        $store_group = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();
        $store_name = $this->_storeManager->getStore($this->getStoreId())->getName();

        $vars = Array(
            'webform_subject' => $subject,
            'webform_name' => $webform->getName(),
            'webform_result' => $this->toHtml($recipient),
            'recipient' => $recipient,
            'customer_name' => $this->getCustomerName(),
            'customer_email' => $this->getCustomerEmail(),
            'ip' => $this->getIp(),
            'store_group' => $store_group,
            'store_name' => $store_name,
            'result' => $this->getTemplateResultVar(),
            'webform' => $webformObject,
            'timestamp' => $this->_localeDate->formatDate($this->getCreatedTime(), \IntlDateFormatter::SHORT, true),
        );

        $customer = $this->getCustomer();

        if ($customer) {
            $vars['customer'] = $customer;
            $billing_address = $customer->getDefaultBillingAddress();
            if ($billing_address) {
                $vars['billing_address'] = $billing_address;
            }
            $shipping_address = $customer->getDefaultShippingAddress();
            if ($shipping_address) {
                $vars['shipping_address'] = $shipping_address;
            }
        }

        $post = $this->_request->getPostValue();

        if ($post) {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            // set region name if found
            if (!empty($post['region_id'])) {
                $postObject->setData('region_name', $post['region_id']);
                $region_name = $this->_regionFactory->create()->load($post['region_id'])->getName();
                if ($region_name) {
                    $postObject->setData('region_name', $region_name);
                }
            }
            $vars['data'] = $postObject;
        }

        $vars['noreply'] = __('Please, don`t reply to this e-mail!');

        $storeId = $this->getStoreId();
        $templateId = 'webforms_notification';
        if ($webform->getEmailTemplateId()) {
            $templateId = $webform->getEmailTemplateId();
        }
        if ($recipient == 'customer') {
            if ($webform->getEmailCustomerTemplateId()) {
                $templateId = $webform->getEmailCustomerTemplateId();
            }
        }
        $file_list = $this->getFiles();
        $send_multiple_admin = false;
        if (is_string($email)) {
            if ($recipient == 'admin' && strstr($email, ','))
                $send_multiple_admin = true;
        }

        if ($recipient == 'admin') {
            $bcc_list = explode(',', $webform->getBccAdminEmail());
        }

        if ($recipient == 'customer') {
            $bcc_list = explode(',', $webform->getBccCustomerEmail());
        }
        // trim bcc array
        array_walk($bcc_list, create_function('&$val', '$val = trim($val);'));

        $validateEmail = new \Zend_Validate_EmailAddress();

        if ($send_multiple_admin) {
            $email_array = explode(',', $email);
            foreach ($email_array as $email) {

                $this->_transportBuilder
                    ->setTemplateIdentifier($templateId)
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $this->_storeManager->getStore()->getId(),
                        ]
                    )
                    ->setTemplateVars($vars)
                    ->setFrom($sender)
                    ->addTo($email)
                    ->setReplyTo($this->getReplyTo($recipient));

                //file content is attached
                if ($webform->getEmailAttachmentsAdmin())
                    foreach ($file_list as $file) {
                        $attachment = file_get_contents($file->getFullPath());
                        $this->_transportBuilder->createAttachment(
                            $attachment,
                            \Zend_Mime::TYPE_OCTETSTREAM,
                            \Zend_Mime::DISPOSITION_ATTACHMENT,
                            \Zend_Mime::ENCODING_BASE64,
                            $file['name']
                        );
                    }

                //attach pdf version to email
                if ($webform->getPrintAttachToEmail()) {
                    if (@class_exists('mPDF')) {

                        $mpdf = @new \mPDF('utf-8', 'A4');
                        $mpdf->WriteHTML($this->toPrintableHtml());

                        $this->_transportBuilder->getMessage()->createAttachment(
                            @$mpdf->Output('', 'S'),
                            \Zend_Mime::TYPE_OCTETSTREAM,
                            \Zend_Mime::DISPOSITION_ATTACHMENT,
                            \Zend_Mime::ENCODING_BASE64,
                            $this->getPdfFilename()
                        );
                    }
                }
                if (is_array($bcc_list))
                    foreach ($bcc_list as $bcc) {
                        if ($validateEmail->isValid($bcc)) {
                            $this->_transportBuilder->getMessage()->addBcc($bcc);
                        }
                    }

                $this->_transportBuilder->getTransport()->sendMessage();
            }
        } else {
            $this->_transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->_storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars($vars)
                ->setFrom($sender)
                ->addTo($email)
                ->setReplyTo($this->getReplyTo($recipient));

            //file content is attached
            if (($webform->getEmailAttachmentsAdmin() && $recipient == 'admin') || ($webform->getEmailAttachmentsCustomer() && $recipient == 'customer'))
                foreach ($file_list as $file) {
                    $attachment = file_get_contents($file->getFullPath());
                    $this->_transportBuilder->createAttachment(
                        $attachment,
                        \Zend_Mime::TYPE_OCTETSTREAM,
                        \Zend_Mime::DISPOSITION_ATTACHMENT,
                        \Zend_Mime::ENCODING_BASE64,
                        $file['name']
                    );
                }

            //attach pdf version to email
            if (($webform->getPrintAttachToEmail() && $recipient == 'admin') || ($webform->getCustomerPrintAttachToEmail() && $recipient == 'customer')) {
                if (@class_exists('mPDF')) {
                    $mpdf = @new \mPDF('utf-8', 'A4');
                    @$mpdf->WriteHTML($this->toPrintableHtml($recipient));

                    $this->_transportBuilder->createAttachment(
                        @$mpdf->Output('', 'S'),
                        \Zend_Mime::TYPE_OCTETSTREAM,
                        \Zend_Mime::DISPOSITION_ATTACHMENT,
                        \Zend_Mime::ENCODING_BASE64,
                        $this->getPdfFilename()
                    );
                }
            }

            if (is_array($bcc_list))
                foreach ($bcc_list as $bcc) {
                    if ($validateEmail->isValid($bcc)) {
                        $this->_transportBuilder->getMessage()->addBcc($bcc);
                    }
                }

            $this->_transportBuilder->getTransport()->sendMessage();
        }
    }

    public function sendApprovalEmail()
    {
        $webform = $this->_formFactory->create()
            ->setStoreId($this->getStoreId())
            ->load($this->getWebformId());

        // for customer
        $sender_name = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();
        if(strlen(trim($webform->getEmailCustomerSenderName()))>0)
            $sender_name = $webform->getEmailCustomerSenderName();
        $sender['name'] = $sender_name;

        $sender['email'] = $this->getReplyTo('customer');

        if ($this->_scopeConfig->getValue('webforms/email/email_from')) {
            $sender['email'] = $this->_scopeConfig->getValue('webforms/email/email_from');
        }

        $email = $this->getCustomerEmail();

        $name = $this->getCustomerName();

        $webformObject = new \Magento\Framework\DataObject();
        $webformObject->setData($webform->getData());

        $varResult = $this->getTemplateResultVar();

        $varResult->addData(array(
            'id' => $this->getId(),
            'subject' => $this->getEmailSubject(),
            'date' => $this->_localeDate->formatDate($this->getCreatedTime()),
            'html' => $this->toHtml('customer'),
        ));

        $store_group = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();
        $store_name = $this->_storeManager->getStore($this->getStoreId())->getName();

        $vars = Array(
            'webform_name' => $webform->getName(),
            'webform_result' => $this->toHtml('customer'),
            'customer_name' => $this->getCustomerName(),
            'customer_email' => $this->getCustomerEmail(),
            'status' => $this->getStatusName(),
            'ip' => $this->getIp(),
            'store_group' => $store_group,
            'store_name' => $store_name,
            'result' => $varResult,
            'webform' => $webformObject,
            'timestamp' => $this->_localeDate->formatDate($this->getCreatedTime(), \IntlDateFormatter::MEDIUM, true),
        );

        $customer = $this->getCustomer();

        if ($customer) {
            $vars['customer'] = $customer;
            $billing_address = $customer->getDefaultBillingAddress();
            if ($billing_address) {
                $vars['billing_address'] = $billing_address;
            }
            $shipping_address = $customer->getDefaultShippingAddress();
            if ($shipping_address) {
                $vars['shipping_address'] = $shipping_address;
            }
        }

        $storeId = $this->getStoreId();
        $templateId = 'webforms_result_approval';
        $attachPDF = false;
        $pdfTemplate = 'admin';

        if ($this->getApproved() == self::STATUS_APPROVED) {
            if ($webform->getData('email_result_approved_template_id')) {
                $templateId = $webform->getData('email_result_approved_template_id');
            }
            //attach pdf version to email
            if (($webform->getApprovedPrintAttachToEmail())) {
                $attachPDF = true;
                $pdfTemplate = 'approved';
            }
        } else if ($this->getApproved() == self::STATUS_NOTAPPROVED) {
            if ($webform->getData('email_result_notapproved_template_id')) {
                $templateId = $webform->getData('email_result_notapproved_template_id');
            }
        } else if ($this->getApproved() == self::STATUS_COMPLETED) {
            if ($webform->getData('email_result_completed_template_id')) {
                $templateId = $webform->getData('email_result_completed_template_id');
            }
            //attach pdf version to email
            if (($webform->getCompletedPrintAttachToEmail())) {
                $attachPDF = true;
                $pdfTemplate = 'completed';
            }
        } else
            return false;

        $this->_transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->_storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($vars)
            ->setFrom($sender)
            ->addTo($email)
            ->setReplyTo($this->getReplyTo('customer'));

        if ($attachPDF) {
            if (@class_exists('mPDF')) {

                $mpdf = @new \mPDF('utf-8', 'A4');
                @$mpdf->WriteHTML($this->toPrintableHtml($pdfTemplate));

                $this->_transportBuilder->createAttachment(
                    @$mpdf->Output('', 'S'),
                    \Zend_Mime::TYPE_OCTETSTREAM,
                    \Zend_Mime::DISPOSITION_ATTACHMENT,
                    \Zend_Mime::ENCODING_BASE64,
                    $this->getPdfFilename()
                );
            }
        }
        $bcc_list = explode(',', $webform->getBccApprovalEmail());
        // trim bcc array
        array_walk($bcc_list, create_function('&$val', '$val = trim($val);'));
        $validateEmail = new \Zend_Validate_EmailAddress();

        if (is_array($bcc_list))
            foreach ($bcc_list as $bcc) {
                if ($validateEmail->isValid($bcc)) {
                    $this->_transportBuilder->getMessage()->addBcc($bcc);
                }
            }

        $this->_transportBuilder->getTransport()->sendMessage();
    }

    public function getStatusName()
    {
        $statuses = $this->getApprovalStatuses();
        foreach ($statuses as $status_id => $status_name) {
            if ($this->getApproved() == $status_id) return $status_name;
        }
    }

    public function getPdfFilename()
    {
        return \Magento\Framework\File\Uploader::getCorrectFileName($this->getWebform()->getName()).'-submitted-' . $this->_localeDate->formatDate($this->getCreatedTime(), \IntlDateFormatter::SHORT, true) . '.pdf';
    }

    public function toPrintableHtml($type = 'admin')
    {
        $webform = $this->_formFactory->create()
            ->setStoreId($this->getStoreId())
            ->load($this->getWebformId());

        $webformObject = new \Magento\Framework\DataObject();
        $webformObject->setData($webform->getData());

        $varResult = $this->getTemplateResultVar();

        $varResult->addData(array(
            'id' => $this->getId(),
            'subject' => $this->getEmailSubject(),
            'date' => $this->_localeDate->formatDate($this->getCreatedTime()),
            'html' => $this->toHtml('customer'),
        ));

        $store_group = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();
        $store_name = $this->_storeManager->getStore($this->getStoreId())->getName();

        $vars = Array(
            'webform_name' => $webform->getName(),
            'webform_result' => $this->toHtml(),
            'result' => $varResult,
            'customer_name' => $this->getCustomerName(),
            'customer_email' => $this->getCustomerEmail(),
            'ip' => $this->getIp(),
            'store_group' => $store_group,
            'store_name' => $store_name,
            'webform' => $webformObject,
        );

        $customer = $this->getCustomer();

        if ($customer) {
            $vars['customer'] = $customer;
            $billing_address = $customer->getDefaultBillingAddress();
            if ($billing_address) {
                $vars['billing_address'] = $billing_address;
            }
            $shipping_address = $customer->getDefaultShippingAddress();
            if ($shipping_address) {
                $vars['shipping_address'] = $shipping_address;
            }
        }

        $templateId = 'webforms_result_print';
        $template = $this->_templateFactory->create()
            ->setDesignConfig([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->getStoreId(),
            ])
            ->loadDefault($templateId);
        if ($type == 'admin' && $webform->getPrintTemplateId()) {
            $templateId = $webform->getPrintTemplateId();
            $template->load($templateId);
        }

        if ($type == 'customer' && $webform->getCustomerPrintTemplateId()) {
            $templateId = $webform->getCustomerPrintTemplateId();
            $template->load($templateId);
        }

        if ($type == 'approved' && $webform->getApprovedPrintTemplateId()) {
            $templateId = $webform->getApprovedPrintTemplateId();
            $template->load($templateId);
        }

        if ($type == 'completed' && $webform->getCompletedPrintTemplateId()) {
            $templateId = $webform->getCompletedPrintTemplateId();
            $template->load($templateId);
        }

        return $template->getProcessedTemplate($vars);
    }

    public function getFiles()
    {
        $files = $this->fileCollectionFactory->create()->addFilter('result_id', $this->getId());
        return $files;
    }

    public function getEmailSubject($recipient = 'admin')
    {
        $webform = $this->getWebform();
        $webform_name = $webform->getName();
        $store_name = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();

        //get default subject for admin
        $subject = __("Form [%1]", $webform_name);

        //get subject for customer
        if ($recipient == 'customer') {
            $subject = __("You have submitted [%1] form on %2 website", $webform_name, $store_name);
        }

        //iterate through fields and build subject
        $subject_array = array();
        $fields_to_fieldsets = $webform->getFieldsToFieldsets(true);
        $logic_rules = $webform->getLogic(true);
        $this->addFieldArray();
        foreach($fields_to_fieldsets as $fieldset) {
            foreach ($fieldset['fields'] as $field) {

                $target_field = array("id" => 'field_' . $field->getId(), 'logic_visibility' => $field->getData('logic_visibility'));
                $field_visibility = $webform->getLogicTargetVisibility($target_field, $logic_rules, $this->getData('field'));

                if ($field_visibility && $field->getEmailSubject()) {
                    foreach ($this->getData() as $key => $value) {
                        if ($key == 'field_' . $field->getId() && $value) {
                            $subject_array[] = $field->prepareResultValue($value);
                        }
                    }
                }
            }
        }
        if (count($subject_array) > 0) {
            $subject = implode(" / ", $subject_array);
        }
        return $subject;
    }

    public function getCustomerName()
    {
        $customer_name = array();
        $fields = $this->_fieldFactory->create()
            ->setStoreId($this->getStoreId())
            ->getCollection()
            ->addFilter('webform_id', $this->getWebformId());
        foreach ($fields as $field) {
            foreach ($this->getData() as $key => $value) {
                if (is_string($value)) $value = trim($value);
                if ($key == 'field_' . $field->getId() && $value) {
                    if (
                        $field->getCode() == 'name' ||
                        $field->getCode() == 'firstname' ||
                        $field->getCode() == 'lastname' ||
                        $field->getCode() == 'middlename'
                    ) $customer_name[] = $value;
                }
            }
        }

        if (count($customer_name) == 0)
            if ($this->getCustomerId()) {
                $customer = $this->_customerFactory->create()->load($this->getCustomerId());
                if ($customer->getId())
                    return $customer->getName();
            }

        if (count($customer_name) == 0) {
            // try to get $_POST[''] variable
            if ($this->_request->getParam('firstname'))
                $customer_name [] = $this->_request->getParam('firstname');

            if ($this->_request->getParam('lastname'))
                $customer_name [] = $this->_request->getParam('lastname');
        }

        if (count($customer_name) == 0)
            return __('Guest');

        return implode(' ', $customer_name);

    }

    public function getContactArray()
    {

        $fields = $this->_fieldFactory->create()
            ->setStoreId($this->getStoreId())
            ->getCollection()
            ->addFilter('webform_id', $this->getWebformId())
            ->addFilter('type', 'select/contact');

        foreach ($fields as $field) {
            foreach ($this->getData() as $key => $value) {
                if ($key == 'field_' . $field->getId() && $value) {
                    return $field->getContactArray($value);
                }
            }
        }

        return false;
    }

    public function getTemplateResultVar()
    {
        $result = new \Magento\Framework\DataObject(array(
            'id' => $this->getId(),
            'webform_id' => $this->getWebformId(),
        ));
        $fields = $this->_fieldFactory->create()
            ->setStoreId($this->getStoreId())
            ->getCollection()
            ->addFilter('webform_id', $this->getWebformId());
        foreach ($fields as $field) {
            foreach ($this->getData() as $key => $value) {
                if (is_string($value)) $value = trim($value);
                if ($key == 'field_' . $field->getId() && $value) {
                    switch ($field->getType()) {
                        case 'date':
                        case 'datetime':
                            $data_value = $field->formatDate($value);
                            break;
                        case 'image':
                            $data_value = '<a style="text-decoration:none" href="' . $this->getDownloadLink($field->getId(), $value) . '"><img src="' . $this->getThumbnail($field->getId(), $value, 200) . '"/></a>';
                            break;
                        case 'file':
                            $data_value = '<a href="' . $this->getDownloadLink($field->getId(), $value) . '">' . $value . '</a>';
                            break;
                        case 'stars':
                            $data_value = $value . ' / ' . $field->getStarsCount();
                            break;
                        case 'select/contact':
                            $contact = $field->getContactArray($value);
                            !empty($contact["name"]) ? $data_value = $contact["name"] : $data_value = $value;
                            break;
                        default:
                            $data_value = nl2br($value);
                            break;
                    }
                    $value = new \Magento\Framework\DataObject(array('html' => $data_value, 'value' => $this->getData('field_' . $field->getId())));
                    $this->_eventManager->dispatch('webforms_results_tohtml_value', array('field' => $field, 'value' => $value, 'result' => $this));
                    $data = new \Magento\Framework\DataObject(array(
                        'value' => $value->getHtml(),
                        'name' => $field->getName(),
                        'result_label' => $field->getResultLabel(),
                    ));
                    $result->setData($field->getId(), $data);
                    if ($field->getCode()) {
                        $result->setData($field->getCode(), $data);
                    }
                }
            }
        }
        return $result;
    }

    public function getReplyTo($recipient = 'admin')
    {
        $fields = $this->_fieldFactory->create()
            ->setStoreId($this->getStoreId())
            ->getCollection()
            ->addFilter('webform_id', $this->getWebformId())
            ->addFilter('type', 'email');

        $webform = $this->_formFactory->create()->setStoreId($this->getStoreId())->load($this->getWebformId());

        $reply_to = false;

        foreach ($this->getData() as $key => $value) {
            if ($key == 'field_' . $fields->getFirstItem()->getId()) {
                $reply_to = $value;
            }
        }
        if (!$reply_to) {
            if ($this->_customerSession->isLoggedIn()) {
                $reply_to = $this->_customerSession->getCustomer()->getEmail();
            } else {
                $reply_to = $this->_scopeConfig->getValue('trans_email/ident_general/email', $this->getScope(), $this->getStoreId());
            }
        }
        if ($recipient == 'customer') {
            if ($webform->getEmailReplyTo())
                $reply_to = $webform->getEmailReplyTo();
            elseif ($this->_scopeConfig->getValue('webforms/email/email_reply_to', $this->getScope(), $this->getStoreId()))
                $reply_to = $this->_scopeConfig->getValue('webforms/email/email_reply_to', $this->getScope(), $this->getStoreId());
            else
                $reply_to = $this->_scopeConfig->getValue('trans_email/ident_general/email', $this->getScope(), $this->getStoreId());
        }
        return $reply_to;
    }

    public function getCustomerEmail()
    {
        $fields = $this->_fieldFactory->create()
            ->setStoreId($this->getStoreId())
            ->getCollection()
            ->addFilter('webform_id', $this->getWebformId())
            ->addFilter('type', 'email');

        $customer_email = array();
        foreach ($this->getData() as $key => $value) {
            foreach ($fields as $field)
                if ($key == 'field_' . $field->getId()) {
                    if (strlen(trim($value)) > 0) $customer_email [] = $value;
                }
        }

        if (!count($customer_email)) {
            // try to get email by customer id
            if ($this->getCustomerId())
                $customer_email [] = $this->_customerFactory->create()->load($this->getCustomerId())->getEmail();
        }

        if (!count($customer_email)) {
            if ($this->_customerSession->isLoggedIn()) {
                $customer_email [] = $this->_customerSession->getCustomer()->getEmail();
            }
        }

        if (!count($customer_email)) {
            // try to get $_POST['email'] variable
            if ($this->_request->getParam('email'))
                $customer_email [] = $this->_request->getParam('email');
        }

        return $customer_email;
    }

    public function toHtml($recipient = 'admin', $options = array())
    {
        $webform = $this->_formFactory->create()
            ->setStoreId($this->getStoreId())
            ->load($this->getWebformId());

        $this->addFieldArray(true);

        if (!isset($options['header'])) {
            $options['header'] = $webform->getAddHeader();
        }
        if (!isset($options['skip_fields'])) {
            $options['skip_fields'] = array();
        }

        $html = "";
        $store_group = $this->_storeManager->getStore($this->getStoreId())->getFrontendName();
        $store_name = $this->_storeManager->getStore($this->getStoreId())->getName();
        if ($recipient == 'admin') {
            if ($store_group)
                $html .= __('Store group') . ": " . $store_group . "<br>";
            if ($store_name)
                $html .= __('Store name') . ": " . $store_name . "<br>";
            $html .= __('Customer') . ": " . $this->getCustomerName() . "<br>";
            $html .= __('IP') . ": " . $this->getIp() . "<br>";
        }
        $html .= __('Date') . ": " . $this->_localeDate->formatDate($this->getCreatedTime(), \IntlDateFormatter::SHORT, true) . "<br>";
        $html .= "<br>";

        $head_html = "";
        if ($options['header']) $head_html = $html;

        $html = "";

        $logic_rules = $webform->getLogic(true);

        $fields_to_fieldsets = $this->_formFactory->create()
            ->setStoreId($this->getStoreId())
            ->load($this->getWebformId())
            ->getFieldsToFieldsets(true);

        foreach ($fields_to_fieldsets as $fieldset_id => $fieldset) {

            $k = false;
            $field_html = "";

            $target_fieldset = array("id" => 'fieldset_' . $fieldset_id, 'logic_visibility' => $fieldset['logic_visibility']);
            $fieldset_visibility = $webform->getLogicTargetVisibility($target_fieldset, $logic_rules, $this->getData('field'));

            if ($fieldset_visibility) {
                foreach ($fieldset['fields'] as $field) {
                    $target_field = array("id" => 'field_' . $field->getId(), 'logic_visibility' => $field->getData('logic_visibility'));
                    $field_visibility = $webform->getLogicTargetVisibility($target_field, $logic_rules, $this->getData('field'));
                    $value = $this->getData('field_' . $field->getId());
                    if ($field->getType() == 'html')
                        $value = $field->getValue();
                    if (is_string($value)) $value = trim($value);
                    if ($value && $field_visibility) {
                        if (!in_array($field->getType(), $options['skip_fields']) && $field->getResultDisplay() != 'off') {
                            $field_name = $field->getName();
                            if (strlen(trim($field->getResultLabel())) > 0)
                                $field_name = $field->getResultLabel();
                            if ($field->getResultDisplay() != 'value') $field_html .= '<b>' . $field_name . '</b><br>';
                            switch ($field->getType()) {
                                case 'date':
                                case 'datetime':
                                    $value = $field->formatDate($value);
                                    break;
                                case 'stars':
                                    $value = $value . ' / ' . $field->getStarsCount();
                                    break;
                                case 'file':
                                    $value = '';
                                    $files = $this->fileCollectionFactory->create()->addFilter('result_id', $this->getId())->addFilter('field_id', $field->getId());
                                    foreach ($files as $file) {
                                        if ($recipient == 'admin') $value .= '<a href="' . $file->getDownloadLink() . '">' . $file->getName() . '</a>';
                                        else $value .= $file->getName();
                                        $value .= ' <small>[' . $file->getSizeText() . ']</small>';
                                    }
                                    break;
                                case 'image':
                                    $value = '';
                                    $files = $this->fileCollectionFactory->create()->addFilter('result_id', $this->getId())->addFilter('field_id', $field->getId());
                                    /** @var \VladimirPopov\WebForms\Model\File $file */
                                    foreach ($files as $file) {
                                        $img = '<img src="' . $file->getThumbnail($this->_scopeConfig->getValue('webforms/images/email_thumbnail_width'), $this->_scopeConfig->getValue('webforms/images/email_thumbnail_height')) . '"/>';
                                        $value .= $img;
                                        if ($recipient == 'admin') {
                                            $value .= '<br><a href="' . $file->getDownloadLink() . '">' . $file->getName() . '</a>';
                                            $value .= ' <small>[' . $file->getSizeText() . ']</small>';
                                        }
                                    }
                                    break;
                                case 'select/contact':
                                    $contact = $field->getContactArray($value);
                                    if (!empty($contact["name"])) $value = $contact["name"];
                                    break;
                                case 'html':
                                    $value = trim($field->getValue('html'));
                                    break;
                                case 'country':
                                    $country_name = $this->translatedLists->getCountryTranslation($value);
                                    if ($country_name) $value = $country_name;
                                    break;
                                case 'subscribe':
                                    if ($value) $value = __('Yes');
                                    else $value = __('No');
                                    break;
                                default :
                                    $value = nl2br(htmlspecialchars($value));
                                    break;
                            }
                            $k = true;
                            $value = new \Magento\Framework\DataObject(array('html' => $value, 'value' => $this->getData('field_' . $field->getId())));
                            $this->_eventManager->dispatch('webforms_results_tohtml_value', array('field' => $field, 'value' => $value, 'result' => $this));
                            $field_html .= $value->getHtml() . "<br><br>";
                        }
                    }

                }
            }
            if (!empty($fieldset['name']) && $k && $fieldset['result_display'] == 'on')
                $field_html = '<h2>' . $fieldset['name'] . '</h2>' . $field_html;
            $html .= $field_html;
        }
        return $head_html . $html;

    }

    public function toXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {

        $webform = $this->_formFactory->create()
            ->setStoreId($this->getStoreId())
            ->load($this->getWebformId());

        if ($webform->getCode())
            $this->setData('webform_code', $webform->getCode());

        foreach ($this->getData() as $key => $value) {
            if (strstr($key, 'field_')) {
                $field = $this->_fieldFactory->create()
                    ->setStoreId($this->getStoreId())
                    ->load(str_replace('field_', '', $key));
                if (!empty($field) && $field->getCode()) {
                    $this->setData($field->getCode(), $value);
                }
            }
        }
        return parent::toXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
    }

    /**
     * @return \VladimirPopov\WebForms\Model\Form
     */
    public function getWebform()
    {
        if (!$this->_webform) {
            /** @var \VladimirPopov\WebForms\Model\Form $webform */
            $webform = $this->_formFactory->create()->setStoreId($this->getStoreId())->load($this->getWebformId());
            $this->_webform = $webform;
        }
        return $this->_webform;
    }

    public function getValue($code = false)
    {
        if ($code === false) return false;
        foreach ($this->getWebform()->getFieldsToFieldsets(true) as $fieldset_id => $fieldset) {
            foreach ($fieldset["fields"] as $field) {
                if ($field->getCode() == $code) {
                    return $this->getData('field_' . $field->getId());
                }
            }
        }
    }

    public function resizeImages()
    {
        if ($this->_registry->registry('result_resize_image_' . $this->getId())) return $this;
        $this->_registry->register('result_resize_image_' . $this->getId(), true);
        foreach ($this->getWebform()->getFieldsToFieldsets(true) as $fieldset_id => $fieldset) {
            foreach ($fieldset["fields"] as $field) {
                $field_value = $field->getValue();
                $resize = empty($field_value['image_resize']) ? false : $field_value['image_resize'];
                $width = empty($field_value['image_resize_width']) ? false : $field_value['image_resize_width'];
                $height = empty($field_value['image_resize_height']) ? false : $field_value['image_resize_height'];

                if ($field->getType() == 'image' && $resize && ($width > 0 || $height > 0)) {
                $files = $this->fileCollectionFactory->create()->addFilter('result_id', $this->getId())->addFilter('field_id', $field->getId());

                /** @var \VladimirPopov\WebForms\Model\File $file */
                foreach ($files as $file) {
                    $imageUrl = $file->getFullPath();
                    $file_info = @getimagesize($imageUrl);
                    if ($file_info) {
                        // skip bmp files
                        if (!strstr($file_info["mime"], "bmp")) {
                            if (file_exists($imageUrl)) {
                                $file->setMemoryForImage();
                                $imageObj = new \Magento\Framework\Image($imageUrl);
                                $imageObj->keepAspectRatio(true);
                                $imageObj->keepTransparency(true);
                                if (!$width) $width = $imageObj->getOriginalWidth();
                                $imageObj->resize($width, $height);
                                $imageObj->save($imageUrl);
                                unset($imageObj);
                            }
                        }
                    }
                }
            }
            }
        }
        return $this;
    }

    public function getIp()
    {
        return long2ip($this->getCustomerIp());
    }

    public function getUploader()
    {
        $uploader = $this->uploaderFactory->create();
        $uploader->setResult($this);
        return $uploader;
    }
}
