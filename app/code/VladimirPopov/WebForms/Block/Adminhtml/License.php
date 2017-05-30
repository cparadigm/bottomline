<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml;

use Magento\Store\Model\ScopeInterface;

class License extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    protected $webformsHelper;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \VladimirPopov\WebForms\Helper\Data $webformsHelper,
        array $data = []
    )
    {
        $this->webformsHelper = $webformsHelper;
        parent::__construct($context, $authSession, $jsHelper, $data);
    }


    protected function _getHeaderHtml($element)
    {
        $html = parent::_getHeaderHtml($element);

        if ($this->webformsHelper->isLocal())
            return $html . '<div class="messages"><div class="message message-success success"><div data-ui-id="messages-message-success">' . __('Development environment detected. Serial number is not required.') . '</div></div></div>';

        if ($this->webformsHelper->isProduction()) {
            $html .= '<div class="messages"><div class="message message-success success"><div data-ui-id="messages-message-success">' . __('License is active.') . '</div></div></div>';
        } else if (!$this->_scopeConfig->getValue('webforms/license/serial', ScopeInterface::SCOPE_STORE)) {
            $html .= '<div class="messages"><div class="message message-warning warning"><div data-ui-id="messages-message-warning">' . __('Please, enter serial number.') . '</div></div></div>';
        } else {
            $html .= '<div class="messages"><div class="message message-warning warning"><div data-ui-id="messages-message-warning">' . __('Incorrect serial number.') . '</div></div></div>';
        }

        return $html;
    }
}