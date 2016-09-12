<?php
/**
 * Catalog product api
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */

class Novus_Ecommhub_Model_Catalog_Product_Api extends Mage_Catalog_Model_Product_Api
{
	/**
	 * Retrieve list of products with basic info (id, sku, type, set, name)
	 *
	 * @param array $filters
	 * @param string|int $store
	 * @return array 105,000
	 */
	public function items($pagesize, $pagenumber, $filters = null, $store = null)
	{

		$collection = Mage::getModel('catalog/product')->getCollection()
			->setFlag('require_stock_items', true)
			->setStoreId($this->_getStoreId($store))
			->addAttributeToSelect('name')
			->addAttributeToSelect('price')
			->setCurPage($pagenumber)
			->setPageSize($pagesize);

		if (is_array($filters)) {
			try {
				foreach ($filters as $field => $value) {
					if (isset($this->_filtersMap[$field])) {
						$field = $this->_filtersMap[$field];
					}
					$collection->addFieldToFilter($field, $value);
				}
			} catch (Mage_Core_Exception $e) {
				$this->_fault('filters_invalid', $e->getMessage());
			}
		}

		$result = array();

		foreach ($collection as $product) {

			$product->load($product->getId());

			$children = null;
			$usedAttribs = null;

			if ($product->isConfigurable()) {
				$usedAttribs = array();
				foreach ($product->getTypeInstance(true)->getUsedProductAttributes($product) as $attribute) {
					$usedAttribs[] = array (
						"code" => $attribute->getAttributeCode(),
						"label" => $attribute->getStoreLabel()
					   );
				}

				$children = array();

				$childProducts = $product->getTypeInstance(true)->getUsedProducts(null,$product);

				foreach ($childProducts as $childProd) {
					$children[] = $this->basicProductData($childProd, null, $usedAttribs);
				}
			}

			$parentId = $product->loadParentProductIds()->getData('parent_product_ids');

			if(isset($parentId[0])) {
				$parent = Mage::getModel('catalog/product')->load($parentId[0]);
			}

			if(!isset($parentId[0]) || (isset($parent) && $parent->isGrouped())){//if this product has a parent
				$result[] = $this->basicProductData($product, $children, $usedAttribs);
			}

		}

		return $result;

		$retval = array();
		$retval['list'] = $result;
		$retval['currentpage'] = $collection->getCurPage();
		$retval['lastpage'] = $collection->getLastPageNumber();
		$retval['memoryusage'] = memory_get_peak_usage();
		return json_encode($retval);
	}

	public function itemscount($pagesize, $pagenumber, $filters = null, $store = null)
	{

		$collection = Mage::getModel('catalog/product')->getCollection()
			->setFlag('require_stock_items', true)
			->setStoreId($this->_getStoreId($store))
			->addAttributeToSelect('name')
			->addAttributeToSelect('price');

		if (is_array($filters)) {
			try {
				foreach ($filters as $field => $value) {
					if (isset($this->_filtersMap[$field])) {
						$field = $this->_filtersMap[$field];
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

		/*return $collection->count(); -- don't want to do this because it implicitly calls load() -- better to use SQL's COUNT() */
	}

	private function basicProductData($product, $childProducts=null, $attributes=null) {

		$result = 	array( // Basic product data
			'product_id' => $product->getId(),
			'sku'        => $product->getSku(),
			'name'       => $product->getName(),
			'set'        => $product->getAttributeSetId(),
			'type'       => $product->getTypeId(),
			'category_ids'       => $product->getCategoryIds(),
			'store_ids'          => $product->getStoreIds(),
			'website_ids'        => $product->getWebsiteIds(),
			'children'           => $childProducts,
			'price'		=> $product->getPrice(),
			'qty'           => $product->getStockItem()->getQty(),
			'is_in_stock'   => $product->getStockItem()->getIsInStock(),
			'updated_at'	=> $product->getUpdatedAt(),
			'parent_ids'	=> Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild( $product->getId() )
		);
		$result['used_attributes'] = array();
		$result['attribute_texts'] = array();

		$allAttributes = array();
		if (!empty($attributes->attributes)) {
			$allAttributes = array_merge($allAttributes, $attributes->attributes);
		} else {
			foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute) {
				if ($this->_isAllowedAttribute($attribute, $attributes)) {
					$allAttributes[] = $attribute->getAttributeCode();
				}
			}
		}

		$_additionalAttributeCodes = array();
		if (!empty($attributes->additional_attributes)) {
			foreach ($attributes->additional_attributes as $k => $_attributeCode) {
				$allAttributes[] = $_attributeCode;
				$_additionalAttributeCodes[] = $_attributeCode;
			}
		}

		$_additionalAttribute = 0;
		foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute) {
			if ($this->_isAllowedAttribute($attribute, $allAttributes)) {
				if (in_array($attribute->getAttributeCode(), $_additionalAttributeCodes)) {
					$result['additional_attributes'][$_additionalAttribute]['key'] = $attribute->getAttributeCode();
					$result['additional_attributes'][$_additionalAttribute]['value'] = $product->getData($attribute->getAttributeCode());
					$_additionalAttribute++;
				} else {
					$result[$attribute->getAttributeCode()] = $product->getData($attribute->getAttributeCode());
				}
			}
		}

		if ($product->isConfigurable() && $attributes != null) {
			$result['used_attributes'] = $attributes;
		} else if ($attributes != null) {
			foreach ($attributes as $attribute) {
				$result['attribute_texts'][$attribute['code']] = $product->getAttributeText( $attribute['code'] );
			}
		}

		return $result;
	}

	/**
	 * Retrieve product info
	 *
	 * @param int|string $productId
	 * @param string|int $store
	 * @param array $attributes
	 * @return array
	 */
	public function info($productId, $store = null, $attributes = null, $identifierType = null)
	{

		$product = $this->_getProduct($productId, $store, $identifierType);

		if (!$product->getId()) {
			$this->_fault('not_exists');
		}

		$children = null;
		$usedAttribs = null;

		if ($product->isConfigurable()) {
			$usedAttribs = array();
			foreach ($product->getTypeInstance(true)->getUsedProductAttributes($product) as $attribute) {
				$usedAttribs[] = array (
					"code" => $attribute->getAttributeCode(),
					"label" => $attribute->getStoreLabel()
				   );
			}

			$children = array();

			$childProducts = $product->getTypeInstance(true)->getUsedProducts(null,$product);

			foreach ($childProducts as $childProd) {
				$children[] = $this->basicProductData($childProd, null, $usedAttribs);
			}
		}

		$parentId = $product->loadParentProductIds()->getData('parent_product_ids');

		if(isset($parentId[0])) {
			$parent = Mage::getModel('catalog/product')->load($parentId[0]);
		}

		if(!isset($parentId[0]) || (isset($parent) && $parent->isGrouped())){//if this product has a parent
		//if (!$allChildIds[$product->getId()]) {
			$result = $this->basicProductData($product, $children, $usedAttribs);
		} else {
			$result = $this->basicProductData($product, $children, $usedAttribs);
		}
		
		/* Moinul - version 2.2.2 - nullify description and short_description */
		$result['description'] 			= '';
		$result['short_description'] 	= '';
			
		/* Moinul - version 2.2.2 - remove few fields from API response*/
		unset($result['gift_message_available'], $result['gift_wrapping_available'], $result['gift_wrapping_price'],
		  	  $result['image_label'], $result['small_image_label'], $result['thumbnail_label'],
			  $result['meta_title'], $result['meta_keyword'], $result['meta_description'],
			  $result['custom_design'], $result['custom_design_from'],
			  $result['custom_design_to'], $result['custom_layout_update'], $result['page_layout']
			  );
		
		return $result;
	}

}
