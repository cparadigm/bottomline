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

require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'OnepageController.php';

class ParadoxLabs_TokenBase_Override_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
	/**
	 * Default recurring profile workflow deletes the quote
	 * after ordering, to avoid processing the order by normal
	 * methods. Paypal/IPN must redirect before getting to this
	 * point, but our method does not (and cannot). So we'll
	 * repress the exception that's about to be thrown instead...
	 */
	protected function _getOrder()
	{
		if (is_null($this->_order)) {
			$this->_order = Mage::getModel('sales/order')->load( $this->getOnepage()->getQuote()->getId(), 'quote_id' );
		}
		
		return $this->_order;
	}
}
