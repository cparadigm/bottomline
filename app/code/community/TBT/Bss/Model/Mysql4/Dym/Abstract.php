<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, Holger Brandt IT Solutions not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by Holger Brandt IT Solutions, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time Holger Brandt IT Solutions spent during the support process.
 * Holger Brandt IT Solutions does not guarantee compatibility with any other framework extension. Holger Brandt IT Solutions  is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * info@brandt-solutions.de, so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

/**
 *
 * @category   TBT
 * @author     Holger Brandt IT Solutions <info@brandt-solutions.de>
 */
abstract class TBT_Bss_Model_Mysql4_Dym_Abstract extends Mage_Core_Model_Mysql4_Abstract
{

    /**
     * returns bss_index (search index) table
     *
     * @return string
     */
    protected function _getBssIndexTable() {
        return Mage::getConfig()->getTablePrefix(). 'bss_index';
    }

    /**
     * returns catalogsearch_fulltext (search index) table
     *
     * @return string
     */
    protected function _getCFTable() {
        return Mage::getConfig()->getTablePrefix(). 'catalogsearch_fulltext';
    }

    /**
     * returns catalog_product_entity_varchar table
     *
     * @return string
     */
    protected function _getCPEVTable() {
        return Mage::getConfig()->getTablePrefix(). 'catalog_product_entity_varchar';
    }

    /**
     * returns catalog_product_flat_xxxx table where xxxx is the store id
     *
     * @return string
     */
    protected function _getCPFlatTable($store_id) {
        return Mage::getConfig()->getTablePrefix(). 'catalog_product_flat_'. $store_id;
    }

    protected function getConnection() {
        return $this->_getReadAdapter();
    }

    protected function _getWriteAdapter() {
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    protected function _getReadAdapter() {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }


    /**
     * Returns a string either "TRUE"; or column IN (val_array..); or column = val
     *
     * @param string $column    : column name (include the column prefix if possible
     * @param mixed $value     : value or values
     * @param Varien_Db_Adapter_Pdo_Mysql $conn
     * @return string :     sql segment
     */
    public function getMixedArrayCondSql($column, $value, $conn) {
        if($value == null) {
            $sql_in = "TRUE";
        }elseif(is_array($value)) {
            $sql_in = $conn->quoteInto("{$column} IN (?)", $value);
        } else {
            $sql_in = $conn->quoteInto("{$column} = ?", $value);
        }

        return $sql_in;
    }


    /**
     * Returns the attribute id of the Product Name attribute for this Magento installation
     *
     * @return integer
     */
    protected function _getProductNameId() {
        $eav_name = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'name');
        $naid = $eav_name->getId();
        return $naid;
    }
}