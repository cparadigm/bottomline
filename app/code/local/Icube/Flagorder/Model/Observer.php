<?php
class Icube_Flagorder_Model_Observer
{

	public function flagNpPoOrders(Varien_Event_Observer $observer)
	{
		$order = $observer->getEvent()->getOrder();
		$items = $order->getAllVisibleItems();
		$address = $order->getShippingAddress()->getStreet();

		if ( $this->isPoBox( $address ) ) {

			foreach ($items as $item) {
				$product = $item->getProduct();
				$vendor = $this->getVendor($product->getId());

				if($vendor != 'NP') {
					continue;
				}

				try {

					// set order status to 'Pending'
					$order->setState(Mage_Sales_Model_Order::STATE_NEW)
				        ->setStatus('pending')
				        ->save();

				    // send NP Order Email    
				    Mage::helper('flagorder')->sendNpOrderEmail($order->getId());      
				
				} catch (Exception $e) {
					Mage::log('Flag NP Order #' .$order->getIncrementId(). ' failed:'.$e->getMessage(), null, 'Icube_Flagorder.log');
				}
				
			}
		}

	}

    private function isPoBox( $address) {
    	$address = is_array($address) ? implode(' ', $address) : $address;
		$_poBox = array('PO Box', 'P.O Box', 'P.O. Box', 'P.O.Box');

        $chr = array();
        foreach($_poBox as $needle) {
                if( stripos($address, $needle) !== false) {
                	return true;
                }
        }
        
        return false;
	}
		
	private function getVendor($productId){

       $storeId = Mage::app()->getStore()->getId();

       $optionId = Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'vendor', $storeId);
       
       $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','vendor');
        $collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->setPositionOrder('asc')
            ->setAttributeFilter($attributeId)
            ->setStoreFilter(0)
            ->load();

        $collection = $collection->toOptionArray();

        foreach ($collection as $option) {
            if ($option['value'] == $optionId) {
                $vendor = $option["label"];
                return $vendor;
            }
        }
    }

}
