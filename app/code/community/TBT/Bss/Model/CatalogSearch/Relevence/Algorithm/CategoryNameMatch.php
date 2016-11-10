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
abstract class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_CategoryNameMatch extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract
{

    protected function _assureProductCategories( TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection)
    {
        // if option not enabled in Admin, don't go further
        if (!Mage::helper('bss/config')->isCategoryMatchEnabled()) {
            return $this;
        }

        if ($search_query_collection->hasJoined('cat_name') || $search_query_collection->hasJoined('tag_name')) {
            $search_query_collection->rememberJoin('cat_name');
            return $this;
        }

        $search_query_collection->getSelect()
        ->joinLeft(
            array('bss_index' => Mage::getConfig()->getTablePrefix()."bss_index"),
            'bss_index.product_id = e.entity_id',
            array()
        );

        $search_query_collection->rememberJoin('cat_name');

        return $this;
    }

}
