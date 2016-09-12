<?php

class Boardroom_CustomCheckout_Model_Observer extends Mage_Core_Model_Abstract {

    public function checkForPCD($observer) {
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllVisibleItems();
        foreach($items as $item) {

            //downloadable
            if ($item->getSku()=='DWNLD001' || $item->getSku()=='DWNLD002' || $item->getSku()=='DWNLD0016') {
                $orders = Mage::getModel('sales/order_invoice')->getCollection()
                        ->addAttributeToFilter('order_id', array('eq'=>$order->getId()));
                $orders->getSelect()->limit(1);  
 
                if ((int)$orders->count() !== 0) {
                    return $this;
                }
 
                if ($order->getState() == Mage_Sales_Model_Order::STATE_NEW) {
 
                    try {
                        if(!$order->canInvoice()) {
                            $order->addStatusHistoryComment('Order cannot be invoiced.', false);
                            $order->save();  
                        }
 
                        //START Handle Invoice
                        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
 
                        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                        $invoice->register();
 
                        $invoice->getOrder()->setCustomerNoteNotify(false);          
                        $invoice->getOrder()->setIsInProcess(true);
                        $order->addStatusHistoryComment('Automatically INVOICED.', false);
 
                        $transactionSave = Mage::getModel('core/resource_transaction')
                            ->addObject($invoice)
                            ->addObject($invoice->getOrder());
 
                        $transactionSave->save();
                        //END Handle Invoice
 
                        //START Handle Shipment
                        $shipment = $order->prepareShipment();
                        $shipment->register();
 
                        $order->setIsInProcess(true);
                        $order->addStatusHistoryComment('Automatically SHIPPED.', false);
 
                        $transactionSave = Mage::getModel('core/resource_transaction')
                            ->addObject($shipment)
                            ->addObject($shipment->getOrder())
                            ->save();
                        //END Handle Shipment
                    } catch (Exception $e) {
                        $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), false);
                        $order->save();
                    }                
                }
 
                return $this;   
            }
        }
    }

}
