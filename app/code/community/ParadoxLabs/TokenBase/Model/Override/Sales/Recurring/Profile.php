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

class ParadoxLabs_TokenBase_Model_Override_Sales_Recurring_Profile extends Mage_Sales_Model_Recurring_Profile
{
	/**
	 * The core class doesn't define these for specific event handling...
	 * all we want is proper observers. Is that too much to ask?
	 */
	protected $_eventPrefix = 'sales_recurring_profile';
	protected $_eventObject = 'profile';
	
	/**
	 * Submit a recurring profile right after an order is placed
	 */
	public function submit()
	{
		$this->_getResource()->beginTransaction();
		try {
			$this->setInternalReferenceId(Mage::helper('core')->uniqHash('temporary-'));
			$this->save();
			$this->setInternalReferenceId(Mage::helper('core')->uniqHash($this->getId() . '-'));
			$this->getMethodInstance()->submitRecurringProfile($this, $this->getQuote()->getPayment());
			$this->save();
			$this->_getResource()->commit();
		} catch (Exception $e) {
			$this->_getResource()->rollBack();
			
			// Add a custom rollback event...so we have it.
			Mage::dispatchEvent( $this->_eventPrefix . '_rollback', $this->_getEventData() );
			
			throw $e;
		}
	}
	
	
	
/**
 * Following: Fix for Magento core (mis)handling of custom options for recurring profiles.
 */
	
	/**
	 * Import quote item information to the profile
	 *
	 * @param Mage_Sales_Model_Quote_Item_Abstract $item
	 * @return Mage_Sales_Model_Recurring_Profile
	 */
	public function importQuoteItem(Mage_Sales_Model_Quote_Item_Abstract $item)
	{
		$result = parent::importQuoteItem( $item );
		
		/**
		 * Build and store custom options in the order_item_info data store for later use.
		 */
		$options = $item->getProduct()->getTypeInstance(true)->getOrderOptions( $item->getProduct() );
		
		if( is_null( $options ) ) {
			$options = array();
		}
		
		$orderItemInfo = $this->getOrderItemInfo();
		$orderItemInfo['options'] = serialize( $options );
		
		$this->setOrderItemInfo($orderItemInfo);
		
		return $result;
	}
	
	/**
	 * Create and return new order item based on profile item data and $itemInfo
	 * for regular payment
	 *
	 * @param Varien_Object $itemInfo
	 * @return Mage_Sales_Model_Order_Item
	 */
	protected function _getRegularItem($itemInfo)
	{
		$item       = parent::_getRegularItem( $itemInfo );
		
		/**
		 * Add custom options after parent processing, since they don't.
		 */
		$options    = $this->getInfoValue('order_item_info', 'options');
		if( is_string( $options ) ) {
			$options    = unserialize( $options );
		}
		
		$item->setProductOptions( $options );
		
		return $item;
	}
	
	/**
	 * Create and return new order item based on profile item data and $itemInfo
	 * for initial payment
	 *
	 * @param Varien_Object $itemInfo
	 * @return Mage_Sales_Model_Order_Item
	 */
	protected function _getInitialItem($itemInfo)
	{
		$item       = parent::_getInitialItem( $itemInfo );
		
		/**
		 * Add custom options after parent processing, since they don't.
		 */
		$options    = $this->getInfoValue('order_item_info', 'options');
		if( is_string( $options ) ) {
			$options    = unserialize( $options );
		}
		
		$item->setProductOptions( $options );
		
		/**
		 * Add the RP info again, since we just overwrote it.
		 */
		$option = array(
			'label' => Mage::helper('sales')->__('Payment type'),
			'value' => Mage::helper('sales')->__('Initial period payment')
		);
		
		$this->_addAdditionalOptionToItem($item, $option);
		
		return $item;
	}
}
