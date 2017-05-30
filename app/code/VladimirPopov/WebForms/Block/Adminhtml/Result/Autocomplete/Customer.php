<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Autocomplete;

class Customer extends \Magento\Backend\Block\Template
{
    protected $_template = 'webforms/result/element/customer.phtml';

    protected $_customerFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    )
    {
        $this->_customerFactory = $customerFactory;
        parent::__construct($context, $data);
    }

    public function getCustomer($customerId = false)
    {
        $customer = $this->_customerFactory->create();
        if ($customerId) {
            $customer->load($customerId);
        }
        return $customer;
    }

    public function getAutocompleteUrl(){
        return $this->getUrl('webforms/result/customersJson');
    }
}