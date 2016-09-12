<?php
/**
 * Sales order api
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */

class Novus_Ecommhub_Model_Sales_Order_Api extends Mage_Sales_Model_Order_Api
{
	 /**
	 * Retrieve list of orders by filters
	 *
	 * @param array $filters
	 * @return array
	 */
	public function ecommhubitems($pagesize, $pagenumber, $filters = null)
	{
		//TODO: add full name logic
		$billingAliasName = 'billing_o_a';
		$shippingAliasName = 'shipping_o_a';

		$version = Mage::getVersion();

		if (true || $version >= "1.4.1.1") {
			$collection = Mage::getModel("sales/order")->getCollection()
				->addAttributeToSelect('*')
				->addAddressFields()
				->addExpressionFieldToSelect(
					'billing_firstname', "{{billing_firstname}}", array('billing_firstname'=>"$billingAliasName.firstname")
				)
				->addExpressionFieldToSelect(
					'billing_lastname', "{{billing_lastname}}", array('billing_lastname'=>"$billingAliasName.lastname")
				)
				->addExpressionFieldToSelect(
					'shipping_firstname', "{{shipping_firstname}}", array('shipping_firstname'=>"$shippingAliasName.firstname")
				)
				->addExpressionFieldToSelect(
					'shipping_lastname', "{{shipping_lastname}}", array('shipping_lastname'=>"$shippingAliasName.lastname")
				)
				->addExpressionFieldToSelect(
						'billing_name',
						"CONCAT({{billing_firstname}}, ' ', {{billing_lastname}})",
						array('billing_firstname'=>"$billingAliasName.firstname", 'billing_lastname'=>"$billingAliasName.lastname")
				)
				->addExpressionFieldToSelect(
						'shipping_name',
						'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
							array('shipping_firstname'=>"$shippingAliasName.firstname", 'shipping_lastname'=>"$shippingAliasName.lastname")
					)

				->setCurPage($pagenumber)
				->setPageSize($pagesize);

		} else {
			$collection = Mage::getResourceModel('sales/order_collection')
				->addAttributeToSelect('*')
				->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
				->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
				->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
				->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
				->addExpressionAttributeToSelect('billing_name',
					'CONCAT({{billing_firstname}}, " ", {{billing_lastname}})',
					array('billing_firstname', 'billing_lastname'))
				->addExpressionAttributeToSelect('shipping_name',
					'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
					array('shipping_firstname', 'shipping_lastname'))

				->setCurPage($pagenumber)
				->setPageSize($pagesize);

		}

		if (is_array($filters)) {
			try {
				foreach ($filters as $field => $value) {
					if (isset($this->_attributesMap['order'][$field])) {
						$field = $this->_attributesMap['order'][$field];
					}
					$collection->addFieldToFilter($field, $value);
				}
			} catch (Mage_Core_Exception $e) {
				$this->_fault('filters_invalid', $e->getMessage());
			}
		}

		$result = array();

		foreach ($collection as $order) {
			$my_order = $this->_getAttributes($order, 'order');
			$my_order['shipping_address'] = $this->_getAttributes($order->getShippingAddress(), 'order_address');
			$my_order['billing_address']  = $this->_getAttributes($order->getBillingAddress(), 'order_address');
			$my_order['payment'] = $this->_getAttributes($order->getPayment(), 'order_payment');

			$my_order['status_history'] = array();

			foreach ($order->getAllStatusHistory() as $history) {
				$my_order['status_history'][] = $this->_getAttributes($history, 'order_status_history');
			}

			$my_order['items'] = array();

			foreach ($order->getAllItems() as $item) {
				if ($item->getGiftMessageId() > 0) {
					$item->setGiftMessage( Mage::getSingleton('giftmessage/message')->load($item->getGiftMessageId())->getMessage() );
				}
				
				// to adjust with enterprise edition - stacklevel too deep error. 
				// Nullify product and parent_product inside items.
				$item_data = array();
				$item_data = $this->_getAttributes($item, 'order_item');
				$item_data['product'] = "";
				$item_data["_parent_product"] = "";
				$item_data["parent_product"] = "";
				$my_order['items'][] = $item_data; //$this->_getAttributes($item, 'order_item');
			}
			$result[] = $my_order;
		}

		return $result;

		/*$retval = array();
		$retval['list'] = $result;
		$retval['currentpage'] = $collection->getCurPage();
		$retval['lastpage'] = $collection->getLastPageNumber();
		$retval['memoryusage'] = memory_get_peak_usage();
		return json_encode($retval);*/
		//return memory_get_usage() . " " . count($result) . " " . ini_get('memory_limit') . " " . Mage::getVersion() . " " . $collection->getCurPage() . " " . $collection->getLastPageNumber();
	}

	public function ecommhubitemscount($pagesize, $pagenumber, $filters = null)
	{
		//TODO: add full name logic
		$billingAliasName = 'billing_o_a';
		$shippingAliasName = 'shipping_o_a';

		$version = Mage::getVersion();

		if (true || $version >= "1.4.1.1") {
			$collection = Mage::getModel("sales/order")->getCollection()
				->addAttributeToSelect('*')
				->addAddressFields()
				->addExpressionFieldToSelect(
					'billing_firstname', "{{billing_firstname}}", array('billing_firstname'=>"$billingAliasName.firstname")
				)
				->addExpressionFieldToSelect(
					'billing_lastname', "{{billing_lastname}}", array('billing_lastname'=>"$billingAliasName.lastname")
				)
				->addExpressionFieldToSelect(
					'shipping_firstname', "{{shipping_firstname}}", array('shipping_firstname'=>"$shippingAliasName.firstname")
				)
				->addExpressionFieldToSelect(
					'shipping_lastname', "{{shipping_lastname}}", array('shipping_lastname'=>"$shippingAliasName.lastname")
				)
				->addExpressionFieldToSelect(
						'billing_name',
						"CONCAT({{billing_firstname}}, ' ', {{billing_lastname}})",
						array('billing_firstname'=>"$billingAliasName.firstname", 'billing_lastname'=>"$billingAliasName.lastname")
				)
				->addExpressionFieldToSelect(
						'shipping_name',
						'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
							array('shipping_firstname'=>"$shippingAliasName.firstname", 'shipping_lastname'=>"$shippingAliasName.lastname")
					);

		} else {
			$collection = Mage::getResourceModel('sales/order_collection')
				->addAttributeToSelect('*')
				->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
				->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
				->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
				->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
				->addExpressionAttributeToSelect('billing_name',
					'CONCAT({{billing_firstname}}, " ", {{billing_lastname}})',
					array('billing_firstname', 'billing_lastname'))
				->addExpressionAttributeToSelect('shipping_name',
					'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
					array('shipping_firstname', 'shipping_lastname'));

		}

		if (is_array($filters)) {
			try {
				foreach ($filters as $field => $value) {
					if (isset($this->_attributesMap['order'][$field])) {
						$field = $this->_attributesMap['order'][$field];
					}
					$collection->addFieldToFilter($field, $value);
				}
			} catch (Mage_Core_Exception $e) {
				$this->_fault('filters_invalid', $e->getMessage());
			}
		}


		/* COUNT SELECT LOGIC FROM -- http://stackoverflow.com/questions/3485455/using-group-breaks-getselectcountsql-in-magento */
		$countSelect = clone $collection->getSelect();
			$countSelect->reset(Zend_Db_Select::ORDER);
			$countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
			$countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
			$countSelect->reset(Zend_Db_Select::COLUMNS);

			// Count doesn't work with group by columns keep the group by
			if(count($collection->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
				$countSelect->reset(Zend_Db_Select::GROUP);
				$countSelect->distinct(true);
				$group = $collection->getSelect()->getPart(Zend_Db_Select::GROUP);
				$countSelect->columns("COUNT(DISTINCT ".implode(", ", $group).")");
			} else {
				$countSelect->columns('COUNT(*)');
			}
		/* END COUNT SELECT LOGIC FROM -- http://stackoverflow.com/questions/3485455/using-group-breaks-getselectcountsql-in-magento */

		return $collection->getConnection()->fetchOne( $countSelect );

		//return $collection->count();

	}

}
