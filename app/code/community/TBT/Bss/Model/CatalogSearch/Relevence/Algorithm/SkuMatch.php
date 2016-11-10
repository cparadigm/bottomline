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
class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_SkuMatch extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract
{

    public function appendRelevenceScore(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection, &$rel_likes)
    {
        $query = $search_query_collection->getQuery();

        //@nelkaake -a 5/11/10: There's an issue with the AS query in Zend Framework.  This is a workaround.
        $full_query = trim($query);
        if (stripos($full_query, ' as ') !== false) {
            $full_query = str_replace(" as ", '__as__', strtolower($full_query));
        }

        //@nelkaake Added on Wednesday June 2, 2010: If we've specified an attribute weighting, use that instead
        // With the sku, we dont need to break up the digits.
        if ($aw_sku = (float)Mage::getStoreConfig('bss/aw/sku')) {
            if (Mage::helper("bss/config")->isEnabledPartialWordMatching()) {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(`e`.sku LIKE ?)*{$aw_sku}", '%'.$full_query.'%');
            } else {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(`e`.sku RLIKE ?)*{$aw_sku}", '[[:<:]]'. $this->prepForPreg($full_query) .'[[:>:]]');
            }

            if (strpos($full_query, '__as__') !== false) {
                $rel_likes[sizeof($rel_likes)-1] = str_replace(" '", " CONCAT('",$rel_likes[sizeof($rel_likes)-1]);
                $rel_likes[sizeof($rel_likes)-1] = str_replace("')", "'))",$rel_likes[sizeof($rel_likes)-1]);
                $rel_likes[sizeof($rel_likes)-1] = str_replace('__as__', " ',char(97), char(115),' ",$rel_likes[sizeof($rel_likes)-1]);
            }

            $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(`e`.sku = ?)*{$aw_sku}", $full_query);

            if (strpos($full_query, ' as ') !== false) {
                $rel_likes[sizeof($rel_likes)-1] = str_replace(" '", " CONCAT('",$rel_likes[sizeof($rel_likes)-1]);
                $rel_likes[sizeof($rel_likes)-1] = str_replace("')", "'))",$rel_likes[sizeof($rel_likes)-1]);
                $rel_likes[sizeof($rel_likes)-1] = str_replace('__as__', " ',char(97), char(115),' ",$rel_likes[sizeof($rel_likes)-1]);
            }
        }

        return $this;
    }

}
