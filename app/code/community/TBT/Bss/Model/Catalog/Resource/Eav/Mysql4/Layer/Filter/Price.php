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
 * Catalog Layer Price Filter resource model
 *
 * @category    Mage
 * @package     Mage_Catalog
 */
class TBT_Bss_Model_Catalog_Resource_Eav_Mysql4_Layer_Filter_Price extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
{
    /**
     * Retrieve clean select with joined price index table
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return Varien_Db_Select
     */
    protected function _getSelect($filter)
    {
        $select = parent::_getSelect($filter);

        //@nelkaake Added on Friday July 2, 2010:
        $select->reset(Zend_Db_Select::GROUP);

        return $select;
    }

}
