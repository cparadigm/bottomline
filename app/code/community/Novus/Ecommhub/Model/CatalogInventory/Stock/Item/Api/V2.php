<?php
/**
 * Catalog inventory api
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */

class Novus_Ecommhub_Model_CatalogInventory_Stock_Item_Api_V2 extends Novus_Ecommhub_Model_CatalogInventory_Stock_Item_Api
{

	public function update($productId, $data)
	{

		$product = Mage::getModel('catalog/product');

		if ($newId = $product->getIdBySku($productId)) {
			$productId = $newId;
		}

		$product->setStoreId($this->_getStoreId())
			->load($productId);

		if (!$product->getId()) {
			$this->_fault('not_exists');
		}

		if (!$stockData = $product->getStockData()) {
			$stockData = array();
		}

		if (isset($data->qty)) {
			$stockData['qty'] = $data->qty;
		}

		if (isset($data->is_in_stock)) {
			$stockData['is_in_stock'] = $data->is_in_stock;
		}

		if (isset($data->manage_stock)) {
			$stockData['manage_stock'] = $data->manage_stock;
		}

		if (isset($data->use_config_manage_stock)) {
			$stockData['use_config_manage_stock'] = $data->use_config_manage_stock;
		}

		/* BEGIN ECOMMHUB CUSTOM */
		if (isset($data->backorders)) {
			$stockData['backorders'] = $data->backorders;
		}

		if (isset($data->use_config_backorders)) {
			$stockData['use_config_backorders'] = $data->use_config_backorders;
		}
		/* END ECOMMHUB CUSTOM */

		$product->setStockData($stockData);

		try {
			$product->save();
		} catch (Mage_Core_Exception $e) {
			$this->_fault('not_updated', $e->getMessage());
		}

		return true;
	}

	/* ECOMMHUB CUSTOM */
	public function massupdate( $data )
	{

		foreach( $data as $datum ){

			$product_id = $datum->key;
			$qty = $datum->value;

			$product = Mage::getModel('catalog/product');
			if ($newId = $product->getIdBySku( $product_id )) {
				$product_id = $newId;
			}

			$product->setStoreId($this->_getStoreId())
				->load( $product_id );

			if (!$stockData = $product->getStockData()) {
				$stockData = array();
			}

			$stockData['qty'] = $qty;

			$product->setStockData($stockData);

			try {
				$product->save();
			} catch (Mage_Core_Exception $e) {
				$this->_fault('not_updated', $e->getMessage());
			}

		}

		return true;

	}
	/* END ECOMMHUB CUSTOM */

}