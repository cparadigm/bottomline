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
 * @copyright  Copyright (c) 2012 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
 */

class TBT_Bss_Model_Mysql4_Cms_Fulltext extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('bss/cms_index', 'page_id');
    }

    /**
     * Regenerate CMS page search index for store(s)
     *
     * @param  int|null $storeId
     * @param  int|array|null $pageIds
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext
     */
    public function rebuildIndex($storeId = null, $pageIds = null)
    {
        if (is_null($storeId)) {
            $storeIds = array_keys(Mage::app()->getStores());
            foreach ($storeIds as $storeId) {
                $this->_rebuildStoreIndex( $storeId, $pageIds );
            }
        } else {
            $this->_rebuildStoreIndex( $storeId, $pageIds );
        }

        return $this;
    }

    /**
     * Check validation for page index
     *
     * @param int $storeId Store View Id
     * @param int $pageId Cms page Entity Id
     * @return Bool
     */
    public function isValidForIndex($storeId, $pageId = null)
    {
        if(empty($pageId)) {
            return false;
        }

        $storePages = explode(',', Mage::getStoreConfig( "bss/cms_search/page", $storeId ));

        if (!empty( $storePages ) && !in_array( $pageId, $storePages )) {
            return false;
        }

        return true;

    }

    /**
     * Regenerate CMS page search index for specific store
     *
     * @param int $storeId Store View Id
     * @param int|array $pageIds Cms page Entity Id
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext
     */
    protected function _rebuildStoreIndex($storeId, $pageIds = null)
    {
        if (!Mage::getStoreConfigFlag('bss/cms_search/cms', $storeId)) {
            return $this;
        }

        $this->cleanIndex($storeId, $pageIds);

        $processor = Mage::getSingleton('bss/core_email_template_filter');
        $processor->getDesignConfig()
            ->setStore($storeId)
            ->setArea(TBT_Bss_Model_Core_Email_Template_Filter::DEFAULT_DESIGN_AREA);

        $lastPageId = 0;
        while (true) {
            $pages = $this->_getSearchablePages($storeId, $pageIds, $lastPageId);
            if (! $pages) {
                break;
            }

            $pageIndexes = array();
            foreach ($pages as $pageData) {
                $lastPageId = $pageData['page_id'];
                if (! isset($pageData['page_id'])) {
                    continue;
                }

                // don't index 404 page and check with store configuration settings
                if ($pageData['identifier'] == 'no-route' || !$this->isValidForIndex($storeId,$pageData['page_id'])) {
                    if(Mage::getIsDeveloperMode()) {
                        Mage::helper('bss')->log("skiping page id: ".$pageData['page_id']." Store ID: ".$storeId);
                    }
                    continue;
                }

                $pageIndex = array();
                if (isset($pageData['page_title'])) {
                    $pageIndex[] = $pageData['page_title'];
                }

                if (isset($pageData['meta_keywords'])) {
                    $pageIndex[] = $pageData['meta_keywords'];
                }

                if (isset($pageData['meta_description'])) {
                    $pageIndex[] = $pageData['meta_description'];
                }

                if (isset($pageData['content'])) {
                    $content = '';
                    try {
                        $content = $processor->process($pageData['content']);
                    } catch (Exception $ex) {
                        Mage::helper('bss')->log("Processing page {$pageData['page_id']} failed...skipping");
                        throw $ex;
                    }

                        $content = Mage::helper('bss')->extractTextFromPage($content);
                    $pageIndex[] = $content;
                }

                $pageIndexes[$pageData['page_id']] = join('|', $pageIndex);
            }

            $this->_savePageIndexes($storeId, $pageIndexes);
        }

        $this->resetSearchResults();

        return $this;
    }

    /**
     * Delete CMS page search index data for store
     *
     * @param int $storeId Store View Id
     * @param int $pageId Product Entity Id
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext
     */
    public function cleanIndex($storeId = null, $pageId = null)
    {
        $where = array();

        if (!is_null($storeId)) {
            $where[] = $this->_getWriteAdapter()->quoteInto('store_id=?', $storeId);
        }
        if (!is_null($pageId)) {
            $where[] = $this->_getWriteAdapter()->quoteInto('page_id IN (?)', $pageId);
        }

        $this->_getWriteAdapter()->delete($this->getMainTable(), $where);

        return $this;

    }

    /**
     * Reset CMS page search results
     *
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext
     */
    public function resetSearchResults()
    {
        $adapter = $this->_getWriteAdapter();
        $adapter->update($this->getTable('catalogsearch/search_query'), array('is_cms_processed' => 0));
        $adapter->delete($this->getTable('bss/cms_result'));

        Mage::dispatchEvent('bss_reset_cms_search_result');

        return $this;
    }

    /**
     * Retrieve searchable CMS pages per store
     *
     * @param      $storeId
     * @param null $pageIds
     * @param int  $lastPageId
     * @param int  $limit
     *
     * @return array
     */
    protected function _getSearchablePages($storeId, $pageIds = null, $lastPageId = 0, $limit = 100)
    {
        $readAdapter = $this->_getReadAdapter();
        $select = $readAdapter->select()
            ->from(
                array('e' => $this->getTable('cms/page')),
                array('page_id', 'title', 'identifier', 'meta_keywords', 'meta_description', 'content')
        )
            ->joinInner(
                array('store' => $this->getTable('cms/page_store')),
                $readAdapter->quoteInto('store.page_id = e.page_id AND (store.store_id=? OR store.store_id=0)', $storeId),
                array()
        );

        if (! is_null($pageIds)) {
            $select->where('e.page_id IN (?)', $pageIds);
        }

        $select->where('e.is_active');
        $select->where('e.page_id > ?', $lastPageId)
            ->limit($limit)
            ->order('e.page_id');

        $result = $readAdapter->fetchAll($select);

        return $result;
    }

    /**
     * Save Multiply CMS page indexes
     *
     * @param $storeId
     * @param $pageIds
     *
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext
     */
    protected function _savePageIndexes($storeId, $pageIndexes)
    {
        $data = array();
        $storeId = (int)$storeId;
        foreach ($pageIndexes as $pageId => &$content) {
            $data[] = array(
                'page_id'   => (int)$pageId,
                'store_id'  => $storeId,
                'content'   => $content
            );
        }

        if ($data) {
            $this->_getWriteAdapter()
                ->insertOnDuplicate($this->getMainTable(), $data, array('content'));
        }

        return $this;
    }

    /**
     * Prepare results for query
     *
     * @param TBT_Bss_Model_Cms_Fulltext $object
     * @param string $queryText
     * @param Mage_CatalogSearch_Model_Query $query
     *
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext
     */
    public function prepareResult($object, $queryText, $query)
    {
        $adapter = $this->_getWriteAdapter();
        if (!$query->getIsCmsProcessed()) {
            $searchType = $object->getSearchType($query->getStoreId());

            $stringHelper = Mage::helper('core/string');
            /* @var $stringHelper Mage_Core_Helper_String */

            $bind = array(
                ':query' => $queryText
            );
            $like = array();

            $fulltextCond   = '';
            $likeCond       = '';
            $separateCond   = '';

            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE
                || $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE) {
                $words = $stringHelper->splitWords($queryText, true, $query->getMaxQueryWords());
                $likeI = 0;
                foreach ($words as $word) {
                    $like[] = '`s`.`content` LIKE :likew' . $likeI;
                    $bind[':likew' . $likeI] = '%' . $word . '%';
                    $likeI ++;
                }
                if ($like) {
                    $likeCond = '(' . join(' OR ', $like) . ')';
                }
            }
            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_FULLTEXT
                || $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE) {
                $fulltextCond = 'MATCH (`s`.`content`) AGAINST (:query IN BOOLEAN MODE)';
            }
            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE && $likeCond) {
                $separateCond = ' OR ';
            }

            $sql = sprintf("INSERT INTO `{$this->getTable('bss/cms_result')}` "
                    . "(SELECT STRAIGHT_JOIN '%d', `s`.`page_id`, MATCH (`s`.`content`) "
                    . "AGAINST (:query IN BOOLEAN MODE) FROM `{$this->getMainTable()}` AS `s` "
                    . "INNER JOIN `{$this->getTable('cms/page')}` AS `e` "
                    . "ON `e`.`page_id`=`s`.`page_id` WHERE (%s%s%s) AND `s`.`store_id`='%d')"
                    . " ON DUPLICATE KEY UPDATE `relevance`=VALUES(`relevance`)",
                $query->getId(),
                $fulltextCond,
                $separateCond,
                $likeCond,
                $query->getStoreId()
            );

            $this->_getWriteAdapter()->query($sql, $bind);

            $query->setIsCmsProcessed(1);
        }

        return $this;
    }
}