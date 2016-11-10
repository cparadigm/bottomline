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
class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_IndiWordMatch extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_ProductNameMatch
{

    public function appendRelevenceScore(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection, &$rel_likes)
    {
        $this->_assureCatalogProduct($search_query_collection);

        $query = $search_query_collection->getQuery();
        $terms = explode(" ", trim($query));

        foreach ($terms as $t) {
             //@nelkaake Added on Wednesday June 2, 2010: If we've specified an attribute weighting, use that instead
            if ($aw_name = (float)Mage::getStoreConfig('bss/aw/name')) {
                //@nelkaake Added on Friday October 22, 2010: TODO Enable/Disable partial word matching
                if (Mage::helper("bss/config")->isEnabledPartialWordMatching()) {
                    $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(product_name.value LIKE ?)*{$aw_name}", '%'.$t.'%');
                } else {
                    $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(product_name.value RLIKE ?)*{$aw_name}", '[[:<:]]'. $this->prepForPreg($t) .'[[:>:]]');
                }
            }

            if (Mage::getStoreConfigFlag('bss/special/plural')) {
                // If the word is plural, then we must have removed the plural S in the original query.  Therefor we should add it back in
                // so we can get an accurate match in this step.
                $unplural = rtrim($t, "Ss");
                if($unplural == $t) {
                    $unplural = $t . "s";
                }

                //@nelkaake (chng) on 23/10/10: Changed from partial unplural word matching to full-word unplurral.
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(product_name.value RLIKE ?)", '[[:<:]]'. $this->prepForPreg($unplural) .'[[:>:]]');
            }
        }

        return $this;
    }

}
