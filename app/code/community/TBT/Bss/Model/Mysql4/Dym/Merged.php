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
class TBT_Bss_Model_Mysql4_Dym_Merged extends TBT_Bss_Model_Mysql4_Dym_Abstract
{

    public function _construct() {
    }


    /**
     *
     * @param unknown_type $query
     * @return unknown
     */
    public function findBySku($query) {
        $cf_t = $this->_getBssIndexTable();
        $conn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = $conn->quoteInto("
SELECT DISTINCT product_id
FROM {$cf_t} cf
WHERE TRUE
  AND cf.merged_sku LIKE CONCAT('%', LOWER(
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(?, '-', '')
                , ' ', '')
              , '/', '')
            , '\"', '')
          , CHAR(39), '')
        , '.', '')
      ,'_', '')
    )
  , '%');
        ", $query);
        $result_ids = $conn->fetchCol($sql);
        return $result_ids;
    }

    /**
     *
     * @param unknown_type $query
     * @return unknown
     */
    public function findByMergedName($query) {
        $cf_t = $this->_getBssIndexTable();
        $conn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = $conn->quoteInto("
SELECT DISTINCT product_id
FROM {$cf_t} cf
WHERE TRUE
  AND cf.merged_name LIKE CONCAT('%', LOWER(
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(?, '-', '')
                , ' ', '')
              , '/', '')
            , '\"', '')
          , CHAR(39), '')
        , '.', '')
      ,'_', '')
    )
  , '%');
        ", $query);
        $result_ids = $conn->fetchCol($sql);
        return $result_ids;
    }




    /**
     * Regenerate search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    public function updateMergedIndexes($storeId, $productIds = null)
    {
        $eav_name = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'name');
        $naid = $eav_name->getId();
        $cpev_t = Mage::getConfig()->getTablePrefix(). 'catalog_product_entity_varchar';
        $cpe_t = Mage::getConfig()->getTablePrefix(). 'catalog_product_entity';
        $cf_t = $this->_getBssIndexTable();
        $conn = Mage::getSingleton('core/resource')->getConnection('core_read');

        //@nelkaake -a 19/12/10:
        $sql_in1 = $this->getMixedArrayCondSql("cf.product_id", $productIds, $conn);

        $conn->beginTransaction();

        $sql = "
UPDATE {$cf_t} cf
LEFT JOIN  `{$cpe_t}` skus ON ( cf.product_id = skus.entity_id)
LEFT JOIN  `{$cpev_t}` names0 ON ( skus.entity_id= names0 .entity_id AND names0 .store_id = cf.store_id AND names0.attribute_id = {$naid} )
LEFT JOIN  `{$cpev_t}` names2 ON ( skus.entity_id= names2.entity_id AND names2.store_id = 0 AND names2.attribute_id = {$naid})
SET cf.merged_name = LOWER(
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(IF(names0 .value IS NULL, names2.value, names0 .value), '-', '')
                , ' ', '')
              , '/', '')
            , '\"', '')
          , CHAR(39), '')
        , '.', '')
      ,'_', '')
    ),
cf.merged_sku =
    LOWER(
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(skus.sku, '-', '')
                , ' ', '')
              , '/', '')
            , '\"', '')
          , CHAR(39), '')
        , '.', '')
      ,'_', '')
    )
    WHERE {$sql_in1} AND cf.store_id = {$storeId}

;
        ";
        $results = $conn->query($sql);

        $conn->commit();

        return $results;
    }



}