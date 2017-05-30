<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer;

class Customer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_customerFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    )
    {
        $this->_customerFactory = $customerFactory;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        if ($value) {
            $customer = $this->_customerFactory->create()->load($value);
            if ($customer->getId())
                $output = "<a href='" . $this->getCustomerUrl($row) . "' target='_blank'>" . $customer->getName() . "</a>";
            else
                $output = __('Guest');
        } else {
            $output = __('Guest');
        }
        return $output;
    }

    public function getCustomerUrl(\Magento\Framework\DataObject $row)
    {

        return $this->getUrl('customer/index/edit', array('id' => $row->getCustomerId(), '_current' => false));
    }
}