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
 * Attribute index resource model
 *
 */
class TBT_Bss_Model_CatalogIndex_Mysql4_Attribute extends Mage_CatalogIndex_Model_Mysql4_Attribute
{
    public function getCount($attribute, $entitySelect)
    {
        $select = clone $entitySelect;
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);
        $select->reset(Zend_Db_Select::GROUP);

        $fields = array('count'=>'COUNT(index.entity_id)', 'index.value');

        $select->from('', $fields)
            ->join(array('index'=>$this->getMainTable()), 'index.entity_id=e.entity_id', array())
            ->where('index.store_id = ?', $this->getStoreId())
            ->where('index.attribute_id = ?', $attribute->getId())
            ->group('index.value');

        $select = $select->__toString();
//        $alias = $this->_getReadAdapter()->quoteTableAs($this->getMainTable(), 'index');
        $result = $this->_getReadAdapter()->fetchAll($select);

        $counts = array();
        foreach ($result as $row) {
            $counts[$row['value']] = $row['count'];
        }
        return $counts;
    }

}