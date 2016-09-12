<?php

/**
 * Helper routines to manage gifts and products
 *
 * @category ProxiBlue
 * @package  ProxiBlue_GiftPromo
 * @author   Lucas van Staden <support@proxiblue.com.au>
 * @license  Copyright ProxiBlue - See EULA on www.proxiblue.com.au
 * @link     www.proxiblue.com.au
 */
class ProxiBlue_GiftPromo_Helper_Data
    extends Mage_Catalog_Helper_Data
{

    /**
     * Get the parent product of given item
     *
     * @param ProxiBlue_GiftPromo_Model_Sales_Quote_Item $item
     * @return \Mage_Catalog_Model_Product|\Varien_Object
     */
    public function getParentOfGift($item, $returnParentProductId = False)
    {
        if ($buyRequest = $this->isAddedAsGift($item)) {
            if (is_object($buyRequest)) {
                if ($returnParentProductId) {
                    return $buyRequest->getParentProductId();
                }
                $parentProduct = Mage::getModel('catalog/product')->load($buyRequest->getParentProductId());
                if ($parentProduct->getId()) {
                    return $parentProduct;
                }
            }
        }
        return false;
    }

    /**
     * Get the parent quote item of given item
     *
     * @param ProxiBlue_GiftPromo_Model_Sales_Quote_Item $item
     * @return \Mage_Catalog_Model_Product|\Varien_Object
     */
    public function getParentQuoteItemOfGift($item, $returnParentId = False)
    {
        if ($buyRequest = $this->isAddedAsGift($item)) {
            if ($returnParentId) {
                return $buyRequest->getParentQuoteItemId();
            }
            $parentQuoteItem = $item->getQuote()->getItemById($buyRequest->getParentQuoteItemId());
            if ($parentQuoteItem instanceof Mage_Sales_Model_Quote_Item) {
                return $parentQuoteItem;
            }
        }
        return false;
    }

    /**
     * Check if cart item was added as a gift item
     *
     * @param object Mage_Sales_Model_Quote_Item|Mage_Sales_Model_Order_Item|Mage_Sales_Model_Order_Item|Mage_Catalog_Model_Product $item
     * @return boolean
     */
    public function isAddedAsGift($item)
    {
        if ($item instanceof Mage_Sales_Model_Quote_Item) {
            $infoBuyRequest = $item->getOptionByCode('info_buyRequest');
            if (!is_object($infoBuyRequest)) {
                return false;
            }
            $buyRequest = new Varien_Object(unserialize($infoBuyRequest->getValue()));
        } else if ($item instanceof Mage_Sales_Model_Order_Item) {
            $buyRequest = new Varien_Object($item->getProductOptions());
        } else if ($item instanceof Mage_Catalog_Model_Product) {
            $infoBuyRequest = $item->getCustomOption('info_buyRequest');
            if (!is_object($infoBuyRequest)) {
                return false;
            }
            $buyRequest = new Varien_Object(unserialize($infoBuyRequest->getValue()));
        } else {
            if (!is_object($item)) {
                return false;
            }
            $infoBuyRequest = $item->getCustomOption('info_buyRequest');
            if (!is_object($infoBuyRequest)) {
                return false;
            }
            $buyRequest = new Varien_Object(unserialize($infoBuyRequest->getValue()));
        }
        if (is_object($buyRequest)) {
            if ($buyRequest->getAddedByRule()) {
                return $buyRequest;
            }
        }
        return false;
    }

    public function getTotalGiftQtyInCartByProduct($giftProduct)
    {
        $qty = 0;
        $cart = $this->getCartSession();
        foreach ($cart->getQuote()->setData('trigger_recollect',
            0)->getAllItems() as $findGiftItem) {
            if (!$this->testGiftTypeCode($findGiftItem->getProductType())) {
                continue;
            }
            if ($findGiftItem->getProductId() == $giftProduct->getId()) {
                $qty += $findGiftItem->getQty();
            }
        }
        Mage::dispatchEvent('gift_product_total_qty_in_cart_by_product',
            array(
            'gift_product' => $giftProduct,
            'qty' => $qty,
        ));
        return $qty;
    }

    /**
     * Get the given gift item current qty
     * @param object $quoteGiftItem
     * @return integer
     */
    public function getTotalGiftQtyInCart(ProxiBlue_GiftPromo_Model_Sales_Quote_Item $quoteGiftItem, $excludeThisGift = false)
    {
        $qty = 0;
        foreach ($quoteGiftItem->getQuote()->setData('trigger_recollect',
            0)->getAllVisibleItems() as $findGiftItem) {
            if (!$this->testGiftTypeCode($findGiftItem->getProductType())) {
                continue;
            }
            if ($findGiftItem->getProductId() == $quoteGiftItem->getProductId()) {
                if ($excludeThisGift == true) {
                    $findGiftItemParentId = $this->getParentQuoteItemOfGift($findGiftItem,
                        true);
                    $quoteGiftItemParentId = $this->getParentQuoteItemOfGift($quoteGiftItem,
                        true);
                    if ($findGiftItemParentId == $quoteGiftItemParentId) {
                        continue;
                    }
                }
                $qty += $findGiftItem->getQty();
            }
        }
        Mage::dispatchEvent('gift_product_total_qty_in_cart',
            array(
            'gift_quote_item' => $quoteGiftItem,
            'qty' => $qty,
            'exclude_this_gift' => $excludeThisGift
        ));
        return $qty;
    }

    /**
     * Add the given product as a gift attached to parent
     *
     * @param ProxiBlue_GiftPromo_Model_Product $giftProduct
     * @param Mage_Catalog_Model_Product $parentProduct
     * @return object
     */
    public function addGiftToCart(ProxiBlue_GiftPromo_Model_Product $giftProduct, $parentItem, $params)
    {
        // is this gift product parked and not aded from a rule?
        $currentSelectedGifts = $this->getCurrentSelectedGifts();
        $currentParkedGifts = $this->getCurrentParkedGifts();
        // is parked
        if (array_key_exists($parentItem->getId(),
                $currentParkedGifts) && array_key_exists($giftProduct->getId(),
                $currentParkedGifts[$parentItem->getId()]) && $giftProduct->getTypeId() != ProxiBlue_GiftPromo_Model_Product_Type_Gift_Configurable::TYPE_CODE) {
            return false;
        }
        $cart = $this->getCartSession();
        // PARKED GIFT, AND HAS NOT BEEN SELECTED
        $parkit = false;
        if ($giftProduct->getAddMethod() != ProxiBlue_GiftPromo_Model_Product::ADD_METHOD_DIRECT) {
            if (!array_key_exists($parentItem->getId(),
                    $currentSelectedGifts['parked']) OR
                array_key_exists($parentItem->getId(),
                    $currentSelectedGifts['parked']) && !array_key_exists($giftProduct->getId(),
                    $currentSelectedGifts['parked'][$parentItem->getId()]) OR
                array_key_exists($parentItem->getId(),
                    $currentParkedGifts) && array_key_exists($giftProduct->getId(),
                    $currentParkedGifts[$parentItem->getId()])) {
                $parkit = true;
            }
        }
        // catch gift-configurables with no super _attribute set
        if ($giftProduct->getTypeId() == ProxiBlue_GiftPromo_Model_Product_Type_Gift_Configurable::TYPE_CODE && !array_key_exists('super_attribute',
                $params)) {
            $parkit = true;
        }
        if ($parkit) {
            $this->parkGift($parentItem->getId(),
                $giftProduct->getId());
        } else {
            // EVEYTHING ELSE
            $giftProduct->addCustomOption('additional_options',
                serialize(array(
                'gifted_message' =>
                array(
                    'label' => $giftProduct->getGiftedLabel(),
                    'value' => $giftProduct->getGiftedMessage()
                )
                ))
                ,
                $giftProduct);

            $testRatioQty = 1;

            if ($testRatioQty > 0) {
                try {
                    $cart->addProduct($giftProduct,
                        array_merge(array(
                        'qty' => $testRatioQty,
                        'added_by_rule' => $parentItem->getId()
                            ),
                            $params)
                    );
                } catch (Mage_Core_Exception $e) {
                    $messages = array_unique(explode("\n",
                            $e->getMessage()));
                    foreach ($messages as $message) {
                        Mage::getSingleton('adminhtml/session_quote')->addError(Mage::helper('giftpromo')->__('Sorry, could not add your gift due to the following error: %s',
                                $message));
                    }
                }

                $messageFactory = Mage::getSingleton('core/message');
                $message = $messageFactory->notice(Mage::helper('giftpromo')->__("Gift '%s' was added to your shopping cart.",
                        htmlentities($giftProduct->getName())));

                if (Mage::app()->getStore()->isAdmin()) {
                    Mage::getSingleton('adminhtml/session_quote')->addMessages($message);
                } else {
                    if (!$cart->getCheckoutSession()->getSkipGiftNotice()) {
                        if (!$this->isPre16()) {
                            $cart->getCheckoutSession()->addUniqueMessages($message);
                        } else {
                            $cart->getCheckoutSession()->addMessage($message);
                        }
                    }
                    $cart->getCheckoutSession()->unsSkipGiftNotice();
                }
            }
            return $cart->getQuote();
        }
    }

    public function calculateQtyRate($quoteGiftItems, $quote)
    {
        $newQty = 1;
        $limitTestQty = 1;
        // itterate the cart (non gift) items.
        // if it has a rule applied, then check if the gift associated has a ration set.
        foreach ($quoteGiftItems as $quoteGiftItem) {
            foreach ($quoteGiftItem as $item) {
                if ($this->testGiftTypeCode($item->getProductType())) {
                    $giftProduct = $item->getProduct();
                    if (is_null($giftProduct->getGiftedPrice())) {
                        $giftProduct = mage::getModel('giftpromo/product')->cloneProduct($giftProduct);
                    }

                    $rateQty = $giftProduct->getGiftedRateProductQty();
                    $rateQty = (empty($rateQty))
                        ? 1
                        : $giftProduct->getGiftedRateProductQty();
                    $giftQty = $giftProduct->getGiftedRateGiftRate();
                    $giftQty = (empty($giftQty))
                        ? 1
                        : $giftProduct->getGiftedRateGiftRate();
                    $maxQty = $giftProduct->getGiftedQtyMax();
                    // have we reached a max qty?
                    if ($maxQty != 0 && $item->getQty() >= $maxQty) {
                        $item->setQty($maxQty);
                        $item->save();
                        continue;
                    }
                    $parentQouteItem = $this->getParentQuoteItemOfGift($item);

                    $limitTestQty = (is_object($parentQouteItem))
                        ? $parentQouteItem->getQty()
                        : 1;
                    // calculate the rate
                    $qtyTest = $limitTestQty / $rateQty;
                    $qtyTest = floor($qtyTest);
                    $newQty = $qtyTest * $giftQty;
                    if ($newQty < 0) {
                        $newQty = 0;
                    }
                    if ($maxQty > 0 && $newQty >= $maxQty) {
                        $newQty = $maxQty;
                    }
                    $oldQty = $item->getQty();
                    if ($newQty == 0) {
                        if ($item->getId()) {
                            $quote->removeItem($item->getId());
                        }
                    } else {
                        $item->setQty($newQty);
                        if ($item->getQty() != $oldQty) {
                            if (method_exists($item,
                                    'save')) {
                                $item->save();
                            }
                        }
                    }
                }
            }
        }
        return $newQty;
    }

    public function calculateQtyRateOld($quoteGiftItems, $quote)
    {
        $newQty = 0;
        $limitTestQty = 0;
        // tally up the qty for all non gift items
        foreach ($quote->setData('trigger_recollect',
            0)->getAllVisibleItems() as $item) {
            if ($this->testGiftTypeCode($item->getProductType())) {
                continue;
            }
            $limitTestQty += $item->getQty();
        }
        foreach ($quoteGiftItems as $quoteGiftItem) {
            foreach ($quoteGiftItem as $giftLineItem) {
                //$limitTestQty = $limitTestQty - $giftLineItem->getQty();
                $giftProduct = $giftLineItem->getProduct();
                if (is_null($giftProduct->getGiftedPrice())) {
                    $giftProduct = mage::getModel('giftpromo/product')->cloneProduct($giftProduct);
                }
                $rateSku = $giftProduct->getGiftedRateProductQtySku();
                $rateQty = $giftProduct->getGiftedRateProductQty();
                $giftQty = $giftProduct->getGiftedRateGiftRate();
                $maxQty = $giftProduct->getGiftedQtyMax();
                if (empty($rateQty) || $maxQty != 0 && $giftLineItem->getQty() >= $maxQty) {
                    $newQty = 1;
                    continue; // no need to go on, it is at max qty already
                }
                if (!empty($rateSku)) {
                    $limitProductId = mage::getModel('catalog/product')->getIdBySku($rateSku);
                    $limitProduct = mage::getModel('catalog/product')->load($limitProductId);
                    $limitQuoteItem = $quote->getItemByProduct($limitProduct);
                    // find the product to limit by quote line item, it is not necassarily this one.
                    $giftLineItemProduct = $giftLineItem->getProduct()->getId();
                    if ($limitProductId && is_object($limitQuoteItem) && $limitQuoteItem->getId() && $this->isAddedAsGift($limitQuoteItem) == false) {
                        // replace the qty with the nominated item qty.
                        $limitTestQty = $limitQuoteItem->getQty();
                    }
                }
                $qtyTest = $limitTestQty / $rateQty;
                $qtyTest = floor($qtyTest);
                $newQty = $qtyTest * $giftQty;
                if ($newQty < 0) {
                    $newQty = 0;
                }
                if ($maxQty > 0 && $newQty >= $maxQty) {
                    $newQty = $maxQty;
                }
                $oldQty = $giftLineItem->getQty();
                if ($newQty == 0) {
                    if ($giftLineItem->getId()) {
                        $quote->removeItem($giftLineItem->getId());
                    }
                } else {
                    $giftLineItem->setQty($newQty);
                    if ($giftLineItem->getQty() != $oldQty) {
                        if (method_exists($giftLineItem,
                                'save')) {
                            $giftLineItem->save();
                        }
                    }
                }
            }
        }
        return $newQty;
    }

    public function wasAddedByRule($item)
    {
        $buyRequest = $this->isAddedAsGift($item);
        if (is_object($buyRequest) && $buyRequest->getAddedByRule()) {
            return true;
        }
        return false;
    }

    /**
     * Take an array of gift items and add them to the cart
     *
     * @param array $giftProducts
     * @param Mage_Catalog_Model_Product $parentProduct
     */
    public function addGiftItems(array $giftProducts, $parentItem, $params = array())
    {
        Mage::dispatchEvent('gift_product_add_to_cart_before',
            array(
            'gift_products' => $giftProducts,
            'parent' => $parentItem,
            'params' => $params
        ));
        $quote = false;
        foreach ($giftProducts as $giftProduct) {
            // block against badly saved gift item.
            if ($giftProduct->getId()) {
                if ($giftProduct->getIsSalable()) {
                    $quote = $this->addGiftToCart($giftProduct,
                        $parentItem,
                        $params);
                } else {
                    // notify about out of stock
                    $messageFactory = Mage::getSingleton('core/message');
                    $message = $messageFactory->error(Mage::helper('giftpromo')->__("Sorry, gift '%s' is currently out of stock.",
                            htmlentities($giftProduct->getName())));
                    if (Mage::app()->getStore()->isAdmin()) {
                        if (!$this->isPre16()) {
                            Mage::getSingleton('adminhtml/session_quote')->addUniqueMessages($message);
                        } else {
                            Mage::getSingleton('adminhtml/session_quote')->addMessage($message);
                        }
                    } else {
                        $cart = Mage::getModel('checkout/cart');
                        if (!$this->isPre16()) {
                            $cart->getCheckoutSession()->addUniqueMessages($message);
                        } else {
                            $cart->getCheckoutSession()->addMessage($message);
                        }
                    }
                }
            }
        }
        if (is_object($quote)) {
            $quote->save();
        }
        Mage::dispatchEvent('gift_product_add_to_cart_after',
            array(
            'gift_products' => $giftProducts,
            'parent' => $parentItem,
            'params' => $params
        ));
    }

    /**
     * Get all the gift products in cart that are based on rules
     * @return type
     */
    public function getRuleBasedCartItems()
    {
        $items = array();
        $cart = $this->getCartSession();
        Mage::getSingleton('checkout/session')->setSkipTriggerCollect(true); // prevents endless loop if session get mucked up.
        foreach ($cart->getQuote()->setData('trigger_recollect',
            0)->getAllItems() as $findRuleGiftItem) {
            if (!$this->testGiftTypeCode($findRuleGiftItem->getProductType()) || !$this->wasAddedByRule($findRuleGiftItem)) {
                continue;
            }
            $buyRequest = $this->isAddedAsGift($findRuleGiftItem);
            $ruleUsed = $buyRequest->getAddedByRule();
            if ($ruleUsed) {
                $items[$ruleUsed][] = $findRuleGiftItem;
            }
        }
        Mage::dispatchEvent('gift_product_rule_based_cart_items',
            array(
            'cart' => $cart,
            'gift_items' => $items
        ));
        return $items;
    }

    /**
     * Get all the gift products in cart that are based on rules
     * @return type
     */
    public function getAllGiftBasedCartItems()
    {
        $items = array();
        $cart = Mage::getModel('checkout/cart');
        foreach ($cart->getQuote()->setData('trigger_recollect',
            0)->getAllItems() as $findGiftItem) {
            if (!$this->testGiftTypeCode($findGiftItem->getProductType())) {
                continue;
            }
            $items[] = $findGiftItem;
        }
        Mage::dispatchEvent('gift_product_all_gift_based_cart_items',
            array(
            'cart' => $cart,
            'gift_items' => $items
        ));
        return $items;
    }

    /**
     * Reset the current parked gifts.
     *
     * @param integer $parentItemId
     * @param integer $giftId
     */
    public function resetParkedGiftData($parentItemId, $giftId)
    {
        $currentParkedGifts = $this->getCurrentParkedGifts();
        if (array_key_exists($parentItemId,
                $currentParkedGifts) && array_key_exists($giftId,
                $currentParkedGifts[$parentItemId])) {
            unset($currentParkedGifts[$parentItemId][$giftId]);
            if (count($currentParkedGifts[$parentItemId]) == 0) {
                unset($currentParkedGifts[$parentItemId]);
            }
            $this->setCurrentParkedGifts($currentParkedGifts);
        }
        return $currentParkedGifts;
    }

    /**
     * Reset the current parked gifts for a parent
     *
     * @param integer $parentItemId
     */
    public function resetParkedGiftsForParent($parentItemId)
    {
        $currentParkedGifts = $this->getCurrentParkedGifts();
        if (array_key_exists($parentItemId,
                $currentParkedGifts)) {
            unset($currentParkedGifts[$parentItemId]);
            $this->setCurrentParkedGifts($currentParkedGifts);
        }
        return $currentParkedGifts;
    }

    /**
     * Park the gifev gift
     *
     * @param integer $parentItemId
     * @param integer $giftProductId
     */
    public function parkGift($parentItemId, $giftProductId)
    {
        $currentParkedGifts = $this->getCurrentParkedGifts();
        if (!array_key_exists($parentItemId,
                $currentParkedGifts)) {
            $currentParkedGifts[$parentItemId] = array();
        }
        $currentParkedGifts[$parentItemId][$giftProductId] = $giftProductId;
        $this->setCurrentParkedGifts($currentParkedGifts);
        return $currentParkedGifts;
    }

    /**
     * Get the current parked gifts
     *
     * @return array
     */
    public function getCurrentParkedGifts()
    {
        $currentParkedGifts = Mage::getSingleton('checkout/session')->getCurrentParkedGifts();
        if (!is_array($currentParkedGifts)) {
            return array();
        }
        return $currentParkedGifts;
    }

    /**
     * Store the given array of parked gifts
     *
     * @param array $currentParkedGifts
     */
    public function setCurrentParkedGifts($currentParkedGifts)
    {
        Mage::dispatchEvent('gift_product_set_parked_gifts',
            array(
            'parked' => $currentParkedGifts,
        ));
        Mage::getSingleton('checkout/session')->setCurrentParkedGifts($currentParkedGifts);
    }

    /**
     * Get the current selected gifts
     *
     * @return array
     */
    public function getCurrentSelectedGifts($plainRuleId = false)
    {
        $currentSelectedGifts = Mage::getSingleton('checkout/session')->getCurrentSelectedGifts();
        if (!is_array($currentSelectedGifts)) {
            $currentSelectedGifts = array(
                'parked' => array());
        }
        if (!array_key_exists('parked',
                $currentSelectedGifts)) {
            $currentSelectedGifts['parked'] = array();
        }
        if ($plainRuleId) {
            foreach ($currentSelectedGifts as $key => $selected) {
                $keyPart = explode('_',
                    $key);
                unset($currentSelectedGifts[$key]);
                $currentSelectedGifts[$keyPart[0]] = $selected;
            }
        }
        Mage::dispatchEvent('gift_product_get_selected_gifts',
            array(
            'selected' => $currentSelectedGifts,
        ));
        return $currentSelectedGifts;
    }

    /**
     * Store the given selected gift array
     *
     * @param array $currentSelectedGifts
     */
    public function setCurrentSelectedGifts($currentSelectedGifts)
    {
        Mage::dispatchEvent('gift_product_set_selected_gifts',
            array(
            'selected' => $currentSelectedGifts,
        ));
        Mage::getSingleton('checkout/session')->setCurrentSelectedGifts($currentSelectedGifts);
    }

    /**
     * Remove the given gift from the given parent as selected
     *
     * @param integer $parentItemId
     * @param integer $giftProductId
     */
    public function resetCurrentSelectedGift($parentItemId, $giftProductId)
    {
        $currentSelectedGifts = $this->getCurrentSelectedGifts();
        if (array_key_exists($parentItemId,
                $currentSelectedGifts['parked']) && array_key_exists($giftProductId,
                $currentSelectedGifts['parked'][$parentItemId])) {
            unset($currentSelectedGifts['parked'][$parentItemId][$giftProductId]);
            if (count($currentSelectedGifts['parked'][$parentItemId]) == 0) {
                unset($currentSelectedGifts['parked'][$parentItemId]);
            }
            $this->setCurrentSelectedGifts($currentSelectedGifts);
        }
        return $currentSelectedGifts;
    }

    /**
     * Reset all selected products for given parent product
     *
     * @param integer $parentItemId
     */
    public function resetCurrentSelectedGiftsForParent($parentItemId)
    {
        $currentSelectedGifts = $this->getCurrentSelectedGifts();
        if (array_key_exists($parentItemId,
                $currentSelectedGifts['parked'])) {
            unset($currentSelectedGifts['parked'][$parentItemId]);
            $this->setCurrentSelectedGifts($currentSelectedGifts);
        }
        $currentSelectedGiftsKeys = array_keys($currentSelectedGifts);
        foreach ($currentSelectedGiftsKeys as $currentSelectedGiftsKey) {
            $keyParts = explode("_",
                $currentSelectedGiftsKey);
            $rulePart = array_pop($keyParts);
            if ($parentItemId == $rulePart) {
                unset($currentSelectedGifts[$currentSelectedGiftsKey]);
                $this->setCurrentSelectedGifts($currentSelectedGifts);
            }
        }
        return $currentSelectedGifts;
    }

    /**
     * Check if the gifen product is of a gifted product type
     *
     * @param string $productType
     */
    public function testGiftTypeCode($productType)
    {
        if (substr($productType,
                0,
                5) != ProxiBlue_GiftPromo_Model_Product_Type::TYPE_GIFT) {
            return false;
        }
        return true;
    }

    public function getGiftProductType($productType)
    {
        if (is_null($productType)) {
            $break = 1;
        }
        if (!$this->testGiftTypeCode($productType)) {
            return ProxiBlue_GiftPromo_Model_Product_Type::TYPE_GIFT . $productType;
        } else {
            return $productType;
        }
    }


    /**
     * Get all gifts assocaited with a product
     *
     * @param Mage_Catalog_Model_Product $product
     * @param bool $asArray
     * @return mixed array\Varien_Data_Collection
     */
    public function testItemHasValidGifting(Mage_Catalog_Model_Product $product, $asArray = true)
    {
        $result = array();
        $giftProducts = array();
        try {
            $rules = Mage::getModel('giftpromo/promo_rule')->getCollection();
            foreach ($rules as $rule) {
                // test if the rule can be processed (valid coupon/usage/customer group if coupon is required for rule)
                $rule = Mage::getModel('giftpromo/promo_rule')->load($rule->getId());
                // create a copy of the current quote object
                // add this product to the copy quote
                // validate against the copy quote
                $quote = Mage::getModel('checkout/session')->getQuote();
                $copyQuote = new Varien_Object;
                $copyQuote->setData($quote->getData());
                // inject this product as an item here!
                $allItems = array();
                $product->setQty(1);
                $product->setProductId($product->getId()); // workaround since it expects cart items, not products
                $allItems[] = $product;
                //adjust the cart subtotal to reflect the additional item price, thus making rule valid for totals
                $copyQuote->setAllItems($allItems);
                $copyQuote->setAllVisibleItems($allItems);
                $copyQuote->setSubtotal($copyQuote->getSubtotal() + $product->getPrice());
                $copyQuote->setSkipForced(true);
                if ($rule->validate($copyQuote)) {
                    $giftProductsFromRule = $rule->getItemsArray();
                    if (is_array($giftProductsFromRule)) {
                        foreach ($giftProductsFromRule as $ruleBasedGiftProduct) {
                            $giftProducts[$ruleBasedGiftProduct->getId()] = $ruleBasedGiftProduct;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
        if ($asArray) {
            $result = $giftProducts;
        } else {
            $collection = new Varien_Data_Collection;
            foreach ($giftProducts as $giftProduct) {
                try {
                    $collection->addItem($giftProduct);
                } catch (Exception $e) {
                    // fail silently, as the item already exists as a gift, thus don't add it again
                }
            }
            $result = $collection;
        }
        return $result;
    }

    public function checkForGiftChanges($quote)
    {
        if (mage::registry('skip_gift_check') != true) { // prevent a loop by forcing only one iteration fo rules checking in a given request
            mage::register('skip_gift_check',
                true,
                true);
            $address = $quote->getShippingAddress();
            $store = Mage::app()->getStore($quote->getStoreId());
            $validator = Mage::getSingleton('giftpromo/promo_validator');
            $validator->init($store->getWebsiteId(),
                $quote->getCustomerGroupId(),
                $quote->getCouponCode());
            $validator->processGiftRules($address);
        }
    }

    public function isPre16()
    {
        $magentoVersion = Mage::getVersionInfo();
        if ($magentoVersion['minor'] < 6) {
            return true;
        }
        // magento professional will return true
        if (method_exists('Mage',
                'getEdition')) {
            $magentoEdition = Mage::getEdition();
            if ($magentoEdition == Mage::EDITION_PROFESSIONAL) {
                return true;
            }
        } else {
            return true;
        }

        return false;
    }

    public function isPost18()
    {
        $magentoVersion = Mage::getVersionInfo();
        if ($magentoVersion['minor'] > 8) {
            return true;
        }
        return false;
    }

    public function getCartSession()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            $object = Mage::getSingleton('adminhtml/sales_order_create');
            return $object;
        } else {
            return Mage::getModel('checkout/cart');
        }
    }

    public function getAppliedRuleIds($value)
    {
        if (is_object($value)) {
            $value = $value->getAppliedGiftRuleIds();
        }
        $ids = json_decode($value,
            true);
        if (is_null($ids)) {
            return array();
        }
        return array_filter($ids);
    }

}
