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

class ParadoxLabs_TokenBase_Block_Override_Sales_Adminhtml_Recurring_Profile_View extends Mage_Sales_Block_Adminhtml_Recurring_Profile_View
{
	/**
	 * Add 'edit' button to recurring profiles (admin)
	 */
	protected function _prepareLayout()
	{
		$profile 	= Mage::registry('current_recurring_profile');
		$parent		= parent::_prepareLayout();
		
		// Show the edit button if the subscription is active
		if( $profile->canSuspend() && in_array( $profile->getMethodCode(), Mage::helper('tokenbase')->getActiveMethods() ) ) {
			$this->_addButton('edit', array(
				'label'		=> Mage::helper('sales')->__('Modify Recurring Profile'),
				'onclick'	=> "setLocation('" . $this->getUrl( '*/customer_recurringprofile/edit', array( 'profile' => $profile->getId() ) ) . "')",
				'class'		=> '',
			));
		}
		
		return $parent;
	}
}
