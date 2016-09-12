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

class ParadoxLabs_TokenBase_Model_Observer_RecurringProfile
{
	/**
	 * When a recurring profile is saved, check if this is during
	 * a registration checkout. If so, save the customer too during
	 * the transaction. It's not clear why this is a problem in need
	 * of solving...
	 */
	public function saveCustomer( $observer )
	{
		$profile	= $observer->getEvent()->getProfile();
		
		if( !in_array( $profile->getMethodCode(), Mage::helper('tokenbase')->getActiveMethods() ) ) {
			return $this;
		}
		
		if( $profile->getId() < 1 && $profile->hasQuote() && $profile->getCustomerId() === true ) {
			/**
			 * When customer is 'true', save the customer, then propagate the ID.
			 */
			$customer = $profile->getQuote()->getCustomer();
			$customer->save();
			
			Mage::register( 'current_customer', $customer, true );
			
			$profile->setCustomerId( $customer->getId() );
			$profile->getQuote()->setCustomer( $customer );
			$profile->getQuote()->getBillingAddress()->setCustomerId( $customer->getId() );
			
			$orderInfo = $profile->getOrderInfo();
			if( is_array( $orderInfo ) ) {
				$orderInfo['customer_id'] = $customer->getId();
				$profile->setOrderInfo( $orderInfo );
			}
			
			$billingAddressInfo = $profile->getBillingAddressInfo();
			if( is_array( $billingAddressInfo ) ) {
				$billingAddressInfo['customer_id'] = $customer->getId();
				$profile->setBillingAddressInfo( $billingAddressInfo );
			}
			
			if( !$profile->getQuote()->isVirtual() ) {
				$profile->getQuote()->getShippingAddress()->setCustomerId( $customer->getId() );
				
				$shippingAddressInfo = $profile->getShippingAddressInfo();
				if( is_array( $shippingAddressInfo ) ) {
					$shippingAddressInfo['customer_id'] = $customer->getId();
					$profile->setShippingAddressInfo( $shippingAddressInfo );
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * Undo assignments if necessary: Needed for registering-checkout failures.
	 */
	public function rollbackCustomer( $observer )
	{
		$profile = $observer->getEvent()->getProfile();
		
		if( !in_array( $profile->getMethodCode(), Mage::helper('tokenbase')->getActiveMethods() ) ) {
			return $this;
		}
		
		Mage::unregister( 'current_customer' );
		
		$profile->setCustomerId( null );
		
		$quote = $profile->getQuote();
		
		$quote->setCustomerId( null );
		$quote->getBillingAddress()->setCustomerId( null );
		
		if( !$quote->isVirtual() ) {
			$quote->getShippingAddress()->setCustomerId( null );
		}
		
		$quote->save();
		
		return $this;
	}
	
	/**
	 * When a card is saved, update billing address on any associated profiles.
	 */
	public function updateBillingAddresses( $observer )
	{
		$card = $observer->getEvent()->getObject();
		
		if( $card instanceof ParadoxLabs_TokenBase_Model_Card && $card->getId() > 0 ) {
			/**
			 * Load any profiles that might be related; we'll check each one.
			 */
			$profiles	= Mage::getModel('sales/recurring_profile')->getCollection()
								->addFieldToFilter( 'method_code', $card->getMethod() )
								->addFieldToFilter( 'additional_info', array( 'like' => '%' . $card->getId() . '%' ) );
			
			$count		= 0;
			foreach( $profiles as $profile ) {
				$profile	= Mage::getModel('sales/recurring_profile')->loadByInternalReferenceId( $profile->getInternalReferenceId() );
				$adtl		= $profile->getAdditionalInfo();
				
				if( $adtl['tokenbase_id'] == $card->getId() ) {
					/**
					 * Update billing address to match the card
					 */
					$billingAddr	= $profile->getBillingAddressInfo();
					$changes		= false;
					
					$copyKeys		= array( 'street', 'firstname', 'lastname', 'city', 'region', 'region_id', 'postcode', 'country_id', 'telephone', 'fax' );
					foreach( $copyKeys as $key ) {
						if( $billingAddr[ $key ] != $card->getAddress( $key ) ) {
							$changes				= true;
							$billingAddr[ $key ]	= $card->getAddress( $key );
						}
					}
					
					if( $changes === true ) {
						$profile->setBillingAddressInfo( $billingAddr );
						$profile->save();
						
						$count++;
					}
				}
			}
			
			if( $count > 0 ) {
				Mage::helper('tokenbase')->log( $card->getMethod(), sprintf( 'Updated billing address for %s RPs on card edit (%s)', $count, $card->getId() ) );
			}
		}
		
		return $this;
	}
}
