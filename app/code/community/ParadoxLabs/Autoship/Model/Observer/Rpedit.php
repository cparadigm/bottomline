<?php

class ParadoxLabs_Autoship_Model_Observer_Rpedit extends Mage_Catalog_Model_Observer
{
	/**
	 * When submitting a recurring profile edit, save various form fields.
	 */
	public function updateRecurringProfile( $observer ) {
		$profile	= $observer->getProfile();
		$itemInfo	= $profile->getOrderItemInfo();
		
		Mage::app()->setCurrentStore( $profile->getStoreId() );
		
		/**
		 * Frequency / period change
		 */
		$period		= intval( Mage::app()->getRequest()->getParam('subscription_period') );
		$periods	= array_keys( Mage::helper('autoship')->getSubscriptionPeriods() );
		if( in_array( $period, $periods ) ) {
			// Change schedule
			$profile->setPeriodFrequency( $period )
					->setScheduleDescription( sprintf( '%s - Every %s Days', $profile->getInfoValue('order_item_info', 'name'), $period ) );
			
			// Update item for order status pages
			$itemInfo['subscription_period'] = $period;
			$profile->setOrderItemInfo( $itemInfo );
		}
		
		/**
		 * Product options change
		 */
		$superAttr	= Mage::app()->getRequest()->getParam('super_attribute');
		
		if( !empty( $superAttr ) ) {
			// Find the product associated with the entered super attribute values (if any). This is a little complicated.
			$parent		= Mage::getModel('catalog/product')->setStoreId( $profile->getStoreId() )->load( $itemInfo['product_id'] );
			$selected	= Mage::getModel('catalog/product')->setStoreId( $profile->getStoreId() )->loadByAttribute( 'sku', $itemInfo['sku'] );
			
			// Get the configurable options
			$config		= Mage::app()->getLayout()->createBlock('catalog/product_view_type_configurable')->setProduct( $parent );
			$configJson	= $config->getJsonConfig();
			$configArr	= json_decode( $configJson, 1 );
			
			// Filter the configurable option products down based on the given option IDs, to figure out which actual product is targeted.
			$values		= array( 'super_attribute' => array() );
			$lastAvailable	= null;
			$nextAvailable	= null;
			foreach( $configArr['attributes'] as $attribute ) {
				foreach( $attribute['options'] as $option ) {
					if( $option['id'] == $superAttr[ $attribute['id'] ] ) {
						if( is_null( $lastAvailable ) ) {
							$nextAvailable = $option['products'];
						}
						else {
							$nextAvailable = array_intersect( $lastAvailable, $option['products'] );
						}
						
						break;
					}
				}
				$lastAvailable = $nextAvailable;
			}
			
			// If that worked properly and the input was valid, we should have one (and only one) array element left now.
			if( count( $lastAvailable ) == 1 ) {
				$newProductId = array_pop( $lastAvailable );
				
				// Is this product different from the one already selected? If so, change it up.
				if( $newProductId != $selected->getId() ) {
					$newProduct = Mage::getModel('catalog/product')->setStoreId( $profile->getStoreId() )->load( $newProductId );
					
					if( $newProduct->isSalable() ) {
						/**
						 * Change product to the new one--adjust name, SKU, and pricing (!).
						 */
						$orderInfo	= $profile->getOrderInfo();
						$shippingAddressInfo = $profile->getShippingAddressInfo();
						
						Mage::log( sprintf( 'Changing product for subscription %s from %s to %s.', $profile->getReferenceId(), $selected->getSku(), $newProduct->getSku() ), null, 'paradoxlabs-autoship.log' );
						
						$itemInfo['sku']		= $newProduct->getSku();
						$itemInfo['name']		= $newProduct->getName();
						$itemInfo['child_id']	= $newProduct->getId();
						
						$newPrice = Mage::helper('autoship')->getSubscriptionPrice( $parent, $newProduct );
						$quantity = intval( $itemInfo['qty'] );
						
						if( $newPrice > 0 && $quantity > 0 ) {
							$newTotal = $newPrice * $quantity;
							
							Mage::log( 'Changing price from '.$profile->getBillingAmount().' to '.$newPrice.' x '.$quantity.' = '.$newTotal, null, 'paradoxlabs-autoship.log' );
							
							$profile->setBillingAmount( $newTotal );
							
							$orderInfo['subtotal'] = $newTotal;
							$orderInfo['base_subtotal'] = $newTotal;
							$orderInfo['subtotal_with_discount'] = $newTotal;
							$orderInfo['base_subtotal_with_discount'] = $newTotal;
							$orderInfo['grand_total'] = $newTotal;
							$orderInfo['base_grand_total'] = $newTotal;
							$profile->setOrderInfo( serialize( $orderInfo ) );
							
							$itemInfo['original_custom_price'] = $newPrice;
							$itemInfo['base_original_price'] = $newPrice;
							$itemInfo['base_calculation_price'] = $newPrice;
							$itemInfo['calculation_price'] = $newPrice;
							$itemInfo['converted_price'] = $newPrice;
							$itemInfo['price'] = $newPrice;
							$itemInfo['row_total'] = $newTotal;
							$itemInfo['base_row_total'] = $newTotal;
							$itemInfo['base_price'] = $newPrice;
							$itemInfo['price_incl_tax'] = $newPrice;
							$itemInfo['base_price_incl_tax'] = $newPrice;
							$itemInfo['row_total_incl_tax'] = $newTotal;
							$itemInfo['base_row_total_incl_tax'] = $newTotal;
							$itemInfo['taxable_amount'] = $newPrice;
							$itemInfo['base_taxable_amount'] = $newPrice;
							
							$shippingAddressInfo['subtotal'] = $newTotal;
							$shippingAddressInfo['base_subtotal'] = $newTotal;
							$shippingAddressInfo['grand_total'] = $newTotal;
							$shippingAddressInfo['base_grand_total'] = $newTotal;
							$shippingAddressInfo['subtotal_incl_tax'] = $newTotal;
							$shippingAddressInfo['base_subtotal_incl_tax'] = $newTotal;
							$profile->setShippingAddressInfo( serialize( $shippingAddressInfo ) );
						}
						
						$profile->setOrderItemInfo( $itemInfo );
					}
				}
			}
		}
		
		return $this;
	}
}