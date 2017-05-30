<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */
namespace Amasty\Rules\Helper;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\SalesRule\Model\Validator
     */
    protected $_validator;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\SalesRule\Model\Rule
     */
    protected $_rule;

    /**
     * @var \Magento\Directory\Model\PriceCurrency
     */
    protected $_priceCurrency;

    public function __construct(
        \Magento\SalesRule\Model\Validator $_validator,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Directory\Model\PriceCurrency $priceCurrency
    ) {
        $this->_validator = $_validator;
        $this->_objectManager = $objectManager;
        $this->_priceCurrency = $priceCurrency;
    }

    public function setRule($rule)
    {
        $this->_rule = $rule;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     *
     * @return float
     */
    public function getItemPrice(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $price = $this->_validator->getItemPrice($item);
        switch ($this->getPriceSelector()) {
            case 1:
                $price -= $item->getDiscountAmount() / $item->getQty();
                break;
            case 2:
                $price = $this->_priceCurrency->convert($item->getProduct()->getPrice());
                break;
        }

        return $price;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     *
     * @return float
     */
    public function getItemBasePrice(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $price = $this->_validator->getItemBasePrice($item);
        switch ($this->getPriceSelector()) {
            case 1:
                $price -= $item->getBaseDiscountAmount() / $item->getQty();
                break;
            case 2:
                $price = $item->getProduct()->getPrice();
                break;
        }

        return $price;
    }

    /**
     * Return item original price
     *
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @return float
     */
    public function getItemOriginalPrice(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $price = $this->_validator->getItemOriginalPrice($item);
        switch ($this->getPriceSelector()) {
            case 1:
                $price -= $item->getDiscountAmount() / $item->getQty();
                break;
            case 2:
                $price = $item->getProduct()->getPrice();
                break;
        }

        return $price;
    }

    /**
     * Return item original price
     *
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @return float
     */
    public function getItemBaseOriginalPrice(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $price = $this->_validator->getItemBaseOriginalPrice($item);
        switch ($this->getPriceSelector()) {
            case 1:
                $price -= $item->getBaseDiscountAmount() / $item->getQty();
                break;
            case 2:
                $price = $this->_priceCurrency->convert($item->getProduct()->getPrice());
                break;
        }

        return $price;
    }

    protected function getPriceSelector()
    {
        $amrulesRule = $this->_objectManager->get('Amasty\Rules\Model\Rule');
        $amrulesRule = $amrulesRule->loadBySalesrule($this->_rule);

        return $amrulesRule->getPriceselector();
    }
}
