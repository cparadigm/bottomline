<?php

/**
 * GiftPromo Validator Model
 *
 * Allows dispatching before and after events for each controller action
 *
 * @category   Mage
 * @package    Mage_SalesRule
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class ProxiBlue_GiftPromo_Model_Promo_Validator
    extends Mage_Core_Model_Abstract
{

    /**
     * Rule source collection
     *
     * @var Mage_SalesRule_Model_Mysql4_Rule_Collection
     */
    protected $_rules;
    protected $_roundingDeltas = array();
    protected $_baseRoundingDeltas = array();

    /**
     * Defines if method Mage_SalesRule_Model_Validator::reset() wasn't called
     * Used for clearing applied rule ids in Quote and in Address
     *
     * @var bool
     */
    protected $_isFirstTimeResetRun = true;

    /**
     * Information about item totals for rules.
     * @var array
     */
    protected $_rulesItemTotals = array();

    /**
     * Store information about addresses which cart fixed rule applied for
     *
     * @var array
     */
    protected $_cartFixedRuleUsedForAddress = array();

    /**
     * Init validator
     * Init process load collection of rules for specific website,
     * customer group and coupon code
     *
     * @param   int $websiteId
     * @param   int $customerGroupId
     * @param   string $couponCode
     * @return  Mage_SalesRule_Model_Validator
     */
    public function init($websiteId, $customerGroupId, $couponCode)
    {
        $this->setWebsiteId($websiteId)
            ->setCustomerGroupId($customerGroupId)
            ->setCouponCode($couponCode);
        return $this;
    }

    /**
     * Get rules collection for current object state
     *
     * @return Mage_SalesRule_Model_Mysql4_Rule_Collection
     */
    protected function _getRules()
    {
        $key = $this->getWebsiteId() . '_' . $this->getCustomerGroupId() . '_' . $this->getCouponCode();
        return $this->_rules[$key];
    }

    /**
     * Get address object which can be used for discount calculation
     *
     * @param   Mage_Sales_Model_Quote_Item_Abstract $item
     * @return  Mage_Sales_Model_Quote_Address
     */
    protected function _getAddress(Mage_Sales_Model_Quote_Item_Abstract $item)
    {
        if ($item instanceof Mage_Sales_Model_Quote_Address_Item) {
            $address = $item->getAddress();
        } elseif ($item->getQuote()->getItemVirtualQty() > 0) {
            $address = $item->getQuote()->getBillingAddress();
        } else {
            $address = $item->getQuote()->getShippingAddress();
        }
        return $address;
    }

    /**
     * Check if rule can be applied for specific address/quote/customer
     *
     * @param   Mage_SalesRule_Model_Rule $rule
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  bool
     */
    public function canProcessRule($rule, $address)
    {
        /**
         * check per coupon usage limit
         */
        if ($rule->getCouponType() != ProxiBlue_GiftPromo_Model_Promo_Rule::COUPON_TYPE_NO_COUPON) {
            $quote = $address->getQuote();
            if (!is_object($quote) || !$quote instanceof Mage_Sales_Model_Quote) {
                return false;
            }
            $couponCode = $address->getQuote()->getCouponCode();
            if (strlen($couponCode)) {
                $coupon = Mage::getModel('giftpromo/promo_coupon');
                $coupon->load($couponCode,
                    'code');
                if ($coupon->getId() && $coupon->getRuleId() == $rule->getId()) {
                    // check entire usage limit
                    if ($coupon->getUsageLimit() && $coupon->getTimesUsed() >= $coupon->getUsageLimit()) {
                        $rule->setIsValid(false);
                        return false;
                    }
                    // check per customer usage limit
                    $customerId = $address->getQuote()->getCustomerId();
                    if ($customerId && $coupon->getUsagePerCustomer()) {
                        $couponUsage = new Varien_Object();
                        Mage::getResourceModel('giftpromo/promo_coupon_usage')->loadByCustomerCoupon(
                            $couponUsage,
                            $customerId,
                            $coupon->getId());
                        if ($couponUsage->getCouponId() &&
                            $couponUsage->getTimesUsed() >= $coupon->getUsagePerCustomer()
                        ) {
                            $rule->setIsValid(false);
                            return false;
                        }
                    }
                    $address->getQuote()->setCouponCode($couponCode);
                    $address->setCouponCode($couponCode);
                } else {
                    $rule->setIsValid(false);
                    return false;
                }
            } else {
                // requires a coupon, but none entered, thus fails
                $rule->setIsValid(false);
                // reset any current seleted gifts for this rule
                $currentSelectedGifts = $this->_getHelper()->getCurrentSelectedGifts();
                $currentSelectedGiftsKeys = array_keys($currentSelectedGifts);
                foreach ($currentSelectedGiftsKeys as $currentSelectedGiftsKey) {
                    $keyParts = explode("_",
                        $currentSelectedGiftsKey);
                    $rulePart = array_shift($keyParts);
                    if ($rule->getId() == $rulePart) {
                        unset($currentSelectedGifts[$currentSelectedGiftsKey]);
                        $this->_getHelper()->setCurrentSelectedGifts($currentSelectedGifts);
                    }
                }
                return false;
            }
        }

        /**
         * check per rule usage limit
         */
//        $ruleId = $rule->getId();
//        if ($ruleId && $rule->getUsesPerCustomer()) {
//            $customerId     = $address->getQuote()->getCustomerId();
//            $ruleCustomer   = Mage::getModel('giftpromo/promo_rule_customer');
//            $ruleCustomer->loadByCustomerRule($customerId, $ruleId);
//            if ($ruleCustomer->getId()) {
//                if ($ruleCustomer->getTimesUsed() >= $rule->getUsesPerCustomer()) {
//                    $rule->setIsValidForAddress($address, false);
//                    return false;
//                }
//            }
//        }
        /**
         * passed all validations, remember to be valid
         */
        $rule->setIsValid(true);
        return true;
    }

    /**
     * Reset quote and address applied rules
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Mage_SalesRule_Model_Validator
     */
    public function reset(Mage_Sales_Model_Quote_Address $address)
    {
        if ($this->_isFirstTimeResetRun) {
            $address->setAppliedRuleIds('');
            $address->getQuote()->setAppliedRuleIds('');
            $this->_isFirstTimeResetRun = false;
        }

        return $this;
    }

    /**
     * Apply discounts to shipping amount
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_SalesRule_Model_Validator
     */
    public function processGiftRules($address)
    {
        Mage::getSingleton('checkout/session')->setSkipTriggerCollect(true); // prevents endless loop if session get mucked up.
        $rules = Mage::getModel('giftpromo/promo_rule')->getCollection();
        foreach ($rules as $rule) {
            $ruleObject = Mage::getModel('giftpromo/promo_rule')->load($rule->getId());
            $address->getQuote()->setGiftTriggerItem(false);
            if ($this->canProcessRule($ruleObject,
                    $address)) {
                // has this rule already been processed?
                //$appliedQuoteGiftRuleIds = $this->_getHelper()->getAppliedRuleIds($address->getQuote()->getAppliedGiftRuleIds());
                $validTest = $ruleObject->validate($address->getQuote());
                if (!$ruleObject->getAllowGiftSelection() && $validTest) {
                    $giftProducts = $ruleObject->getItemsArray();
                    $giftItemsInCart = $ruleObject->getGiftCartItems($address->getQuote());
                    $triggerItem = $address->getQuote()->getGiftTriggerItem();
                    if (is_object($triggerItem)) {
                        foreach ($giftItemsInCart as $giftKey => $currentCartGiftItem) {
                            $currentGiftAddedBy = $this->_getHelper()->getParentQuoteItemOfGift($currentCartGiftItem,
                                true);
                            if ($triggerItem->getId() != $currentGiftAddedBy) {
                                unset($giftItemsInCart[$giftKey]);
                            }
                        }
                    }
                    $giftDiff = array_diff_key($giftProducts,
                        $giftItemsInCart);
                    if (count($giftDiff) != 0) {
                        Mage::dispatchEvent('gift_product_gifts_changed_rule',
                            array(
                            'quote' => $address->getQuote(),
                            'gift_diff' => $giftDiff,
                            'in_cart' => $giftItemsInCart,
                            'gift_products' => $giftProducts
                        ));
                        $params = array();

                        if (is_object($triggerItem)) {
                            if (!$triggerItem->getId()) {
                                try {
                                    try {
                                        $triggerItem->save();
                                    } catch (Exception $e) {
                                        // if this is the first item, or session timeout quote object may not saved
                                        $address->getQuote()->save();
                                        $triggerItem->save();
                                    }
                                } catch (Exception $e) {
                                    mage::logException($e);
                                }
                            }
                            $params = array(
                                'parent_quote_item_id' => $triggerItem->getId(),
                                'parent_product_id' => $triggerItem->getProductId());
                            $triggerItem->setAppliedGiftRuleIds(json_encode(array(
                                $triggerItem->getId() => $ruleObject->getId())));
                            try {
                                $triggerItem->save();
                            } catch (Exception $e) {
                                // if this is the first item, or session timeout quote object may not saved
                                $address->getQuote()->save();
                                $triggerItem->save();
                            }
                        }
                        $this->_getHelper()->addGiftItems($giftDiff,
                            $ruleObject,
                            $params);
                    }
//                    $ruleDiff = array_diff_key($giftItemsInCart,
//                        $giftProducts);
//                    if (count($ruleDiff) > 0) {
//                        foreach ($ruleDiff as $ItemsToRemove) {
//                            $address->getQuote()->removeItem($ItemsToRemove->getId());
//                        }
//                    }
                    // if valid test if we need to stop further rules from processing
                    if ($rule->getStopRulesProcessing()) {
                        break;
                    }
                } else {
                    if (!$ruleObject->validate($address->getQuote())) {
                        // clear any stored session data for selected gift
                        $currentSelectedGifts = $this->_getHelper()->getCurrentSelectedGifts();
                        $currentSelectedGiftsKeys = array_keys($currentSelectedGifts);
                        foreach ($currentSelectedGiftsKeys as $currentSelectedGiftsKey) {
                            $keyParts = explode("_",
                                $currentSelectedGiftsKey);
                            $rulePart = array_shift($keyParts);
                            if ($ruleObject->getId() == $rulePart) {
                                unset($currentSelectedGifts[$currentSelectedGiftsKey]);
                                $this->_getHelper()->setCurrentSelectedGifts($currentSelectedGifts);
                            }
                        }
                        // clear out any items in cart for this rule
                        $ruleBasedCartItems = $this->_getHelper()->getRuleBasedCartItems(true);
                        foreach ($ruleBasedCartItems as $ruleCartItem) {
                            foreach ($ruleCartItem as $cartItemKey => $cartItem) {
                                $buyRequest = $this->_getHelper()->isAddedAsGift($cartItem);
                                $ruleId = $buyRequest->getAddedByRule();
                                if ($ruleId == $ruleObject->getId()) {
                                    $address->getQuote()->removeItem($cartItem->getId());
                                }
                            }
                        }
                    }
                    $giftItemsInCart = $ruleObject->getGiftCartItems($address->getQuote());
                    $removed = false;
                    $currentRuleSelectedGift = false;
                    if (count($giftItemsInCart) != 0) {
                        foreach ($giftItemsInCart as $cartItem) {
                            $infoBuyRequest = $cartItem->getOptionByCode('info_buyRequest');
                            $buyRequest = new Varien_Object(unserialize($infoBuyRequest->getValue()));
                            $currentSelectedGifts = $this->_getHelper()->getCurrentSelectedGifts();
                            if (array_key_exists($buyRequest->getSelectedGiftItemKey(),
                                    $currentSelectedGifts)) {
                                $currentRuleSelectedGift = $currentSelectedGifts[$buyRequest->getSelectedGiftItemKey()];
                            }
                            if ($currentRuleSelectedGift != false && $currentRuleSelectedGift != $cartItem->getProductId()) {
                                $removed = true;
                                $address->getQuote()->removeItem($cartItem->getId());
                            }
                        }
                        if ($removed) {
                            $address->getQuote()->save();
                        }
                    }
                }
            } else {
                // is there an item in the cart with this rule that does not validate?
                // if so remove the item(s)
                if (mage::registry('skip_extra_check_one') != true) {
                    mage::register('skip_extra_check_one',
                        true,
                        true);
                    $ruleBasedCartItems = $this->_getHelper()->getRuleBasedCartItems(true);
                    foreach ($ruleBasedCartItems as $ruleCartItem) {
                        foreach ($ruleCartItem as $cartItemKey => $cartItem) {
                            $buyRequest = $this->_getHelper()->isAddedAsGift($cartItem);
                            $ruleId = $buyRequest->getAddedByRule();
                            if ($ruleId == $rule->getId()) {
                                $address->getQuote()->removeItem($cartItem->getId());
                            }
                        }
                    }
                }
            }
        }
        // and test all rule based cart items for inactive rules
        if (mage::registry('skip_extra_check_two') != true) {
            mage::register('skip_extra_check_two',
                true,
                true);
            $ruleBasedCartItems = $this->_getHelper()->getRuleBasedCartItems(true);
            foreach ($ruleBasedCartItems as $ruleCartItem) {
                foreach ($ruleCartItem as $cartItemKey => $cartItem) {
                    $buyRequest = $this->_getHelper()->isAddedAsGift($cartItem);
                    $ruleId = $buyRequest->getAddedByRule();
                    $ruleObject = Mage::getModel('giftpromo/promo_rule')->load($ruleId);
                    if ($ruleObject->getIsActive() == 0) {
                        $address->getQuote()->removeItem($cartItem->getId());
                    }
                    if (!$buyRequest->getParentProductId() && !$ruleObject->validate($address->getQuote())) {
                        $address->getQuote()->removeItem($cartItem->getId());
                        $appliedGiftRuleIds = $this->_getHelper()
                            ->getAppliedRuleIds($address->getQuote()->getAppliedGiftRuleIds());
                        unset($appliedGiftRuleIds[$cartItem->getProductId()]);
                        $appliedGiftRuleIds = json_encode($appliedGiftRuleIds);
                        $address->getQuote()->setAppliedGiftRuleIds($appliedGiftRuleIds);
                    }
                }
            }
        }


        $quoteGiftItems = $this->_getHelper()->getRuleBasedCartItems();
        Mage::helper('giftpromo')->calculateQtyrate($quoteGiftItems,
            $address->getQuote());

        return $this;
    }

    /**
     * Get the helper class and cache teh object
     * @return object
     */
    private function _getHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::Helper('giftpromo');
        }
        return $this->_helper;
    }

}
