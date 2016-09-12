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

class ParadoxLabs_TokenBase_Customer_RecurringprofileController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Ensure customers log in/register before getting to this controller.
	 */
	public function preDispatch()
	{
		parent::preDispatch();
		
		if( !Mage::getSingleton('customer/session')->authenticate($this) ) {
			$this->getResponse()->setRedirect( Mage::helper('customer')->getLoginUrl() );
			$this->setFlag( '', self::FLAG_NO_DISPATCH, true );
		}
		elseif( count( Mage::helper('tokenbase')->getActiveMethods() ) == 0 ) {
			$this->getResponse()->setRedirect( Mage::helper('customer')->getAccountUrl() );
			$this->setFlag( '', self::FLAG_NO_DISPATCH, true );
		}
		
		return $this;
	}
	
	/**
	 * Change RP addresses et al.: Form.
	 */
	public function editAction()
	{
		$profile	= Mage::getModel('sales/recurring_profile')->load( $this->getRequest()->getParam('profile') );
		$customer	= Mage::helper('tokenbase')->getCurrentCustomer();
		
		if( $profile && $customer && $profile->getCustomerId() == $customer->getId() ) {
			Mage::register( 'current_recurring_profile', $profile );
			
			if( $profile->getShippingAddressInfo() != array() ) {
				$origAddr	= Mage::getModel('sales/quote_address')->load( $profile->getInfoValue('shipping_address_info', 'address_id') );
				Mage::register('current_address', $origAddr);
			}
			
			$this->loadLayout();
			$this->_title()
				 ->_title( sprintf( 'Recurring Profile %s', $profile->getReferenceId() ) )
				 ->_title('Modify Recurring Profile');
			$this->renderLayout();
		}
		else {
			$this->_redirect('sales/recurring_profile');
		}
	}
	
	/**
	 * Change RP addresses: Form submit.
	 */
	public function editPostAction()
	{
		$profile	= Mage::getModel('sales/recurring_profile')->load( $this->getRequest()->getParam('profile') );
		$customer	= Mage::helper('tokenbase')->getCurrentCustomer();
		
		if( $profile && $customer && $profile->getCustomerId() == $customer->getId() ) {
			Mage::register( 'current_recurring_profile', $profile );
			
			try {
				$input	= new Varien_Object( Mage::app()->getRequest()->getPost() );
				
				Mage::helper('tokenbase/recurringProfile')->processEdit( $profile, $input );
				
				Mage::getSingleton('core/session')->addSuccess( $this->__('Updated your recurring profile settings.') );
				
				$this->_redirect( 'sales/recurring_profile/view', array( 'profile' => $this->getRequest()->getParam('profile') ) );
			}
			catch( Exception $e ) {
				Mage::getSingleton('core/session')->addError( $e->getMessage() );
				
				$this->_redirect( '*/*/edit', array( 'profile' => $this->getRequest()->getParam('profile') ) );
			}
		}
		else {
			$this->_redirect('sales/recurring_profile');
		}
	}
}
