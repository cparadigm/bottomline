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
class TBT_Bss_Model_Mysql4_Dym extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct() {
    }

    public function getSuggestedPids($query) {
        $eav_name = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'name');
        $naid = $eav_name->getId();
        $cpev_t = Mage::getConfig()->getTablePrefix(). 'catalog_product_entity_varchar';
        $conn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = $conn->quoteInto("

SELECT cpev.entity_id
FROM {$cpev_t} cpev
WHERE attribute_id = {$naid}
ORDER BY soundex_full_match(?, cpev.value, ' ') DESC
LIMIT 1;
        ", $query);
        $result_ids = $conn->fetchCol($sql);
        return $result_ids;
    }

    /**
     * returns bss_index (search index) table
     *
     * @return string
     */
    protected function _getBssIndexTable() {
        return Mage::getConfig()->getTablePrefix(). 'bss_index';
    }

    /**
     * returns catalogsearch_fulltext (search index) table
     *
     * @return string
     */
    protected function _getCFTable() {
        return Mage::getConfig()->getTablePrefix(). 'catalogsearch_fulltext';
    }

    public function findBySku($query) {
        return $this->_getMergedIndexResource()->findBySku($query);
    }

    public function findByMergedName($query) {
        return $this->_getMergedIndexResource()->findByMergedName($query);
    }

    public function findBySoundex($query) {
        return $this->_getSoundexIndexResource()->findBySoundex($query);
    }

    public function findPhraseBySoundex($query) {
        return $this->_getAutocorrectResource()->findPhraseBySoundex($query);
    }

    protected function getConnection() {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }


    /**
     * Regenerate search index for store(s)
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id(s)
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    public function rebuildIndex($storeId = null, $productIds = null)
    {
        Varien_Profiler::start("TBT_Bss: Rebuild BSS index");
        if (is_null($storeId)) {
            foreach (Mage::app()->getStores(false) as $store) {
                $this->_rebuildStoreIndex2($store->getId(), $productIds);
            }
        } else {
            $this->_rebuildStoreIndex2($storeId, $productIds);
        }
        Varien_Profiler::stop("TBT_Bss: Rebuild BSS index");
        return $this;
    }

    /**
     * Regenerate search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    protected function _rebuildStoreIndex2($storeId, $productIds = null)
    {
        //$tr = $tstart = time();
        $names_data = $this->_readIndexNamesData($storeId, $productIds);
        $names_data = array_merge($names_data, $names_data, $names_data, $names_data, $names_data, $names_data);
        //$tr = time() - $tr;
        //echo "Reading ". sizeof($names_data) . " total rows...<BR />\n"; flush();

        //$tt = time();

        //@nelkaake note: this must run before the merged indexes:
        $this->_quickUpdatePnsIndexes($storeId, $productIds);

        $this->_updateMergedIndexes($storeId, $productIds);
        //$tt = time() - $tt;

        $this->_updateTagIndexes($storeId, $productIds);

        $this->_updateCategoriesIndexes($storeId, $productIds);

        //die("Total time to read was {$tr}. \n {$tt} spent updating the DB.  Total: ". (time()-$tstart));
        return $this;


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
        return $this->_getSoundexIndexResource()->quickUpdatePnsIndexes($storeId, $productIds);
    }


    /**
     * Regenerate search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    protected function _updateMergedIndexes($storeId, $productIds = null)
    {
        return $this->_getMergedIndexResource()->updateMergedIndexes($storeId, $productIds);
    }

    /**
     * Regenerate search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $productIds Product Entity Id
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext
     */
    protected function _updateTagIndexes($storeId, $productIds = null)
    {
        return $this->_getTagIndexResource()->updateTagIndexes($storeId, $productIds);
    }

    protected function _updateCategoriesIndexes($storeId, $productIds = null)
    {
        return $this->_getCategoriesIndexResource()->updateCategoriesIndexes($storeId, $productIds);
    }


    /**     * @deprecated      */
    protected function _rebuildStoreIndex($storeId, $productIds = null)    {        return $this;    }
    /**     * @deprecated      */
    protected function _readIndexNamesData($storeId, $productIds = null)    {        $results = array();         return $results;    }
    /**     * @deprecated use _quickUpdatePnsIndexes     */
    protected function _writePnsIndex($index_entry) {         return $this;    }
    /**     * @deprecated use _quickUpdatePnsIndexes     */
    protected function _writePnsIndex2($index_entry) {        return $this;    }
    /**  * @deprecated   */
    public function rebuildSI($storeIds = null, $productIds=null) {     return $this;   }



    /**
     * @return TBT_Bss_Model_Mysql4_Dym_Merged
     */
    protected function _getMergedIndexResource() {
        return Mage::getResourceModel('bss/dym_merged');
    }
    /**
     * @return TBT_Bss_Model_Mysql4_Dym_Soundex
     */
    protected function _getSoundexIndexResource() {
        return Mage::getResourceModel('bss/dym_soundex');
    }
    /**
     * @return TBT_Bss_Model_Mysql4_Dym_Soundex
     */
    protected function _getAutocorrectResource() {
        return Mage::getResourceModel('bss/dym_autocorrect');
    }

    /**
     * @return TBT_Bss_Model_Mysql4_Dym_Tag
     */
    protected function _getTagIndexResource() {
        return Mage::getResourceModel('bss/dym_tag');
    }

    /**
     * @return TBT_Bss_Model_Mysql4_Dym_Categories
     */
    protected function _getCategoriesIndexResource() {
        return Mage::getResourceModel('bss/dym_categories');
    }

}