<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Plugin;

use Magento\Store\Model\ScopeInterface;

class ContactForm
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
    }

    public function beforeToHtml(\Magento\Contact\Block\ContactForm $contactForm)
    {
        if ($this->_scopeConfig->getValue('webforms/contacts/enable', ScopeInterface::SCOPE_STORE)) {

            $contactForm->setTemplate('VladimirPopov_WebForms::webforms/contact/form.phtml');

            $block = $contactForm->getLayout()->createBlock('VladimirPopov\WebForms\Block\Form', 'webforms.contact.form', [
                'data' => [
                    'template' => 'VladimirPopov_WebForms::webforms/form/default.phtml',
                    'webform_id' => $this->_scopeConfig->getValue('webforms/contacts/webform', ScopeInterface::SCOPE_STORE)
                ]
            ]);
            $contactForm->append($block);
        }
    }
}