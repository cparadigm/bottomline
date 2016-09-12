<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 * 
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 * 
 * Want to customize or need help with your store?
 *  Phone: 717-431-3330
 *  Email: sales@paradoxlabs.com
 *
 * @category	ParadoxLabs
 * @package		TokenBase
 * @author		Ryan Hoerr <magento@paradoxlabs.com>
 * @license		http://store.paradoxlabs.com/license.html
 */

class ParadoxLabs_TokenBase_Block_Recurringprofile_Edit extends ParadoxLabs_TokenBase_Block_Recurringprofile_Info
{
	protected $_address = null;
	
	public function getAddress()
	{
		if( is_null( $this->_address ) ) {
			$this->_address = Mage::getModel('sales/quote_address');
			
			if( !is_array( $this->getProfile()->getShippingAddressInfo() ) ) {
				$shippingAddr = unserialize( $this->getProfile()->getShippingAddressInfo() );
			}
			else {
				$shippingAddr = $this->getProfile()->getShippingAddressInfo();
			}
			
			$this->_address->setData( $shippingAddr );
		}
		
		return $this->_address;
	}
}
