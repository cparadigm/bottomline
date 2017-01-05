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
class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_FullQueryTagMatch extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_TagNameMatch
{

    public function appendRelevenceScore(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection, &$rel_likes)
    {
        $this->_assureProductTag($search_query_collection);

        if (!$search_query_collection->hasJoined('tag_name')) {
            return $this;
        }

        $query = $search_query_collection->getQuery();

        $full_query = trim($query);
        if (stripos($full_query, ' as ') !== false) {
            $full_query = str_replace(" as ", '__as__', strtolower($full_query));
        }

        if ($aw_tag_name = (float) Mage::getStoreConfig('bss/aw/tag_name')) {

            if (Mage::helper("bss/config")->isEnabledPartialWordMatching()) {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(bss_index.tag LIKE ?)*{$aw_tag_name}", '%' . ($full_query) . '%');
            } else {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(bss_index.tag RLIKE ?)*{$aw_tag_name}", '[[:<:]]' . $this->prepForPreg($full_query) . '[[:>:]]');
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
