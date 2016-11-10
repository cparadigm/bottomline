<?php

class TBT_Bss_Model_Mysql4_Dym_Tag extends TBT_Bss_Model_Mysql4_Dym_Abstract
{
    public function _construct()
    {
        return $this;
    }

    /**
     * Regenerate search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    public function updateTagIndexes($storeId = null, $productIds = null, $forceToUpdate = false)
    {
        if (!Mage::helper('bss/config')->isTagMatchEnabled() && !$forceToUpdate) {
            return $this;
        }

        $tag_relation_t = Mage::getConfig()->getTablePrefix(). 'tag_relation';
        $tag_t = Mage::getConfig()->getTablePrefix(). 'tag';
        $cf_t = $this->_getBssIndexTable();
        $viewSqlWhere = "";
        $conn = Mage::getSingleton('core/resource')->getConnection('core_read');

        $viewWhere = array();

        if ($productIds) {
            if (is_array($productIds)) {
                $productIdString = implode(",", $productIds);
                $viewWhere[] = "tag_relation.product_id IN ({$productIdString})";
            } else{
                $viewWhere[] = "tag_relation.product_id = ({$productIds})";
            }
        }

        // Filter by store ID
        if ($storeId) {
            $viewWhere[] = "tag_relation.store_id = ({$storeId})";
        }

        // Check status
        $viewWhere[] = "tag.status = 1";

        if (!empty($viewWhere)) {
            $viewSqlWhere = ' WHERE ' . join(" AND ", $viewWhere) . ' ';
        }

        $viewSql = "
            SELECT tag_relation.product_id pid, tag_relation.store_id store_id,
                   GROUP_CONCAT(tag.name SEPARATOR \"|\") tag_names
            FROM `{$tag_relation_t}` tag_relation
            LEFT JOIN `{$tag_t}` tag ON tag_relation.tag_id = tag.tag_id
            {$viewSqlWhere}
            GROUP BY tag_relation.product_id,tag_relation.store_id
       ";

        $sql = "
            UPDATE `{$cf_t}` `cf`
            JOIN ( {$viewSql} ) a
            SET tag = a.tag_names
            WHERE cf.product_id = a.pid AND cf.store_id = a.store_id
        ";

        $results = $conn->query($sql);

        // make sure we don't have any 'null' values in tag column because it messes Profiler results
        $sql = "
            UPDATE `{$cf_t}` `cf`
            SET cf.tag = '|'
            WHERE cf.tag IS NULL;
        ";
        $conn->query($sql);
        $conn->commit();

        return $results;
    }
}
