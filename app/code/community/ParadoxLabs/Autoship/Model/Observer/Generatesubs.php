<?php

class ParadoxLabs_Autoship_Model_Observer_Generatesubs extends Mage_Catalog_Model_Observer
{
	/**
	 * When an order is placed, check all items for autoship.
	 * Generate a recurring profile for each one we find.
	 */
	public function generateSubscriptionsOnOrder( $observer ) {
		$order		= $observer->getOrder();
		$orderQuote	= $observer->getQuote();
		$items		= $order->getAllItems();
		$adtl		= $order->getPayment()->getAdditionalInformation();
		
		try {
			foreach( $items as $item ) {
				if( $item->getSubscriptionPeriod() > 0 && $item->getIsSubscription() == 0 && is_null( $item->getParentItem() ) ) {
					Mage::log( 'processing item '.$item->getId(), null, 'paradoxlabs-autoship.log' );
					
					/**
					 * Create a quote from the order and this individual item.
					 * This is stored with the recurring profile and used for who-knows-what.
					 */
					$quote	= Mage::getModel('sales/quote')
									->setStoreId( $order->getStoreId() )
									->setIsMultiShipping( false )
									->setIsActive( false )
									->setRemoteIp( $orderQuote->getRemoteIp() )
									->assignCustomer( Mage::getModel('customer/customer')->load( $order->getCustomerId() ) );
					
					$quote->getBillingAddress()->importOrderAddress( $order->getBillingAddress() );
					$quote->getShippingAddress()->importOrderAddress( $order->getShippingAddress() );
					
					$quote->setPayment( $orderQuote->getPayment() );
					$quote->setIsVirtual( $quote->getIsVirtual() );
					
					$product = Mage::getModel('catalog/product')
										->setStoreId( $order->getStoreId() )
										->load( $item->getProductId() );
					
					if( !$product->getId() ) {
						Mage::log( 'Could not load product for item '.$item->getId(), null, 'paradoxlabs-autoship.log' );
						continue;
					}
					
					if( $product->getData('allow_autoship') != 1 ) {
						Mage::log( 'Autoship is not enabled for item '.$item->getId(), null, 'paradoxlabs-autoship.log' );
						continue;
					}
					
					$info = $item->getProductOptionByCode('info_buyRequest');
					$info = new Varien_Object( $info );
					$info->setQty( $item->getQtyOrdered() );
					
					$quote->addProduct( $product, $info );
					
					$quoteItem = $quote->getItemsCollection()->getFirstItem();
					$quoteItem->setSubscriptionPeriod( $item->getSubscriptionPeriod() )
							  ->setIsSubscription( 1 );
					
					$quoteItem->setOriginalCustomPrice( $item->getPrice() );
					
					$quote->getShippingAddress()->setShippingMethod( 'flatrate_flatrate' )
												->setShippingDescription( Mage::getStoreConfig('carriers/flatrate/name') );
					
					$quote->collectTotals();
					$quote->save();
					
					/**
					 * Generate a recurring profile for this individual item with the information on hand.
					 */
					
					$paymentInfo = array(
						'last_bill'		=> strtotime( $order->getCreatedAt() ),
						'next_cycle'	=> strtotime( sprintf( '+%s day', $item->getSubscriptionPeriod() ) ),
						'billed_count'	=> 1,
						'failure_count'	=> 0,
						'profile_id'	=> $adtl['profile_id'],
						'payment_id'	=> $adtl['payment_id'],
						'outstanding'	=> 0,
						'init_paid'		=> 1,
						'in_trial'		=> 0,
						'billing_log'	=> array(
							array(
								'date'		=> strtotime( $order->getCreatedAt() ),
								'amount'	=> $quote->getBaseGrandTotal(),
								'success'	=> 1
							)
						),
					);
					
					if( $order->getPayment()->getTokenbaseId() ) {
						$paymentInfo['tokenbase_id'] = $order->getPayment()->getTokenbaseId();
					}
					
					$orderInfo		= $quote->getData();
					$orderItemInfo	= $quote->getItemsCollection()->getFirstItem()->toArray();
					$billingAddr	= $quote->getBillingAddress()->getData();
					$shippingAddr	= $quote->getShippingAddress()->getData();
					
					Mage::helper('autoship')->cleanupArray( $orderInfo );
					Mage::helper('autoship')->cleanupArray( $orderItemInfo );
					Mage::helper('autoship')->cleanupArray( $billingAddr );
					Mage::helper('autoship')->cleanupArray( $shippingAddr );
					
					$data = array(
						'state'						=> 'active',
						'customer_id'				=> $order->getCustomerId(),
						'store_id'					=> $order->getStoreId(),
						'method_code'				=> 'authnetcim',
						'created_at'				=> strtotime( $order->getCreatedAt() ),
						'updated_at'				=> strtotime( $order->getUpdatedAt() ),
						'reference_id'				=> 1703920 + $quote->getId(),
						'subscriber_name'			=> $order->getCustomerName(),
						'start_datetime'			=> strtotime( $order->getCreatedAt() ),
						'internal_reference_id'		=> Mage::helper('core')->uniqHash( $quote->getId() . '-' ),
						'schedule_description'		=> sprintf( '%s - Every %s Days', $item->getName(), $item->getSubscriptionPeriod() ),
						'suspension_threshold'		=> 1,
						'bill_failed_later'			=> 0,
						'period_unit'				=> 'day',
						'period_frequency'			=> $item->getSubscriptionPeriod(),
						'period_max_cycles'			=> null,
						'billing_amount'			=> $quote->getBaseSubtotal(),
						'trial_period_unit'			=> null,
						'trial_period_frequency'	=> null,
						'trial_period_max_cycles'	=> null,
						'trial_billing_amount'		=> null,
						'currency_code'				=> 'USD',
						'shipping_amount'			=> floatval( Mage::getStoreConfig('paradoxlabs_autoship/autoship/shipping') ),
						'tax_amount'				=> $quote->getShippingAddress()->getBaseTaxAmount(),
						'init_amount'				=> null,
						'init_may_fail'				=> null,
						'order_info'				=> serialize( $orderInfo ),
						'order_item_info'			=> serialize( $orderItemInfo ),
						'billing_address_info'		=> serialize( $billingAddr ),
						'shipping_address_info'		=> serialize( $shippingAddr ),
						'profile_vendor_info'		=> null,
						'additional_info'			=> serialize( $paymentInfo ),
					);
					
					$rp = Mage::getModel('sales/recurring_profile');
					$rp->setData( $data );
					$rp->save();
					$rp->addOrderRelation( $order->getId() );
					
					Mage::log( sprintf( 'Generated %s day profile for item %s (SKU %s)', $item->getSubscriptionPeriod(), $item->getId(), $item->getSku() ), null, 'paradoxlabs-autoship.log' );
				}
			}
		}
		catch( Exception $e ) {
			Mage::log( (string)$e, null, 'paradoxlabs-autoship.log', true );
			throw $e;
		}
	}
}
