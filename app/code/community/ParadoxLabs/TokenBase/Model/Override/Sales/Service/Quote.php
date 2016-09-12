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

class ParadoxLabs_TokenBase_Model_Override_Sales_Service_Quote extends Mage_Sales_Model_Service_Quote
{
	/**
	 * Simple function overload to support admin creation
	 * of recurring profile/nominal item orders.
	 *
	 * submit() is deprecated, but they did it wrong.
	 *
	 * arg.
	 */
	
	/**
	 * @deprecated after 1.4.0.1
	 * @see submitOrder()
	 * @see submitAll()
	 */
	public function submit()
	{
		// return $this->submitOrder();
		$this->submitAll();
		
		/**
		 * Not sure how this is supposed to work with multiple recurring profiles...
		 * but there can only be one profile in an order right now regardless.
		 */
		$profiles = $this->getRecurringPaymentProfiles();
		if( $this->_quote->getIsActive() === false && count($profiles) > 0 ) {
			// Trigger RP fetch so we have an order to return.
			$this->getOrder();
		}

		return $this->_order;
	}
	
	/**
	 * Handle order properly for recurring profiles... why does Magento not do this?
	 */
    public function getOrder()
    {
    	if( isset($this->_order) ) {
    		return $this->_order;
    	}
    	
		$profiles = $this->getRecurringPaymentProfiles();
		if( count($profiles) > 0 ) {
			foreach( $profiles as $profile ) {
				$orders = $profile->getChildOrderIds();
				if( count( $orders ) > 0 ) {
					$order = array_pop( $orders );
					
					if( $order > 0 ) {
						$this->_order = Mage::getModel('sales/order')->load( $order );
					}
				}
			}
		}
		
        return $this->_order;
    }
}
