<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/
/**
 * Catalog Layer Decimal attribute Filter Resource Model
 *
 * @category    Mage
 * @package     Mage_Catalog
 */
class TBT_Bss_Model_Catalog_Resource_Eav_Mysql4_Layer_Filter_Decimal extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Decimal
{
    /**
     * Retrieve clean select with joined index table
     *
     * Joined table has index
     *
     * @param Mage_Catalog_Model_Layer_Filter_Decimal $filter
     * @return Varien_Db_Select
     */
    protected function _getSelect($filter)
    {
        $collection = $filter->getLayer()->getProductCollection();

        // clone select from collection with filters
        $select = clone $collection->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);
        //@nelkaake Added on Friday July 2, 2010:
        $select->reset(Zend_Db_Select::GROUP);

        $attributeId = $filter->getAttributeModel()->getId();
        $storeId     = $collection->getStoreId();

        $select->join(
            array('decimal_index' => $this->getMainTable()),
            "e.entity_id=decimal_index.entity_id AND decimal_index.attribute_id={$attributeId}"
                . " AND decimal_index.store_id={$storeId}",
            array()
        );

        return $select;
    }

}