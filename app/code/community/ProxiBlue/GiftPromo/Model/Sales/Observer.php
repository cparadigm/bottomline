<?php

/**
 * Events observers to deal with frontent sales adjustments
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Model_Sales_Observer
    extends ProxiBlue_GiftPromo_Model_Observer
{

    /**
     * Event to update sales collection.
     *
     * @param Varien_Event_Observer $observer
     * @return ProxiBlue_GiftPromo_Model_Checkout_Observer
     */
    public function sales_order_item_collection_load_before(Varien_Event_Observer $observer)
    {
        try {
            if (Mage::getStoreConfig('giftpromo/orders/last_ordered_enabled')) {
                $event = $observer->getEvent();
                $orderItemCollection = $event->getOrderItemCollection();
                $select = $orderItemCollection->getSelect();
                $order = $select->getPart('order');
                if (is_array($order) && count($order) > 0) {
                    foreach ($order as $key => $expression) {
                        // TODO: find a better way !
                        // if there is an order directive of RAND(),
                        // then this is a call for the sidebar
                        if ($expression == 'RAND()') {
                            //adjust the where to skip gift based products
                            $select->where("product_type NOT LIKE 'gift-%'");
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
        return $this;
    }

    /**
     * Event to adjust gift in cart to a gift item type
     *
     * @param Varien_Event_Observer $observer
     * @return ProxiBlue_GiftPromo_Model_Checkout_Observer
     */
    public function sales_quote_item_set_product(Varien_Event_Observer $observer)
    {
        try {
            $event = $observer->getEvent();
            $quoteItem = $event->getQuoteItem();
            if ($buyRequest = $this->_getHelper()->isAddedAsGift($quoteItem)) {
                $quoteItem->setProductType($this->_getHelper()->getGiftProductType($quoteItem->getProductType()));
                $quoteItem->getProduct()->setTypeId($this->_getHelper()->getGiftProductType($quoteItem->getProduct()->getTypeId()));
                if ($this->_getHelper()->wasAddedByRule($quoteItem)) {
                    if ($buyRequest instanceof Varien_Object) {
                        $appliedGiftRuleIds = $this->_getHelper()->getAppliedRuleIds($quoteItem->getAppliedGiftRuleIds());
                        $appliedGiftRuleIds[$quoteItem->getProductId()] = $buyRequest->getAddedByRule();
                        $quoteItem->setAppliedGiftRuleIds(json_encode($appliedGiftRuleIds));
                        $appliedQuoteGiftRuleIds = $this->_getHelper()->getAppliedRuleIds($quoteItem->getQuote()->getAppliedGiftRuleIds());
                        $appliedQuoteGiftRuleIds[$quoteItem->getProductId()] = $buyRequest->getAddedByRule();
                        $quoteItem->getQuote()->setAppliedGiftRuleIds(json_encode($appliedQuoteGiftRuleIds));
                    }
                }
                return $this;
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
        // do not return anything, else it mucks up things later.
    }

    /**
     * Event to remove any gift associated to a product, from the cart, when the parent is removed
     *
     * @param Varien_Event_Observer $observer
     * @return ProxiBlue_GiftPromo_Model_Checkout_Observer
     */
    public function sales_quote_remove_item(Varien_Event_Observer $observer)
    {
        try {
            $quoteItem = $observer->getQuoteItem();
            if ($buyRequest = $this->_getHelper()->isAddedAsGift($quoteItem)) {
                if ($this->_getHelper()->wasAddedByRule($quoteItem)) {
                    if ($buyRequest instanceof Varien_Object) {
                        $appliedQuoteGiftRuleIds = $this->_getHelper()->getAppliedRuleIds($quoteItem->getQuote()->getAppliedGiftRuleIds());
                        if (array_key_exists($quoteItem->getId(),
                                $appliedQuoteGiftRuleIds)) {
                            unset($appliedQuoteGiftRuleIds[$quoteItem->getId()]);
                            $quoteItem->getQuote()->setAppliedGiftRuleIds(json_encode($appliedQuoteGiftRuleIds));
                        }
                    }
                }
                $quoteItem->getQuote()->save();
                $cart = Mage::getSingleton('checkout/cart');
                $cart->setQuote($quoteItem->getQuote());
                return $this;
            }
            $quote = $quoteItem->getQuote();
            foreach ($quote->setData('trigger_recollect',
                0)->getAllItems() as $item) {
                if ($this->_getHelper()->getParentQuoteItemOfGift($item,
                        true) == $quoteItem->getId()) {
                    $quote->removeItem($item->getId());
                    // clear session parked and selected data
                    $this->_getHelper()->resetParkedGiftsForParent($quoteItem->getId());
                    $this->_getHelper()->resetCurrentSelectedGiftsForParent($quoteItem->getId());
                    //clear the AppliedGiftRuleId for this product.
                    $appliedGiftRuleIds = $appliedQuoteGiftRuleIds = $this->_getHelper()
                        ->getAppliedRuleIds($quote->getAppliedGiftRuleIds());
                    unset($appliedGiftRuleIds[$item->getProductId()]);
                    $appliedGiftRuleIds = json_encode($appliedGiftRuleIds);
                    $quote->setAppliedGiftRuleIds($appliedGiftRuleIds);
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
        $this->_getHelper()->resetParkedGiftsForParent($quoteItem->getId());
        $this->_getHelper()->resetCurrentSelectedGiftsForParent($quoteItem->getId());
        return $this;
    }

    /**
     * Append gift product additional data to order item options
     *
     * @param Varien_Event_Observer $observer
     * @return Enterprise_GiftCard_Model_Observer
     */
    public function sales_convert_quote_item_to_order_item(Varien_Event_Observer $observer)
    {
        try {
            $orderItem = $observer->getEvent()->getOrderItem();
            $item = $observer->getEvent()->getItem();
            if ($this->_helper->testGiftTypeCode($orderItem->getProductType()) && !$item->getParentItem()) {
                $quoteItem = $observer->getEvent()->getItem();
                try {
                    $options = unserialize($orderItem->getData('product_options'));
                } catch (Exception $e) {
                    $options = array();
                }
                $allOptions = $quoteItem->getOptions();
                foreach ($allOptions as $optionData) {
                    $unserialised = @unserialize($optionData->getValue());
                    if ($unserialised == false) {
                        $options = array_merge($options,
                            array($optionData->getCode() => $optionData->getValue()));
                    } else {
                        $options = array_merge($options,
                            array($optionData->getCode() => $unserialised));
                    }
                }
                $orderItem->setProductOptions($options);
                Mage::getSingleton('checkout/session')->unsCurrentSelectedGifts();
            } else if ($item->getParentItem()) {
                $quoteItem = $observer->getEvent()->getItem();
                $orderItem->setParentItemId($item->getParentItem()->getId());
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
        return $this;
    }

    /**
     * Event to update the composite item parentItemId, which is not recorded due to the same item order in order object
     *
     * @param Varien_Event_Observer $observer
     * @return ProxiBlue_GiftPromo_Model_Checkout_Observer
     */
    public function sales_model_service_quote_submit_after(Varien_Event_Observer $observer)
    {
        try {
            $order = $observer->getOrder();
            foreach ($order->setData('trigger_recollect',
                0)->getAllItems() as $item) {
                if ($item->getParentItem() && $this->_getHelper()->testGiftTypeCode($item->getProductType())) {
                    $item->setParentItemId($item->getParentItem()->getId());
                    $item->save();
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
        return $this;
    }

    /**
     *
     *
     * @param Varien_Event_Observer $observer
     * @return Enterprise_GiftCard_Model_Observer
     */
    public function sales_quote_save_after(Varien_Event_Observer $observer)
    {
        try {
            if ($observer->getQuote()->getCheckoutMethod()) {
                // re-evaluate gifts if there is a selected checkout method
                // but only if we are in checkout
                $request = Mage::app()->getRequest();
                if ($request) {
                    //$module = $request->getModuleName();
                    $controller = $request->getControllerName();
                    //$action = $request->getActionName();
                    if ($controller != 'cart' && ($action == 'saveMethod' || $action == "loginPost")) {
                        $this->_getHelper()->checkForGiftChanges($observer->getQuote());
                    }
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
        return $this;
    }

    /**
     * Adjust rules and coupons after order was placed.
     *
     * @param type $observer
     * @return \ProxiBlue_GiftPromo_Model_Sales_Observer
     */
    public function sales_order_place_after($observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!$order) {
            return $this;
        }

        // lookup rule ids
        $ruleIds = $this->_getHelper()->getAppliedRuleIds($order->getAppliedGiftRuleIds());

        $ruleIds = array_unique($ruleIds);

        $ruleCustomer = null;
        $customerId = $order->getCustomerId();

        // use each rule (and apply to customer, if applicable)
        foreach ($ruleIds as $ruleId) {
            if (!$ruleId) {
                continue;
            }
            $rule = Mage::getModel('giftpromo/promo_rule');
            $rule->load($ruleId);
            if ($rule->getId()) {
                $rule->setTimesUsed($rule->getTimesUsed() + 1);
                $rule->save();

                if ($customerId) {
                    $ruleCustomer = Mage::getModel('giftpromo/promo_rule_customer');
                    $ruleCustomer->loadByCustomerRule($customerId,
                        $ruleId);

                    if ($ruleCustomer->getId()) {
                        $ruleCustomer->setTimesUsed($ruleCustomer->getTimesUsed() + 1);
                    } else {
                        $ruleCustomer
                            ->setCustomerId($customerId)
                            ->setRuleId($ruleId)
                            ->setTimesUsed(1);
                    }
                    $ruleCustomer->save();
                }
            }
        }

        $coupon = Mage::getModel('giftpromo/promo_coupon');
        $coupon->load($order->getCouponCode(),
            'code');
        if ($coupon->getId()) {
            $coupon->setTimesUsed($coupon->getTimesUsed() + 1);
            $coupon->save();
            if ($customerId) {
                $couponUsage = Mage::getResourceModel('giftpromo/promo_coupon_usage');
                $couponUsage->updateCustomerCouponTimesUsed($customerId,
                    $coupon->getId());
            }
        }

        $items = $order->getAllItems();
        foreach($items as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            if ($product->getTypeID()=='downloadable' && $item->getRowTotal()==0.0) {
                $product_links = Mage::getModel('downloadable/product_type')->getLinks( $product );
                foreach ($product_links as $link) {
                    $downloadPurchase = Mage::getModel('downloadable/link_purchased')
                        ->setOrderId($item->getOrderId())
                        ->setOrderIncrementId($order->getIncrementId())
                        ->setOrderItemId($item->getId())
                        ->setCreatedAt(date('Y-m-d H:i:s'))
                        ->setUpdatedAt(date('Y-m-d H:i:s'))
                        ->setCustomerId($order->getCustomerId())
                        ->setProductName($product->getName())
                        ->setProductSku($product->getSku())
                        ->setLinkSectionTitle('Click here to download');

                    $downloadPurchase->save();

                    $linkHash = strtr(base64_encode(microtime() . $downloadPurchase->getId() . $item->getId() . $product->getId()), '+/=', '-_,');
                    $downloadItem = Mage::getModel('downloadable/link_purchased_item')
                        ->setProductId($product->getId())
                        ->setNumberOfDownloadsBought(0)
                        ->setNumberOfDownloadsUsed(0)
                        ->setLinkTitle($link->getTitle())
                        ->setIsShareable($link->getData('is_shareable'))
                        ->setLinkFile($link->getData('link_file'))
                        ->setLinkType('file')
                        ->setStatus('available')
                        ->setCreatedAt(date('Y-m-d H:i:s'))
                        ->setUpdatedAt(date('Y-m-d H:i:s'))
                        ->setLinkHash($linkHash)
                        ->setOrderItemId($item->getId())
                        ->setPurchasedId($downloadPurchase->getId());

                    $downloadItem->save();
                }
            }
        }
    }

    /**
     * Remove any gift products from a merged in quote
     *
     * @param type $observer
     * @return \ProxiBlue_GiftPromo_Model_Sales_Observer
     */
    public function load_customer_quote_before($observer)
    {
        $removed = false;
        $quote = Mage::getModel('sales/quote')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomerId());
        if (is_object($quote)) {
            foreach ($quote->setData('trigger_recollect',
                0)->getAllItems() as $quoteItem) {
                if ($this->_getHelper()->isAddedAsGift($quoteItem)) {
                    $quote->removeItem($quoteItem->getId());
                    $quoteItem->delete();
                    
                    $removed = true;
                }
            }
        }
        if($removed){
            $quote->save();
        }
        return $this;
    }

    /**
     * Add coupon's rule name to order data
     *
     * @param Varien_Event_Observer $observer
     * @return Mage_SalesRule_Model_Observer
     */
    public function addRuleNameToOrder($observer)
    {
        $order = $observer->getOrder();
        $couponCode = $order->getCouponCode();

        if (empty($couponCode)) {
            return $this;
        }

        /**
         * @var Mage_SalesRule_Model_Coupon $couponModel
         */
        $couponModel = Mage::getModel('giftpromo/promo_coupon');
        $couponModel->loadByCode($couponCode);

        $ruleId = $couponModel->getRuleId();

        if (empty($ruleId)) {
            return $this;
        }

        /**
         * @var Mage_SalesRule_Model_Rule $ruleModel
         */
        $ruleModel = Mage::getModel('giftpromo/promo_rule');
        $ruleModel->load($ruleId);

        $order->setCouponRuleName($ruleModel->getRuleName() . ' (Gift Promotions) ');

        return $this;
    }

}
