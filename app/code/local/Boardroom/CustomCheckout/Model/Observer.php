<?php

class Boardroom_CustomCheckout_Model_Observer extends Mage_Core_Model_Abstract {

    public function checkForPCD($observer) {
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();
        foreach($items as $item) {

            //pcd
            $product = Mage::getModel('catalog/product')->load($item->getProductId());        
            if ($product->getAttributeText('vendor')=='PCD') {
                $item->setData('is_pcd',1);
                $item->save();

                $qtys[$item->getId()] = $item->getQtyOrdered();
                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($qtys);
                $invoice->register()->pay();
                    $invoice->getOrder()->setIsInProcess(true);
                $invoice->save();
                //$invoiceId = Mage::getModel('sales/order_invoice_api')
                //    ->create($order->getIncrementId(), $qtys ,'' ,1,1);

                if ($order->getIsPcd()==0) {
                    $order->setData('is_pcd',1)->save();
                    //$test = $order->save();var_dump(get_class($order));var_dump($order->getId());var_dump($order->getData('is_pcd'));
//var_dump($test);
//die();
                }
            }

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
            /*
var_dump($product->getTypeID());var_dump($item->getRowTotal());var_dump($item->getRowTotal()==0.0);
            if ($product->getTypeID()=='downloadable' && $item->getRowTotal()==0.0) {echo 1;die();
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
            */
        }
    }

}
