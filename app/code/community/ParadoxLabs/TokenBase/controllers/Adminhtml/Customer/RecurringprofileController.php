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

class ParadoxLabs_TokenBase_Adminhtml_Customer_RecurringprofileController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Change RP addresses et al.: Form.
	 */
	public function editAction() {
		$profile	= Mage::getModel('sales/recurring_profile')->load( $this->getRequest()->getParam('profile') );
		$customer	= Mage::getmodel('customer/customer')->load( $profile->getCustomerId() );
		
		if( $profile && $customer && $profile->getCustomerId() == $customer->getId() ) {
			Mage::register( 'current_recurring_profile', $profile );
			
			if( $profile->getShippingAddressInfo() != array() ) {
				$origAddr	= Mage::getModel('sales/quote_address')->load( $profile->getInfoValue( 'shipping_address_info', 'address_id' ) );
				Mage::register('current_address', $origAddr);
			}
			
			$this->loadLayout();
			$this->_title( sprintf( 'Recurring Profile %s', $profile->getReferenceId() ) )
				 ->_title('Modify Recurring Profile');
			$this->renderLayout();
		}
		else {
			$this->_redirect( '*/sales_recurring_profile/view', array( 'profile' => $this->getRequest()->getParam('profile') ) );
		}
	}
	
	/**
	 * Change RP addresses: Form submit.
	 */
	public function editPostAction() {
		$profile	= Mage::getModel('sales/recurring_profile')->load( $this->getRequest()->getParam('profile') );
		$customer	= Mage::getmodel('customer/customer')->load( $profile->getCustomerId() );
		
		if( $profile && $customer && $profile->getCustomerId() == $customer->getId() ) {
			Mage::register( 'current_recurring_profile', $profile );
			
			try {
				$input	= new Varien_Object( Mage::app()->getRequest()->getPost() );
				
				Mage::helper('tokenbase/recurringProfile')->processEdit( $profile, $input );
				
				Mage::getSingleton('adminhtml/session')->addSuccess( $this->__('Updated recurring profile settings.') );
				
				$this->_redirect( '*/sales_recurring_profile/view', array( 'profile' => $this->getRequest()->getParam('profile') ) );
			}
			catch( Exception $e ) {
				Mage::getSingleton('adminhtml/session')->addError( $e->getMessage() );
				
				$this->_redirect( '*/*/edit', array( 'profile' => $this->getRequest()->getParam('profile') ) );
			}
		}
	}
	
	/**
	 * Check ACP perms
	 */
	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('sales/recurring_profile');
	}
}
