<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Field;

class AbstractField extends \Magento\Framework\View\Element\Template
{
    /** @var  \VladimirPopov\WebForms\Model\Field */
    protected $_field;

    /** @var \Magento\Framework\Registry  */
    protected $_registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    public function registry($key = ''){
        return $this->_registry->registry($key);
    }

    public function setField(\VladimirPopov\WebForms\Model\Field $field){
        $this->_field = $field;
    }

    public function getField(){
        return $this->_field;
    }
}