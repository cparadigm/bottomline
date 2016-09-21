<?php

class Glew_Service_Model_Types_Order
{
    public function parse($order)
    {
        $this->id = $order->getId();
        $this->email = $order->getCustomerEmail();
        $this->increment_id = $order->getIncrementId();
        $this->customer_id = $order->getCustomerId() ? $order->getCustomerId() : 0;
        $this->customer_group_id = $order->getCustomerGroupId() ? $order->getCustomerGroupId() : null;
        $customerGroup = Mage::getModel('customer/group')->load($order->getCustomerGroupId());
        $this->customer_group = $customerGroup->getCode();
        $this->created_at = $order->getCreatedAt();
        $this->updated_at = $order->getUpdatedAt();
        $this->state = $order->getState();
        $this->status = $order->getStatus();
        $this->customer_is_guest = $order->getCustomerIsGuest();
        $this->total_qty_ordered = (int) $order->getTotalQtyOrdered();
        $this->currency = $order->getOrderCurrencyCode();
        $this->total = round($order->getGrandTotal(), 2);
        $this->tax = round($order->getTaxAmount(), 2);
        $this->shipping_total = round($order->getShippingAmount(), 2);
        $this->shipping_tax = round($order->getShippingTaxAmount(), 2);
        $this->shipping_description = $order->getShippingDescription();
        try {
            $this->payment_method = $order->getPayment()->getMethodInstance()->getTitle();
        } catch (Exception $e) {
            $this->payment_method = '';
        }

        $this->discount_amount = round($order->getDiscountAmount(), 2);
        $this->discount_description = $order->getDiscountDescription();
        $this->weight = $order->getWeight();
        $this->remote_ip = $order->getRemoteIp();
        $this->store = $order->getStore()->getCode();

        return $this;
    }
}
