<?php

class ParadoxLabs_Autoship_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Get possible subscription periods.
	 */
	public function getSubscriptionPeriods( $includeZero=true, $fromProduct=false ) {
		$periods	= $this->getSubscriptionPeriodNumbers();
		
		array_walk( $periods, 'trim' );
		
		$labels		= array();
		if( $includeZero === true ) {
			$labels[0] = $this->__('Deliver One Time');
		}
		
		foreach( $periods as $period ) {
			$labels[ $period ] = $this->__( sprintf( 'Deliver Now & Every %s Days', $period ) );
		}
		
		return $labels;
	}
	
	public function getSubscriptionPeriodNumbers() {
		return array_filter( explode( ',', Mage::getStoreConfig( 'paradoxlabs_autoship/autoship/periods' ) ) );
	}
	
	/**
	 * Get the subscription price for the given product. Gives the lesser of the subscription or final price.
	 */
	public function getSubscriptionPrice( $product=null, $item=null ) {
		if( is_null( $product ) ) {
			$product = Mage::registry('current_product');
		}
		
		$price = 0;
		
		if( $product ) {
			if( $product->getSubscriptionPrice() > 0 && $product->getSubscriptionPrice() < $product->getFinalPrice() ) {
				$price = $product->getSubscriptionPrice();
			}
			else {
				$price = $product->getFinalPrice();
			}
			
			/**
			 * Use price from child item if possible (by SKU).
			 */
			if( !is_null( $item ) && $item instanceof Varien_Object ) {
				$childProduct = Mage::getModel('catalog/product')
										->setStoreId( $product->getStoreId() )
										->loadByAttribute( 'sku', $item->getSku() );
				
				if( $childProduct instanceof Varien_Object && $childProduct->getId() > 0 && $childProduct->getSubscriptionPrice() > 0 ) {
					$price = $this->getSubscriptionPrice( $childProduct );
				}
			}
		}
		
		return $price;
	}
	
	/**
	 * Place the given subscription on hold for some period.
	 */
	public function placeSubscriptionHold( $profile, $duration=null ) {
		$adtl	= $profile->getAdditionalInfo();
		
		if( is_string( $adtl ) ) {
			$adtl	= unserialize( $adtl );
		}
		
		/**
		 * Allow additional hold only if we're within the normal billing range. (Don't allow indefinite holds.)
		 */
		if( Mage::app()->getStore()->isAdmin() || $adtl['next_cycle'] <= strtotime( sprintf( '+%s %s', $profile->getPeriodFrequency(), $profile->getPeriodUnit() ) ) ) {
			// If no duration given, default to one period.
			if( is_null( $duration ) ) {
				$duration = sprintf( '+%s %s', $profile->getPeriodFrequency(), $profile->getPeriodUnit() );
			}
			
			$adtl['next_cycle'] = strtotime( '+' . $duration, ( $adtl['next_cycle'] > 0 ? $adtl['next_cycle'] : time() ) );
			
			$profile->setAdditionalInfo( $adtl )
					->save();
		}
		
		return $profile;
	}
	
	/**
	 * Recursively remove objects from an array
	 */
	public function cleanupArray(&$array) {
		if (!$array) {
			return;
		}
		foreach ($array as $key => $value) {
			if (is_object($value)) {
				unset($array[$key]);
			} elseif (is_array($value)) {
				$this->cleanupArray($array[$key]);
			}
		}
	}
}
