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
 *
 * @category   TBT
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
class TBT_Bss_Model_Mysql4_Dym_Soundex extends TBT_Bss_Model_Mysql4_Dym_Abstract
{
    public function _construct()
    {
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $query
     * @return unknown
     */
    public function findBySoundex($query)
    {
        $query_parts = explode(' ', $query);

        $cf_t = $this->_getBssIndexTable();
        $conn = $this->getConnection();

        $soundex_parts = array();
        $partial_soundex_parts = array();
        foreach ($query_parts as $qp) {
            $soundex_parts[] = soundex($qp) . "|";
            $partial_soundex_parts[] = substr(soundex($qp), 1) . "|";
        }

        $rank_match_sql = $this->_genLikeSoundexRankSql($soundex_parts, 'cf.pns');
        $rank_match_partial_sql = $this->_genLikeSoundexRankSql($partial_soundex_parts, 'cf.pns');
        $rank_pos_sql = $this->_genLikeSoundexPositionRankSql($soundex_parts, 'cf.pns');

        $sql = ("
SELECT DISTINCT product_id
FROM {$cf_t} cf
WHERE {$rank_match_sql} > 0
ORDER BY {$rank_match_sql} DESC, {$rank_pos_sql} ASC, {$rank_match_partial_sql} DESC
        ");
        $result_ids = $conn->fetchCol($sql);

        return $result_ids;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $soundex_parts
     * @param unknown_type $field
     * @return unknown
     */
    protected function _genLikeSoundexRankSql($soundex_parts, $field)
    {
        $conn = $this->getConnection();

        $ranking_sql_array = array();
        foreach ($soundex_parts as $sp) {
            $ranking_sql_array[] = $conn->quoteInto("{$field} LIKE ?", "%". $sp . "%");
        }

        $ranking_sql = implode(") + (", $ranking_sql_array);
        if (sizeof($ranking_sql_array) > 0) {
            $ranking_sql = "(". $ranking_sql . ")";
        }

        return $ranking_sql;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $soundex_parts
     * @param unknown_type $field
     * @return unknown
     */
    protected function _genLikeSoundexPositionRankSql($soundex_parts, $field)
    {
        $conn = $this->getConnection();

        $ranking_sql_array = array();
        foreach ($soundex_parts as $sp) {
            $ranking_sql_array[] = $conn->quoteInto("LOCATE(?, {$field})", $sp);
        }

        $ranking_sql = implode(") + (", $ranking_sql_array);
        if (sizeof($ranking_sql_array) > 0) {
            $ranking_sql = "(". $ranking_sql . ")";
        }

        return $ranking_sql;
    }

    /**
     * Regenerate search index for specific store
     * @depends must run before any other bss_index table column indexes.
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    public function quickUpdatePnsIndexes($storeId, $productIds = null)
    {
        return $this->_quickUpdatePnsIndexes($storeId, $productIds);
    }

    /**
     * Regenerate search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    protected function _quickUpdatePnsIndexes($storeId, $productIds = null)
    {
        $conn = $this->_getWriteAdapter();

        // The DDL statements error only comes up in v1.7 so we need to end all older transactions.
        if(Mage::helper('bss/version')->isBaseMageVersionAtLeast('1.7')) {
            while($conn->getTransactionLevel() > 0) {
                $conn->commit();
            }
        }

        $conn->beginTransaction();
        $this->_regenerateBssIndexIds($conn, $storeId, $productIds);
        $conn->commit();

        $this->_regenerateNewTmpCfTables($conn, $storeId, $productIds);

        $conn->beginTransaction();
        $this->_updateBssPns($conn, $storeId, $productIds);
        $conn->commit();

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param Varien_Db_Adapter_Pdo_Mysql $conn
     * @param unknown_type $storeId
     * @param unknown_type $productIds
     * @return unknown
     */
    protected function _regenerateBssIndexIds($conn, $storeId, $productIds)
    {
        //@nelkaake -a 19/12/10:
        $sql_in = $this->getMixedArrayCondSql("product_id", $productIds, $conn);

        $bi_t = $this->_getBssIndexTable();
        $cf_t = $this->_getCFTable();

        $sql = "
            DELETE FROM {$bi_t}
            WHERE {$sql_in} AND store_id = {$storeId};
        ";
        $conn->query($sql);

        $conn->query("
            INSERT INTO {$bi_t} (
                SELECT product_id, store_id, null, null, null, null, null, null
                FROM {$cf_t}
                WHERE {$sql_in} AND store_id = {$storeId}
            )
            ;
        ");

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param Varien_Db_Adapter_Pdo_Mysql $conn
     * @param unknown_type $storeId
     * @param unknown_type $productIds
     * @return unknown
     */
    protected function _regenerateNewTmpCfTables($conn, $storeId, $productIds)
    {
        //@nelkaake -a 19/12/10:
        $conn->query("DROP temporary table IF EXISTS `tmp_cf_fs`");

        if (Mage::helper('catalog/product_flat')->isEnabled()) {
            $cpflat_t = $this->_getCPFlatTable($storeId);
            $sql_in1 = $this->getMixedArrayCondSql("names.entity_id", $productIds, $conn);
    
            $conn->query("
                CREATE temporary table tmp_cf_fs  (
                    SELECT {$storeId} as store_id, names.entity_id, @pname := names.name as value, names.entity_id as product_id,
                      @s1 := IF(LOCATE(' ', @pname) > 0, ((LEFT(@pname, LOCATE(' ', @pname)))), @pname) as s1,
                      @s2 := IF(@s1='','',( LEFT(SUBSTR(@pname, length(@s1)+locate(@s1,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s1)+locate(@s1,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s1)+locate(@s1,@pname))))) )  ) as s2,
                      @s3 := IF(@s2='','',( LEFT(SUBSTR(@pname, length(@s2)+locate(@s2,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s2)+locate(@s2,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s2)+locate(@s2,@pname))))) )  ) as s3,
                      @s4 := IF(@s3='','',( LEFT(SUBSTR(@pname, length(@s3)+locate(@s3,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s3)+locate(@s3,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s3)+locate(@s3,@pname))))) )  ) as s4,
                      @s5 := IF(@s4='','',( LEFT(SUBSTR(@pname, length(@s4)+locate(@s4,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s4)+locate(@s4,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s4)+locate(@s4,@pname))))) )  ) as s5,
                      @s6 := IF(@s5='','',( LEFT(SUBSTR(@pname, length(@s5)+locate(@s5,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s5)+locate(@s5,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s5)+locate(@s5,@pname))))) )  ) as s6,
                      @s7 := IF(@s6='','',( LEFT(SUBSTR(@pname, length(@s6)+locate(@s6,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s6)+locate(@s6,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s6)+locate(@s6,@pname))))) )  ) as s7,
                      @s8 := IF(@s7='','',( LEFT(SUBSTR(@pname, length(@s7)+locate(@s7,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s7)+locate(@s7,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s7)+locate(@s7,@pname))))) )  ) as s8,
                      @s9 := IF(@s8='','',( LEFT(SUBSTR(@pname, length(@s8)+locate(@s8,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s8)+locate(@s8,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s8)+locate(@s8,@pname))))) )  ) as s9,
                      @s10 := IF(@s9='','',( LEFT(SUBSTR(@pname, length(@s9)+locate(@s9,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s9)+locate(@s9,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s9)+locate(@s9,@pname))))) )  ) as s10,
                    CONCAT(
                        SUBSTR(soundex(@s1),1),
                        '|',
                        SUBSTR(soundex(@s2),1),
                        '|',
                        SUBSTR(soundex(@s3),1),
                        '|',
                        SUBSTR(soundex(@s4),1),
                        '|',
                        SUBSTR(soundex(@s5),1),
                        '|',
                        SUBSTR(soundex(@s6),1),
                        '|',
                        SUBSTR(soundex(@s7),1),
                        '|',
                        SUBSTR(soundex(@s8),1),
                        '|',
                        SUBSTR(soundex(@s9),1),
                        '|',
                        SUBSTR(soundex(@s10),1),
                        '|'
                    ) as full_soundex
                    FROM {$cpflat_t} names
                    WHERE {$sql_in1}
                )
                ;
            ");
        } else {
            $productTable = Mage::getSingleton('core/resource')->getTableName('catalog/product');
            $productVarcharTable = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar');
            $nameAttributeId = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'name')->getAttributeId();
            $sql_in1 = $this->getMixedArrayCondSql("e.entity_id", $productIds, $conn);
            
            $conn->query("      
                CREATE temporary table tmp_cf_fs  (
                    SELECT {$storeId} AS store_id, e.entity_id AS entity_id, @pname := v.value AS value, e.entity_id AS prouct_id,
                        @s1 := IF(LOCATE(' ', @pname) > 0, ((LEFT(@pname, LOCATE(' ', @pname)))), @pname) as s1,
                        @s2 := IF(@s1='','',( LEFT(SUBSTR(@pname, length(@s1)+locate(@s1,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s1)+locate(@s1,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s1)+locate(@s1,@pname))))) )  ) as s2,
                        @s3 := IF(@s2='','',( LEFT(SUBSTR(@pname, length(@s2)+locate(@s2,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s2)+locate(@s2,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s2)+locate(@s2,@pname))))) )  ) as s3,
                        @s4 := IF(@s3='','',( LEFT(SUBSTR(@pname, length(@s3)+locate(@s3,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s3)+locate(@s3,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s3)+locate(@s3,@pname))))) )  ) as s4,
                        @s5 := IF(@s4='','',( LEFT(SUBSTR(@pname, length(@s4)+locate(@s4,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s4)+locate(@s4,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s4)+locate(@s4,@pname))))) )  ) as s5,
                        @s6 := IF(@s5='','',( LEFT(SUBSTR(@pname, length(@s5)+locate(@s5,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s5)+locate(@s5,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s5)+locate(@s5,@pname))))) )  ) as s6,
                        @s7 := IF(@s6='','',( LEFT(SUBSTR(@pname, length(@s6)+locate(@s6,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s6)+locate(@s6,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s6)+locate(@s6,@pname))))) )  ) as s7,
                        @s8 := IF(@s7='','',( LEFT(SUBSTR(@pname, length(@s7)+locate(@s7,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s7)+locate(@s7,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s7)+locate(@s7,@pname))))) )  ) as s8,
                        @s9 := IF(@s8='','',( LEFT(SUBSTR(@pname, length(@s8)+locate(@s8,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s8)+locate(@s8,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s8)+locate(@s8,@pname))))) )  ) as s9,
                        @s10 := IF(@s9='','',( LEFT(SUBSTR(@pname, length(@s9)+locate(@s9,@pname)),  If(LOCATE(' ', SUBSTR(@pname, length(@s9)+locate(@s9,@pname))) = 0, 999, LOCATE(' ', SUBSTR(@pname, length(@s9)+locate(@s9,@pname))))) )  ) as s10,
                        CONCAT(
                            SUBSTR(soundex(@s1),1),
                            '|',
                            SUBSTR(soundex(@s2),1),
                            '|',
                            SUBSTR(soundex(@s3),1),
                            '|',
                            SUBSTR(soundex(@s4),1),
                            '|',
                            SUBSTR(soundex(@s5),1),
                            '|',
                            SUBSTR(soundex(@s6),1),
                            '|',
                            SUBSTR(soundex(@s7),1),
                            '|',
                            SUBSTR(soundex(@s8),1),
                            '|',
                            SUBSTR(soundex(@s9),1),
                            '|',
                            SUBSTR(soundex(@s10),1),
                            '|'
                        ) as full_soundex
                    FROM {$productTable} e
                    INNER JOIN {$productVarcharTable} v ON e.entity_id = v.entity_id
                    WHERE v.attribute_id = {$nameAttributeId}
                    AND (v.store_id = {$storeId} OR v.store_id = 0)
                    AND {$sql_in1}
                );
            ");
        }
                
        return $this;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $conn
     * @param unknown_type $storeId
     * @param unknown_type $productIds
     * @return unknown
     */
    protected function _updateBssPns($conn, $storeId, $productIds)
    {
        $bi_t = $this->_getBssIndexTable();

        //@nelkaake -a 19/12/10:
        $sql_in = $this->getMixedArrayCondSql("names.product_id", $productIds, $conn);

        $sql = $conn->quoteInto("
REPLACE INTO `{$bi_t}`
SELECT
    names.entity_id as product_id,
    {$storeId} as store_id,
    names.full_soundex as pns,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL
FROM tmp_cf_fs names
WHERE TRUE AND {$sql_in}
;
        ", $productIds);
        $results = $conn->query($sql);

        return $this;
    }
}