<?php

class Glew_Service_Model_Types_AbandonedCart
{
    public function parse($cart)
    {
        $products = array();

        foreach ($cart->getAllItems() as $item) {
            $obj = new stdClass();
            $obj->product_id = $item->getProduct()->getId();
            $obj->qty = $item->getQty();
            $products[] = $obj;
        }

        $this->id = $cart->getId();
        $this->email = $cart->getCustomerEmail();
        $this->customer_id = $cart->getCustomerId() ? $cart->getCustomerId() : 0;
        $this->customer_group_id = $cart->getCustomerGroupId() ? $cart->getCustomerGroupId() : null;
        $customerGroup = Mage::getModel('customer/group')->load($cart->getCustomerGroupId());
        $this->customer_group = $customerGroup->getCode();
        $this->created_at = $cart->getCreatedAt();
        $this->updated_at = $cart->getUpdatedAt();
        $this->items_count = $cart->getItemsCount();
        $this->items_qty = $cart->getItemsQty();
        $this->products = $products;
        $this->total = round($cart->getSubtotal(), 2);

        $this->discount_amount = round($cart->getDiscountAmount(), 2);
        $this->discount_description = $cart->getDiscountDescription();
        $this->coupon_code = $cart->getCouponCode();
        $this->weight = $cart->getWeight();
        $this->remote_ip = $cart->getRemoteIp();
        $this->store = $cart->getStore()->getCode();
        $this->currency = $cart->getQuoteCurrencyCode();

        return $this;
    }
}
