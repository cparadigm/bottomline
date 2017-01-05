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
 * Holger Brandt IT Solutions does not guarantee coweightatibility with any other framework extension. Holger Brandt IT Solutions  is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * info@brandt-solutions.de, so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/
class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_ManCatModifiers extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_CategoryNameMatch
{

    /**
     * Append manual category weight modifier relevance score.
     * The relevance score is SUM of all category weight modifiers set for the product.
     * @param  TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection $search_query_collection
     * @param  array $rel_likes
     * @return $this
     */
    public function appendRelevenceScore(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection, &$rel_likes)
    {
        $this->_assureProductCategories($search_query_collection);

        if (!$search_query_collection->hasJoined('cat_name')) {
            return $this;
        }

        $manualCatModifiers = array();
        $manualCatModifiers = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('bss_cat_weight')
            ->addFieldToFilter('bss_cat_weight', array('notnull' => 1))
            ->addFieldToFilter('bss_cat_weight', array('neq' => 0))
            ->addFieldToFilter('bss_cat_weight', array('neq' => 1))
            ;

        $likes = null;
        foreach ($manualCatModifiers as $weight) {
            $weight_m = (float)($weight->getBssCatWeight());
            if (!empty($weight_m)) {
                if (is_null($likes)) {
                    $likes = $search_query_collection->getConnection()->quoteInto("(bss_index.category_ids RLIKE ?)*{$weight_m}", '[[:<:]]'.$weight->getId().'[[:>:]]');
                } else {
                    $likes .= ' + ' . $search_query_collection->getConnection()->quoteInto("(bss_index.category_ids RLIKE ?)*{$weight_m}", '[[:<:]]'.$weight->getId().'[[:>:]]');
                }
            }
        }

        $rel_likes[] = !is_null($likes) ? $likes : 0;

        return $this;
    }

}
