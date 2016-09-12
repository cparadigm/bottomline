<?php

class Magestore_Giftvoucher_Block_Adminhtml_Order_Creditmemo_Credit extends Mage_Adminhtml_Block_Sales_Order_Totals_Item {

    public function initTotals() {
        $orderTotalsBlock = $this->getParentBlock();
        $order = $orderTotalsBlock->getCreditmemo();
        if ($order->getUseGiftCreditAmount() && $order->getUseGiftCreditAmount() > 0) {
            $orderTotalsBlock->addTotal(new Varien_Object(array(
                'code' => 'giftcardcredit',
                'label' => $this->__('Gift Card credit'),
                'value' => -$order->getUseGiftCreditAmount(),
                'base_value' => -$order->getBaseUseGiftCreditAmount(),
                    )), 'subtotal');
        }
    }

}