<?php

class Magestore_Giftvoucher_Block_Adminhtml_Order_Creditmemo_Refund extends Mage_Adminhtml_Block_Template {

    public function getCreditmemo() {
        return Mage::registry('current_creditmemo');
    }

    public function getOrder() {
        return $this->getCreditmemo()->getOrder();
    }

    public function getCustomer() {
        $order = $this->getOrder();
        if ($order->getCustomerIsGuest()) {
            return false;
        }
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if ($customer->getId()) {
            return $customer;
        }
        return false;
    }

    public function getIsShow() {
        return ($this->getCreditmemo()->getUseGiftCreditAmount() || $this->getCreditmemo()->getGiftVoucherDiscount());
    }

    public function getMaxAmount() {
        $maxAmount = 0;
        if ($this->getCreditmemo()->getUseGiftCreditAmount() && Mage::helper('giftvoucher')->getGeneralConfig('enablecredit', $this->getOrder()->getStoreId())) {
            $maxAmount += floatval($this->getCreditmemo()->getUseGiftCreditAmount());
        }
        if ($this->getCreditmemo()->getGiftVoucherDiscount()) {
            $maxAmount += floatval($this->getCreditmemo()->getGiftVoucherDiscount());
        }
        return Mage::app()->getStore($this->getOrder()->getStoreId())->roundPrice($maxAmount);
    }

    public function formatPrice($price) {
        return $this->getOrder()->formatPrice($price);
    }

}
