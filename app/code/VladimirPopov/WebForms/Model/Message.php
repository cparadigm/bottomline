<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Message extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    /**
     * Message cache tag
     */
    const CACHE_TAG = 'webforms_message';

    /**
     * @var string
     */
    protected $_cacheTag = 'webforms_message';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'webforms_message';

    protected $_resultFactory;

    protected $_formFactory;

    protected $_transportBuilder;

    protected $_storeManager;

    protected $_localeDate;

    protected $_scopeConfig;

    public function __construct(
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_resultFactory = $resultFactory;
        $this->_formFactory = $formFactory;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_localeDate = $localeDate;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\Message');
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

    public function sendEmail()
    {
        $result = $this->_resultFactory->create()
            ->load($this->getResultId());

        $email = $result->getCustomerEmail();

        if (!$email) return false;

        $name = $result->getCustomerName();

        $webform = $this->_formFactory->create()
            ->setStoreId($result->getStoreId())
            ->load($result->getWebformId());

        $sender = Array(
            'name' => $this->_storeManager->getStore($this->getStoreId())->getFrontendName(),
            'email' => $result->getReplyTo('customer'),
        );

        if(strlen(trim($webform->getEmailCustomerSenderName()))>0)
            $sender['name'] = $webform->getEmailCustomerSenderName();
        
        if ($this->_storeManager->getStore($this->getStoreId())->getConfig('webforms/email/email_from')) {
            $sender['email'] = $this->_storeManager->getStore($this->getStoreId())->getConfig('webforms/email/email_from');
        }

        $vars = $this->getTemplateVars();

        $storeId = $result->getStoreId();

        $templateId = 'webforms_reply';

        if ($webform->getEmailReplyTemplateId()) {
            $templateId = $webform->getEmailReplyTemplateId();
        }

        $this->_transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($vars)
            ->setFrom($sender)
            ->addTo($email)
            ->setReplyTo($result->getReplyTo('customer'))
            ->getTransport()
            ->sendMessage();

        return true;
    }

    public function getTemplateVars()
    {
        $result = $this->_resultFactory->create()
            ->load($this->getResultId());
        $name = $result->getCustomerName();

        $webform = $result->getWebform();
        $subject = $result->getEmailSubject();
        $store_group = $this->_storeManager->getStore($result->getStoreId())->getFrontendName();
        $store_name = $this->_storeManager->getStore($result->getStoreId())->getName();

        $varCustomer = new \Magento\Framework\DataObject(array(
            'name' => $name
        ));

        $varResult = $result->getTemplateResultVar();

        $varResult->addData(array(
            'id' => $result->getId(),
            'subject' => $result->getEmailSubject(),
            'date' => $this->_localeDate->formatDate($result->getCreatedTime()),
            'html' => $result->toHtml('customer'),
        ));

        $varReply = new \Magento\Framework\DataObject(array(
            'date' => $this->_localeDate->formatDate($this->getCreatedTime()),
            'message' => $this->getMessage(),
            'author' => $this->getAuthor()
        ));

        $vars = Array(
            'webform_subject' => $subject,
            'webform_name' => $webform->getName(),
            'customer_name' => $result->getCustomerName(),
            'customer_email' => $result->getCustomerEmail(),
            'ip' => $result->getIp(),
            'store_group' => $store_group,
            'store_name' => $store_name,
            'customer' => $varCustomer,
            'result' => $varResult,
            'reply' => $varReply,
            'webform' => $webform
        );

        $customer = $result->getCustomer();

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

        return $vars;
    }
}
