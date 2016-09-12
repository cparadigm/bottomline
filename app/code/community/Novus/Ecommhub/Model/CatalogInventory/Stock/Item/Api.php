<?php
/**
 * Catalog inventory api
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */

class Novus_Ecommhub_Model_CatalogInventory_Stock_Item_Api extends Mage_CatalogInventory_Model_Stock_Item_Api
{
	public function items($productIds)
	{

		if (!is_array($productIds)) {
			$productIds = array($productIds);
		}

		$product = Mage::getModel('catalog/product');

		foreach ($productIds as &$productId) {
			if ($newId = $product->getIdBySku($productId)) {
				$productId = $newId;
			}
		}

		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->setFlag('require_stock_items', true)
			->addFieldToFilter('entity_id', array('in'=>$productIds));

		$result = array();

		foreach ($collection as $product) {
			if ($product->getStockItem()) {
				$result[] = array(
					'product_id'    => $product->getId(),
					'sku'           => $product->getSku(),
					'qty'           => $product->getStockItem()->getQty(),
					'is_in_stock'   => $product->getStockItem()->getIsInStock(),
					'backorders'    => $product->getStockItem()->getBackorders(), // ECOMMHUB CUSTOM
					'use_config_backorders'	=> $product->getStockItem()->getUseConfigBackorders() // ECOMMHUB CUSTOM
				);
			}
		}

		return $result;
	}

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

		if (isset($data['qty'])) {
			$stockData['qty'] = $data['qty'];
		}

		if (isset($data['is_in_stock'])) {
			$stockData['is_in_stock'] = $data['is_in_stock'];
		}

		if (isset($data['manage_stock'])) {
			$stockData['manage_stock'] = $data['manage_stock'];
		}

		if (isset($data['use_config_manage_stock'])) {
			$stockData['use_config_manage_stock'] = $data['use_config_manage_stock'];
		}

		/* BEGIN ECOMMHUB CUSTOM */
		if (isset($data['backorders'])) {
			$stockData['backorders'] = $data['backorders'];
		}

		if (isset($data['use_config_backorders'])) {
			$stockData['use_config_backorders'] = $data['use_config_backorders'];
		}
		/* END ECOMMHUB CUSTOM */

		$product->setStockData($stockData);

		try {
			$product->save();
		} catch (Mage_Core_Exception $e) {
			$this->_fault('not_updated', $e->getMessage());
			return false;
		}

		return true;
	}

	public function massupdate( $data ){

		$stream = fopen( '/home/ech1510/public_html/tmp/tmp.txt', 'a+' );
		fwrite( $stream, "running: Novus_Ecommhub_Model_CatalogInventory_Stock_Item_Api::massupdate()\n" );
		fwrite( $stream, print_r( $data, 1 ) ."\n" );
		fclose( $stream );

		// will anything use this?
		// i.e. do we need a v1 api massUpdate function?  v2 has it..
		// will anything use this?
		// i.e. do we need a v1 api massUpdate function?  v2 has it..
		foreach( $data as $datum ){

			$product_id = $datum['key'];
			$qty = $datum['value'];

			$product = Mage::getModel('catalog/product');
			if ($newId = $product->getIdBySku( $product_id )) {
				$product_id = $newId;
			}

			$product->setStoreId($this->_getStoreId())
				->load( $product_id );

			$stream = fopen( '/home/ech1510/public_html/tmp/tmp.txt', 'a+' );
			fwrite( $stream, "loaded product with id: ". $product_id ." and name: ". $product->getName() ."\n" );
			fclose( $stream );

			if (!$stockData = $product->getStockData()) {
				$stockData = array();
			}

			$stockData['qty'] = $qty;

			$product->setStockData($stockData);

			try {
				$stream = fopen( '/home/ech1510/public_html/tmp/tmp.txt', 'a+' );
				fwrite( $stream, "attempting save()\n" );
				fclose( $stream );
				$product->save();
				$stream = fopen( '/home/ech1510/public_html/tmp/tmp.txt', 'a+' );
				fwrite( $stream, "finished save()\n" );
				fclose( $stream );
			} catch (Mage_Core_Exception $e) {
				$stream = fopen( '/home/ech1510/public_html/tmp/tmp.txt', 'a+' );
				fwrite( $stream, $e->getMessage() ."\n" );
				fclose( $stream );
				$this->_fault('not_updated', $e->getMessage());
			}

		}

		return true;

	}

}
