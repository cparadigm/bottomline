<?php


class ProxiBlue_GiftPromo_Model_Promo_Gift extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /**
     * Discount calculation object
     *
     * @var Mage_SalesRule_Model_Validator
     */
    //protected $_validator;

    /**
     * Internal holder for helper class
     *
     * @var object
     */
    private $_helper;

    public function __construct()
    {
        $this->setCode('giftpromo');
        $this->_validator = Mage::getSingleton('giftpromo/promo_validator');
    }

    /**
     * Collect information about free shipping for all address items
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_SalesRule_Model_Quote_Freeshipping
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $quote = $address->getQuote();
        $store = Mage::app()->getStore($quote->getStoreId());
        if (!count($quote->getAllVisibleItems())) {
            return $this;
        }
        $this->_validator->init($store->getWebsiteId(), $quote->getCustomerGroupId(), $quote->getCouponCode());
        $this->_validator->processGiftRules($address, $store->getWebsiteId());
        return $this;
    }

   /**
    * Add information about free shipping for all address items to address object
    * By default we not present such information
    *
    * @param   Mage_Sales_Model_Quote_Address $address
    * @return  Mage_SalesRule_Model_Quote_Freeshipping
    */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        return $this;
    }

    /**
     * Get the helper class and cache teh object
     * @return object
     */
    private function _getHelper() {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::Helper('giftpromo');
        }
        return $this->_helper;
    }

}
