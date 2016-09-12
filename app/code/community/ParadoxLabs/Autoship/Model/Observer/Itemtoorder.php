<?php

class ParadoxLabs_Autoship_Model_Observer_Itemtoorder extends Mage_Catalog_Model_Observer
{
	public function addItemToCart( $observer ) {
		/**
		 * Set subscription period when adding to cart
		 */
		$item 	= $observer->getQuoteItem();
		
		$period	= intval( Mage::app()->getRequest()->getParam('subscription_period') );
		
		if( !in_array( $period, Mage::helper('autoship')->getSubscriptionPeriodNumbers() ) ) {
			$period	= 0;
		}
		
		$item->setSubscriptionPeriod( $period )
			 ->setIsSubscription( 0 );
	}
	
	/**
	 * Update when modifying cart
	 */
	public function updateItemInCart( $observer ) {
		$request = Mage::app()->getRequest()->getParam('cart');
		
		foreach($observer->getCart()->getQuote()->getAllVisibleItems() as $item ) {
			if( $item->getParentItem() ) {
				$item = $item->getParentItem();
			}
			
			if( isset( $request[ $item->getId() ] ) && isset( $request[ $item->getId() ]['subscription_period'] ) ) {
				$item->setSubscriptionPeriod( intval( $request[ $item->getId() ]['subscription_period'] ) )
					 ->setIsSubscription( 0 );
			}
		}
	}
	
	/**
	 * Convert custom attributes when creating an order.
	 */
	public function convertQuoteToOrder( $observer ) {
		$quote = $observer->getQuote();
		$order = $observer->getOrder();
		
		// Copy options from quote into order, as in Mage_Adminhtml_Model_Sales_Order_Create::_prepareQuoteItems().
		// Why doesn't Magento do this itself?...
		foreach($quote->getAllItems() as $item) {
			$orderItem = $order->getItemByQuoteItemId( $item->getItemId() );
			
			if (!$orderItem) {
				continue;
			}
			
			$options = $orderItem->getProductOptions();
			$productOptions = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
			if ($productOptions) {
				$productOptions['info_buyRequest']['options'] = $this->_prepareOptionsForRequest($item);
				$options = $productOptions;
			}
			$adtl = $item->getOptionByCode('additional_options');
			if( $adtl ) {
				$options['additional_options'] = unserialize($adtl->getValue());
			}
			$orderItem->setProductOptions( $options );
			
			$orderItem->setSubscriptionPeriod( $item->getSubscriptionPeriod() )
					  ->setIsSubscription( $item->getIsSubscription() );
			
			if( $order->getId() ) {
				$orderItem->save();
			}
		}
		
		$order->save();
		
		return $this;
	}
	
	
	
	/**
	 * Prepare options array for info buy request
	 *
	 * @param Mage_Sales_Model_Quote_Item $item
	 * @return array
	 */
	protected function _prepareOptionsForRequest($item)
	{
		$newInfoOptions = array();
		if ($optionIds = $item->getOptionByCode('option_ids')) {
			foreach (explode(',', $optionIds->getValue()) as $optionId) {
				$option = $item->getProduct()->getOptionById($optionId);
				$optionValue = $item->getOptionByCode('option_'.$optionId)->getValue();
				
				$group = Mage::getSingleton('catalog/product_option')->groupFactory($option->getType())
					->setOption($option)
					->setQuoteItem($item);
				
				$newInfoOptions[$optionId] = $group->prepareOptionValueForRequest($optionValue);
			}
		}
		return $newInfoOptions;
	}
}
