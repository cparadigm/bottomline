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
class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_FullQueryCategoryMatch extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_CategoryNameMatch
{

    public function appendRelevenceScore(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection, &$rel_likes)
    {
        $this->_assureProductCategories($search_query_collection);

        if (!$search_query_collection->hasJoined('cat_name')) {
            return $this;
        }

        $query = $search_query_collection->getQuery();

        $full_query = trim($query);
        if (stripos($full_query, ' as ') !== false) {
            $full_query = str_replace(" as ", '__as__', strtolower($full_query));
        }

        if ($aw_cat_name = (float) Mage::getStoreConfig('bss/aw/cat_name')) {

            if (Mage::helper("bss/config")->isEnabledPartialWordMatching()) {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(bss_index.categories LIKE ?)*{$aw_cat_name}", '%' . ($full_query) . '%');
            } else {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(bss_index.categories RLIKE ?)*{$aw_cat_name}", '[[:<:]]' . $this->prepForPreg($full_query) . '[[:>:]]');
            }

            if (strpos($full_query, '__as__') !== false) {
                $rel_likes[sizeof($rel_likes) - 1] = str_replace(" '", " CONCAT('", $rel_likes[sizeof($rel_likes) - 1]);
                $rel_likes[sizeof($rel_likes) - 1] = str_replace("')", "'))", $rel_likes[sizeof($rel_likes) - 1]);
                $rel_likes[sizeof($rel_likes) - 1] = str_replace('__as__', " ',char(97), char(115),' ", $rel_likes[sizeof($rel_likes) - 1]);
            }
        }

        return $this;
    }

}