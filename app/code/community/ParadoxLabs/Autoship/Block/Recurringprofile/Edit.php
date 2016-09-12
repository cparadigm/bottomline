<?php

class ParadoxLabs_Autoship_Block_Recurringprofile_Edit extends ParadoxLabs_AuthorizeNetCim_Block_Recurringprofile_Edit
{
	public function _construct() {
		// Silly thing for backwards compatibility
		if( Mage::registry('current_recurring_profile') ) {
			$profile = Mage::registry('current_recurring_profile');
			
			Mage::register( 'current_profile', $profile, true );
			
			if( $profile->getShippingAddressInfo() != array() ) {
				$origAddr	= Mage::getModel('sales/quote_address')->load( $profile->getInfoValue('shipping_address_info', 'address_id') );
				Mage::register( 'current_address', $origAddr, true );
			}
		}
		
		parent::_construct();
	}
}
