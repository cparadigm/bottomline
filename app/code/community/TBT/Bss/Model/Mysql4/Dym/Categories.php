<?php

class TBT_Bss_Model_Mysql4_Dym_Categories extends TBT_Bss_Model_Mysql4_Dym_Abstract
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
    public function updateCategoriesIndexes($storeId = null, $productIds = null, $forceToUpdate = false)
    {
        if (!Mage::helper('bss/config')->isCategoryMatchEnabled() && !$forceToUpdate){
            return $this;
        }

        $cat_index_t   = Mage::getConfig()->getTablePrefix(). 'catalog_category_product_index';
        $cat_name_t    = Mage::getConfig()->getTablePrefix(). 'catalog_category_entity_varchar';
        $cat_status_t  = Mage::getConfig()->getTablePrefix(). 'catalog_category_entity_int';
        $viewWhereText = "";

        $cf_t       = $this->_getBssIndexTable();
        $conn       = Mage::getSingleton('core/resource')->getConnection('core_read');
        $eav_name   = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_category', 'name');
        $eav_status = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_category', 'is_active');

        //remove disabled categories
        $viewWhere[] = "cat_status.value = 1";

        if ($storeId) {
            $viewWhere[] = "cat_index.store_id = {$storeId}";
        }

        if (!empty($viewWhere)) {
            $viewWhereText = " WHERE ". implode(" AND ", $viewWhere);
        }

        $sql = "
            UPDATE `{$cf_t}` `cf`
                JOIN (
                    SELECT cat_index.product_id pid,cat_index.store_id store_id,GROUP_CONCAT(cat_name.value SEPARATOR \"|\") c_names, GROUP_CONCAT(cat_name.entity_id SEPARATOR \"|\") cat_ids
                    FROM `{$cat_index_t}` cat_index
                    LEFT JOIN `{$cat_name_t}` cat_name ON cat_name.entity_id = cat_index.category_id
                        AND cat_name.attribute_id = {$eav_name->getId()}
                    LEFT JOIN `{$cat_status_t}` cat_status ON cat_status.entity_id = cat_index.category_id
                        AND cat_status.attribute_id = {$eav_status->getId()}
                    {$viewWhereText}
                    GROUP BY cat_index.product_id,cat_index.store_id
                     ) a
            SET categories = a.c_names, category_ids = a.cat_ids
            WHERE cf.product_id = a.pid AND cf.store_id = a.store_id
           ";

        $results = $conn->query($sql);
        $conn->commit();

        return $results;
    }
}
