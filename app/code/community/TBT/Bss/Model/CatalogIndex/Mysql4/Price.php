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
 * Price index resource model
 *
 */
class TBT_Bss_Model_CatalogIndex_Mysql4_Price extends Mage_CatalogIndex_Model_Mysql4_Price
{
    public function getCount($range, $attribute, $entitySelect)
    {
        $select = clone $entitySelect;
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);
        $select->reset(Zend_Db_Select::GROUP);

        $select->join(array('price_table'=>$this->getMainTable()), 'price_table.entity_id=e.entity_id', array());
        $response = new Varien_Object();
        $response->setAdditionalCalculations(array());

        if ($attribute->getAttributeCode() == 'price') {
            $select->where('price_table.customer_group_id = ?', $this->getCustomerGroupId());
            $args = array(
                'select'=>$select,
                'table'=>'price_table',
                'store_id'=>$this->getStoreId(),
                'response_object'=>$response,
            );
            Mage::dispatchEvent('catalogindex_prepare_price_select', $args);
        }


        $fields = array('count'=>'COUNT(DISTINCT price_table.entity_id)', 'range'=>"FLOOR(((price_table.value".implode('', $response->getAdditionalCalculations()).")*{$this->getRate()})/{$range})+1");

        $select->from('', $fields)
            ->group('range')
            ->where('price_table.website_id = ?', $this->getWebsiteId())
            ->where('price_table.attribute_id = ?', $attribute->getId());


        $result = $this->_getReadAdapter()->fetchAll($select);

        $counts = array();
        foreach ($result as $row) {
            $counts[$row['range']] = $row['count'];
        }

        return $counts;
    }

}
