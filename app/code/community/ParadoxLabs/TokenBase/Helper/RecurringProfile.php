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

class ParadoxLabs_TokenBase_Helper_RecurringProfile extends ParadoxLabs_TokenBase_Helper_Data
{
	/**
	 * Process the given recurring profile for billing.
	 */
	public function bill( Mage_Payment_Model_Recurring_Profile $profile )
	{
		Mage::dispatchEvent( 'recurring_profile_before_billing', array( 'profile' => $profile ) );
		
		$adtl			= $profile->getAdditionalInfo();
		$customerId		= $profile->getCustomerId();
		$customer 		= Mage::getModel('customer/customer');
		
		$card			= Mage::getModel('tokenbase/card');
		
		if( is_string( $adtl ) ) {
			$adtl = unserialize( $adtl );
		}
		
		/**
		 * Try to load payment card
		 */
		if( isset( $adtl['tokenbase_id'] ) ) {
			$card->load( $adtl['tokenbase_id'] );
		}
		
		if( !is_null( $customerId ) ) {
			$customer->load( $customerId );
		}
		
		/**
		 * No card means no billing. Err out.
		 */
		if( !$card || $card->getId() < 1 ) {
			Mage::helper('tokenbase')->log( $profile->getMethodCode(), Mage::helper('tokenbase')->__( 'Recurring profile %s: Could not find payment ID; unable to bill.', $profile->getReferenceId() ) );
			Mage::throwException( Mage::helper('tokenbase')->__( 'Recurring profile %s: Could not find payment ID; unable to bill.', $profile->getReferenceId() ) );
		}
		
		/**
		 * Load the payment method instance and make sure all appears well (active, etc.).
		 */
		$method			= $card->getMethodInstance();
		$method->setStore( $profile->getStoreId() );
		
		if( $method->getConfigData( 'active' ) == 0 || ( !is_null( $customerId ) && $customer->getId() != $customerId ) ) {
			return $profile;
		}
		
		/**
		 * Serious business now. Calculate the scheduling and billing amount, if any.
		 */
		$amount			= 0;
		
		$trialCycles	= intval( $profile->getTrialPeriodMaxCycles() );
		$totalCycles	= $trialCycles + intval( $profile->getPeriodMaxCycles() );
		
		/**
		 * Initial charge to be billed?
		 */
		if( $profile->getInitAmount() > 0 && $adtl['billed_count'] < 1 ) {
			$amount += floatval( $profile->getInitAmount() );
		}
		
		/**
		 * Within scheduled billing period?
		 */
		if( isset( $adtl['next_cycle'] ) && $adtl['next_cycle'] <= time() ) {
			/**
			 * Are we in the trial period?
			 */
			if( isset( $adtl['in_trial'] ) && $adtl['in_trial'] ) {
				if( $trialCycles <= $adtl['billed_count'] + 1 ) {
					$adtl['in_trial'] = false;
				}
				
				$amount	+= floatval( $profile->getTrialBillingAmount() );
			}
			elseif( $adtl['billed_count'] < $totalCycles || $totalCycles == $trialCycles ) {
				$amount	+= floatval( $profile->getBillingAmount() );
			}
			
			/**
			 * Is there an outstanding bill?
			 */
			if( isset( $adtl['outstanding'] ) && $adtl['outstanding'] > 0 ) {
				$amount += floatval( $adtl['outstanding'] );
			}
			
			if( $profile->getState() == 'pending' ) {
				$profile->setState( Mage_Sales_Model_Recurring_Profile::STATE_ACTIVE );
			}
			
			/**
			 * Calculate the next billing cycle.
			 */
			if( $adtl['in_trial'] ) {
				$frequency	= $profile->getTrialPeriodFrequency();
				$unit		= $profile->getTrialPeriodUnit();
			}
			else {
				$frequency	= $profile->getPeriodFrequency();
				$unit		= $profile->getPeriodUnit();
			}
			
			if( $unit == 'semi_month' ) {
				$unit		= 'weeks';
				$frequency	= $frequency * 2;
			}
			
			$adtl['next_cycle'] = strtotime( sprintf( '+%d %s', $frequency, $unit ) );
		}
		
		/**
		 * Do we need to bill? If so, do it.
		 */
		if( $amount > 0 ) {
			/**
			 * Try to generate an order and invoice.
			 */
			try {
				Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Processing RP %s (%s)', $profile->getReferenceId(), Mage::helper('core')->currency( $amount, true, false ) ) );
				
				$productItemInfo = new Varien_Object();
				
				if( $adtl['billed_count'] < $trialCycles ) {
					$productItemInfo->setPaymentType( Mage_Sales_Model_Recurring_Profile::PAYMENT_TYPE_TRIAL );
				}
				else {
					$productItemInfo->setPaymentType( Mage_Sales_Model_Recurring_Profile::PAYMENT_TYPE_REGULAR );
				}
				
				$productItemInfo->setTaxAmount( $profile->getTaxAmount() );
				$productItemInfo->setShippingAmount( $profile->getShippingAmount() );
				$productItemInfo->setPrice( $amount );
				
				$order = $profile->createOrder( $productItemInfo );
				
				if( is_null( $customerId ) ) {
					$order->setCustomerId( null );
				}
				
				$paymentData = new Varien_Object( array( 'card_id' => $card->getHash(), 'method' => $profile->getMethodCode() ) );
				
				$order->getPayment()->getMethodInstance()->setCustomer( $customer );
				$order->getPayment()->getMethodInstance()->assignData( $paymentData );
				
				/**
				 * Place the order
				 */
				$transaction = Mage::getModel('core/resource_transaction');
				
				if( $customer && $customer->getId() ) {
					$transaction->addObject( $customer );
				}
				
				$transaction->addObject( $order );
				$transaction->addCommitCallback( array( $order, 'place' ) );
				$transaction->addCommitCallback( array( $order, 'save' ) );
				$transaction->save();
				
				$profile->addOrderRelation( $order->getId() );
				
				/**
				 * Send new order email only if this is not during normal checkout processing.
				 * If the billed count is greater than 0, not checkout.
				 * If the start date is greater than the created date, not checkout.
				 */
				if( $order->getCanSendNewEmailFlag() && ( $adtl['billed_count'] > 0 || ( $profile->getCreatedAt() != '' && strtotime( $profile->getStartDatetime() ) > strtotime( $profile->getCreatedAt() ) ) ) ) {
					try {
						$order->sendNewOrderEmail();
					}
					catch(Exception $e) {
						Mage::logException($e->getMessage());
					}
				}
				
				$adtl['outstanding'] = 0;
				$adtl['billed_count']++;
				
				$profile->setHasBilled( true );
				
				Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( "Recurring profile #%s billed for %s.", $profile->getReferenceId(), Mage::helper('core')->currency( $order->getGrandTotal(), true, false ) ) );
				
				Mage::dispatchEvent( 'recurring_profile_billed', array( 'order' => $order, 'profile' => $profile ) );
				
				/**
				 * Is the profile complete?
				 */
				if( intval( $profile->getPeriodMaxCycles() ) > 0 && $adtl['billed_count'] >= $totalCycles ) {
					$profile->setState( Mage_Sales_Model_Recurring_Profile::STATE_EXPIRED );
				}
			}
			catch(Mage_Core_Exception $e) {
				/**
				 * Payment failed; handle the error.
				 */
				$adtl['failure_count']++;
				
				// Reset the order IDs on exception; we do not want it to save.
				if( isset( $order ) ) {
					$order->setId(null);
					foreach ($order->getItemsCollection() as $item) {
						$item->setOrderId(null);
						$item->setItemId(null);
					}
				}
				
				Mage::dispatchEvent( 'recurring_profile_failed', array( 'profile' => $profile, 'reason' => $e->getMessage() ) );
				
				Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Recurring profile billing error: %s', $e->getMessage() ) );
				
				$suspensionThreshold	= intval( $profile->getSuspensionThreshold() );
				
				if( $suspensionThreshold > 0 && $adtl['failure_count'] >= $suspensionThreshold ) {
					$profile->setState( Mage_Sales_Model_Recurring_Profile::STATE_SUSPENDED );
					
					Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Recurring profile #%s failed on payment (%s/%s); profile suspended.', $profile->getReferenceId(), $adtl['failure_count'], $suspensionThreshold ) );
				}
				else {
					if( $profile->getBillFailedLater() ) {
						$adtl['billed_count']++;
						$adtl['outstanding'] = $amount;
					}
					
					Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Recurring profile #%s failed on payment (%s/%s).', $profile->getReferenceId(), $adtl['failure_count'], $suspensionThreshold ) );
				}
				
				$adtl['billing_log'][]	= array(
					'date'		=> time(),
					'amount'	=> round( $amount + $profile->getTaxAmount() + $profile->getShippingAmount(), 4 ),
					'success'	=> false,
				);
				
				$profile->setAdditionalInfo( $adtl )
						->save();
				
				Mage::logException( $e );
				
				throw $e;
			}
			catch( Exception $e ) {
				Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Recurring profile #%s failed: %s', $profile->getReferenceId(), $e->getMessage() ) );
				
				throw $e;
			}
			
			/**
			 * Log the billing sequence.
			 */
			$adtl['last_bill']		= time();
			$adtl['init_paid']		= true;
			
			$adtl['billing_log'][]	= array(
				'date'		=> time(),
				'amount'	=> round( $amount + $profile->getTaxAmount() + $profile->getShippingAmount(), 4 ),
				'success'	=> true,
			);
			
			$profile->setAdditionalInfo( $adtl )
					->save();
			
			Mage::dispatchEvent( 'recurring_profile_after_billing', array( 'profile' => $profile ) );
		}
		
		return $profile;
	}
	
	/**
	 * Take form inputs and use them to modify the given recurring profile.
	 */
	public function processEdit( Mage_Payment_Model_Recurring_Profile $profile, Varien_Object $input )
	{
		$customer = Mage::helper('tokenbase')->getCurrentCustomer();
		
		if( $profile->getShippingAddressInfo() != array() ) {
			$origAddr = Mage::getModel('sales/quote_address');
			
			if( !is_array( $profile->getShippingAddressInfo() ) ) {
				$shippingAddr = unserialize( $profile->getShippingAddressInfo() );
			}
			else {
				$shippingAddr = $profile->getShippingAddressInfo();
			}
			
			$origAddr->setData( $shippingAddr );
			
			$newAddrId	= intval( $input->getData('shipping_address_id') );
			
			/**
			 * Has the address changed?
			 */
			if( $origAddr && $newAddrId != $origAddr->getCustomerAddressId() ) {
				/**
				 * New address or existing?
				 * 
				 * If new:
				 * - store as customer address
				 * - convert to quote address
				 * - add to profile
				 * 
				 * If existing:
				 * - convert to quote address
				 * - add to profile
				 */
				
				/**
				 * Existing address
				 */
				if( $newAddrId > 0 ) {
					$newAddr = Mage::getModel('customer/address')->load( $newAddrId );
					
					if( !$customer || $newAddr->getCustomerId() != $customer->getId() ) {
						Mage::throwException( $this->__('An error occurred. Please try again.') );
					}
				}
				/**
				 * New address
				 */
				else {
					$newAddr = Mage::getModel('customer/address');
					$newAddr->setCustomerId( $customer->getId() );
					
					$data = $input->getData('shipping');
					
					$addressForm = Mage::getModel('customer/form');
					$addressForm->setFormCode('customer_address_edit');
					$addressForm->setEntity( $newAddr );
					
					$addressData    = $addressForm->extractData( $addressForm->prepareRequest( $data ) );
					$addressErrors  = $addressForm->validateData( $addressData );
					
					if( $addressErrors !== true ) {
						Mage::throwException( implode( ' ', $addressErrors ) );
					}
					
					$addressForm->compactData( $addressData );
					$addressErrors = $newAddr->validate();
					
					$newAddr->setSaveInAddressBook( true );
					$newAddr->implodeStreetAddress();
					$newAddr->save();
				}
				
				/**
				 * Update the shipping address on our record
				 */
				$origAddr->importCustomerAddress( $newAddr );
				
				$shippingAddr = $origAddr->getData();
				$this->cleanupArray( $shippingAddr );
				$profile->setShippingAddressInfo( $shippingAddr );
			}
		}
		
		/**
		 * Has the payment card changed?
		 */
		$tokenbaseId = intval( $input->getData('tokenbase_id') );
		if( $tokenbaseId > 0 && $tokenbaseId != $profile->getInfoValue('additional_info', 'tokenbase_id') ) {
			$card = Mage::getModel('tokenbase/card')->load( $tokenbaseId );
			
			if( $card && $card->getId() == $tokenbaseId && ( $customer->getId() == 0 || $card->hasOwner( $customer->getId() ) ) ) {
				$adtl = $profile->getAdditionalInfo();
				$adtl['tokenbase_id'] = $tokenbaseId;
				
				/**
				 * Update billing address to match the card
				 */
				$billingAddr	= $profile->getBillingAddressInfo();
				
				$copyKeys		= array( 'street', 'firstname', 'lastname', 'city', 'region', 'region_id', 'postcode', 'country_id', 'telephone', 'fax' );
				foreach( $copyKeys as $key ) {
					$billingAddr[ $key ] = $card->getAddress( $key );
				}
				
				$profile->setBillingAddressInfo( $billingAddr );
				$profile->setAdditionalInfo( $adtl );
				
				Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Changed tokenbase ID for RP #%s to %s', $profile->getReferenceId(), $adtl['tokenbase_id'] ) );
			}
			else {
				Mage::throwException( $this->__('Payment record not found. Please try again.') );
			}
		}
		
		/**
		 * Has the next billing date changed?
		 */
		$nextBilled = Mage::getModel('core/date')->gmtTimestamp( $input->getData('next_billed') );
		if( $input->getData('next_billed') != '' && $nextBilled > 0 && $nextBilled != $profile->getInfoValue('additional_info', 'next_cycle') ) {
			$adtl = $profile->getAdditionalInfo();
			$adtl['next_cycle'] = $nextBilled;
			
			$profile->setAdditionalInfo( $adtl );
			
			Mage::helper('tokenbase')->log( $profile->getMethodCode(), sprintf( 'Changed next billing cycle for RP #%s to %s', $profile->getReferenceId(), date( 'j-F Y h:i', Mage::getModel('core/date')->timestamp( $adtl['next_cycle'] ) ) ) );
		}
		
		Mage::dispatchEvent( 'tokenbase_recurringprofile_edit_before_save', array( 'profile' => $profile, 'input' => $input ) );
		
		$profile->save();
		
		return $profile;
	}
}
