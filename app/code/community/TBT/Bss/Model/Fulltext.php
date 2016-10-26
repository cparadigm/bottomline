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

class TBT_Bss_Model_Fulltext extends Mage_Core_Model_Abstract
{
    const SEARCH_TYPE_LIKE              = 1;
    const SEARCH_TYPE_FULLTEXT          = 2;
    const SEARCH_TYPE_COMBINE           = 3;
    const XML_PATH_CATALOG_SEARCH_TYPE  = 'catalog/search/search_type';

    const SEARCH_MODE_OR                = 1;
    const SEARCH_MODE_AND               = 2;
    const XML_PATH_BSS_SEARCH_MODE      = 'bss/special/search_mode';


    /**
     *
     * @return TBT_Bss_Model_Mysql4_Dym
     */
    public function getDymResource() {
        return Mage::getResourceModel('bss/dym');
    }

    public function getCmsResource() {
        return Mage::getResourceModel('bss/cms_fulltext');
    }

    /**
     * Regenerate all Stores index
     *
     * Examples:
     * (null, null) => Regenerate index for all stores
     * (1, null)    => Regenerate index for store Id=1
     * (1, 2)       => Regenerate index for product Id=2 and its store view Id=1
     * (null, 2)    => Regenerate index for all store views of product Id=2
     *
     * @param int $storeId Store View Id
     * @param int $dataId Product|Cms Page Entity Id
     *
     * @return TBT_Bss_Model_Fulltext
     */
    public function rebuildIndex($storeId = null, $dataId = null)
    {
        $this->getDymResource()->rebuildIndex($storeId, $dataId);
        $this->getCmsResource()->rebuildIndex($storeId, $dataId);

        return $this;
    }

    /**
     * Regenerate all Stores index
     *
     * Examples:
     * (null, null) => Regenerate index for all stores
     * (1, null)    => Regenerate index for store Id=1
     * (1, 2)       => Regenerate index for product Id=2 and its store view Id=1
     * (null, 2)    => Regenerate index for all store views of product Id=2
     *
     * @param int $storeId Store View Id
     * @param int $productId Product Entity Id
     *
     * @return TBT_Bss_Model_Fulltext
     */
    public function rebuildCatalogIndex($storeId = null, $productId = null)
    {
        $this->getDymResource()->rebuildIndex($storeId, $productId);
        return $this;
    }

    /**
     * Regenerate all Stores index
     *
     * Examples:
     * (null, null) => Regenerate index for all stores
     * (1, null)    => Regenerate index for store Id=1
     * (1, 2)       => Regenerate index for page Id=2 and its store view Id=1
     * (null, 2)    => Regenerate index for all store views of page Id=2
     *
     * @param int $storeId Store View Id
     * @param int $pageId Cms Page Entity Id
     *
     * @return TBT_Bss_Model_Fulltext
     */
    public function rebuildCmsIndex($storeId = null, $pageId = null)
    {
        $this->getCmsResource()->rebuildIndex($storeId, $pageId);
        return $this;
    }

    /**
     * Delete index data
     *
     * Examples:
     * (null, null) => Clean index of all stores
     * (1, null)    => Clean index of store Id=1
     * (1, 2)       => Clean index of product Id=2 and its store view Id=1
     * (null, 2)    => Clean index of all store views of product Id=2
     *
     * @param int $storeId Store View Id
     * @param int $productId Product Entity Id
     * @return Mage_CatalogSearch_Model_Fulltext
     */
    public function cleanIndex($storeId = null, $productId = null)
    {
        //@nelkaake -a 5/11/10: TODO No cleaning operation yet. Cleaning search indexes will also clean DYM data.
        //$this->getDymResource()->cleanIndex($storeId, $productId);
        return $this;
    }

    /**
     * Reset search results cache
     *
     * @return Mage_CatalogSearch_Model_Fulltext
     */
    public function resetSearchResults()
    {
        //@nelkaake -a 5/11/10: Also doesn't need to be implemented as long as the DYM system adds on to the Magento Search Index system
        //$this->getResource()->resetSearchResults();
        return $this;
    }

}
