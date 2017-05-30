<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Email;

use Magento\Framework\View\Element\Template;

class Result extends \Magento\Framework\View\Element\Template
{
    protected $_result;

    protected $_resultFactory;

    protected $_template = 'VladimirPopov_WebForms::webforms/email/result.phtml';

    public function __construct(
        Template\Context $context,
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        array $data = [])
    {
        $this->_resultFactory = $resultFactory;
        parent::__construct($context, $data);
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function _toHtml()
    {
        if($this->getData('result')) {
            $resultId = $this->getData('result')->getId();
            $this->_result = $this->_resultFactory->create()->load($resultId);
        }
        return parent::_toHtml();
    }
}