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

class ParadoxLabs_TokenBase_Model_Override_Authorizenet_Directpost_Observer extends Mage_Authorizenet_Model_Directpost_Observer
{
	/**
	 * Simple function overload to support admin creation
	 * of recurring profile/nominal item orders.
	 *
	 * For nominal items, $order is null, causing an exception.
	 */
	public function updateAllEditIncrements(Varien_Event_Observer $observer)
	{
		if( $observer->getEvent()->getData('order') != null ) {
			$order = $observer->getEvent()->getData('order');
			Mage::helper('authorizenet')->updateOrderEditIncrements($order);
		}
		
		return $this;
	}
}
