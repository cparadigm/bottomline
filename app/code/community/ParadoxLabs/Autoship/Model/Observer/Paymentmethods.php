<?php

class ParadoxLabs_Autoship_Model_Observer_Paymentmethods extends Mage_Core_Model_Abstract
{
	public function paymentMethodIsActive($observer)
	{
		$checkResult	= $observer->getEvent()->getResult();
		$method			= $observer->getEvent()->getMethodInstance();
		
		/**
		 * Check if the method is forbidden by products in the cart
		 * Only allow subscription purchase by CIM.
		 */
		if( $checkResult->isAvailable && $method->getCode() != 'authnetcim' ) {
			if( Mage::app()->getStore()->isAdmin() && Mage::getSingleton('adminhtml/session_quote') ) {
				$items = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getAllItems();
			}
			else {
				$items = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
			}
			
			foreach( $items as $item ) {
				if( $item->getSubscriptionPeriod() > 0 ) {
					$checkResult->isAvailable = false;
					break;
				}
			}
		}
	}
}

