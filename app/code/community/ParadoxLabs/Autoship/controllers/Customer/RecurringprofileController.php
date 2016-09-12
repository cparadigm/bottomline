<?php

require_once Mage::getModuleDir('controllers', 'ParadoxLabs_TokenBase') . DS . 'Customer' . DS . 'RecurringprofileController.php';

class ParadoxLabs_Autoship_Customer_RecurringprofileController extends ParadoxLabs_TokenBase_Customer_RecurringprofileController
{
	/**
	 * Place a subscription on temporary hold (delay next billing by one period).
	 */
	public function holdAction()
	{
		$profile	= Mage::getModel('sales/recurring_profile')->load( $this->getRequest()->getParam('profile') );
		$customer	= Mage::getSingleton('customer/session')->getCustomer();
		
		if( $profile && $customer && $profile->getCustomerId() == $customer->getId() ) {
			Mage::register( 'current_profile', $profile );
			
			try {
				Mage::helper('autoship')->placeSubscriptionHold( $profile );
				
				Mage::getSingleton('core/session')->addSuccess( $this->__('Your next auto-shipment has been delayed.') );
				
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
