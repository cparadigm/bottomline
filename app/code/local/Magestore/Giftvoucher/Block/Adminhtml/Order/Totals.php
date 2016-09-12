<?php

class Magestore_Giftvoucher_Block_Adminhtml_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals_Item {

    public function initTotals() {
        $orderTotalsBlock = $this->getParentBlock();
        $order = $orderTotalsBlock->getOrder();
        if ($order->getGiftVoucherDiscount() && $order->getGiftVoucherDiscount() > 0) {
            $orderTotalsBlock->addTotal(new Varien_Object(array(
                'code' => 'giftvoucher',
                'label' => $this->__('Gift Card (%s)', $order->getGiftCodes()),
                'value' => -$order->getGiftVoucherDiscount(),
                'base_value' => -$order->getBaseGiftVoucherDiscount(),
                    )), 'subtotal');
        }
        if ($refund = $this->getGiftCardRefund($order)) {
            $baseCurrency = Mage::app()->getStore($order->getStoreId())->getBaseCurrency();
            if ($rate = $baseCurrency->getRate($order->getOrderCurrencyCode())) {
                $refundAmount = $refund / $rate;
            }
            if ($order->getCustomerIsGuest() || !Mage::helper('giftvoucher')->getGeneralConfig('enablecredit', $order->getStoreId())) {
                $label = $this->__('Refund to customer gift card code used to check out');
            } else {
                $label = $this->__('Refund to customer\'s Gift Card credit balance');
            }
            $orderTotalsBlock->addTotal(new Varien_Object(array(
                'code' => 'giftcard_refund',
                'label' => $label,
                'value' => $refund,
                'base_value' => $refundAmount,
                'area' => 'footer',
                    )), 'subtotal');
        }
    }

    public function getGiftCardRefund($order) {
        $refund = 0;
        foreach ($order->getCreditmemosCollection() as $creditmemo) {
            $refund += $creditmemo->getGiftcardRefundAmount();
        }
        return $refund;
    }

}
