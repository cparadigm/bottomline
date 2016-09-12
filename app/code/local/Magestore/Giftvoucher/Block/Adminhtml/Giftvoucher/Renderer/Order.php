<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $order = Mage::getModel('sales/order')->loadByIncrementId($row->getOrderIncrementId());
        return sprintf('<a href="%s" title="%s">%s</a>', $this->getUrl('adminhtml/sales_order/view', array('order_id' => $order->getId())), Mage::helper('giftvoucher')->__('View Order Detail'), $row->getOrderIncrementId()
        );
    }

}
