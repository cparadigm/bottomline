<?php

/**
 * Show a subscription lengths dropdown on relevant product pages
 */
class ParadoxLabs_Autoship_Block_Product_Option extends Mage_Core_Block_Template
{
	public function getOptions( $includeZero=true, $fromProduct=false ) {
		return Mage::helper('autoship')->getSubscriptionPeriods( $includeZero, $fromProduct );
	}
}
