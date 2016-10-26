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
 * @author nelkaake
 *
 */
class TBT_Bss_Model_Indexer_Fulltext extends Mage_Index_Model_Indexer_Abstract
{
    /**
     * Indexer must be match entities
     *
     * @var array
     */
    protected $_matchedEntities = array(
        Mage_Catalog_Model_Product::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
            Mage_Index_Model_Event::TYPE_MASS_ACTION,
            Mage_Index_Model_Event::TYPE_DELETE
        ),
        Mage_Catalog_Model_Resource_Eav_Attribute::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
            Mage_Index_Model_Event::TYPE_DELETE,
        ),
        Mage_Core_Model_Store::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
            Mage_Index_Model_Event::TYPE_DELETE
        ),
        Mage_Core_Model_Store_Group::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Mage_Core_Model_Config_Data::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Mage_Catalog_Model_Convert_Adapter_Product::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        )
    );

    /**
     * Related Configuration Settings for match
     *
     * @var array
     */
    protected $_relatedConfigSettings = array(
        Mage_CatalogSearch_Model_Fulltext::XML_PATH_CATALOG_SEARCH_TYPE
    );

    /**
     * Retrieve Fulltext Search instance
     *
     * @return TBT_Bss_Model_Fulltext
     */
    protected function _getIndexer()
    {
        return Mage::getSingleton('bss/fulltext');
    }

    /**
     * Retrieve Indexer name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('catalogsearch')->__('Better Store Search Index');
    }

    /**
     * Retrieve Indexer description
     *
     * @return string
     */
    public function getDescription()
    {
        return Mage::helper('catalogsearch')->__('Rebuild special algorithm indexes for the Better Store Search catalog product & CMS page search engine.');
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $o
     * @return unknown
     */
    public function updateAfterMassReindex($o) {
        //@nelkaake -a 16/11/10: If controller is trying to rebuild Search Indexes (id = 7)
        $req = $o->getEvent()->getControllerAction()->getRequest();
        $post = $req->getPost();
        $reindex_ids = isset($post['process']) ?  $post['process'] : array();

        $indexer = Mage::getSingleton('index/indexer');
        $bssIndexer = $indexer->getProcessByCode('bss_fulltext');
        // if BSS is also re-indexed we can skip this
        if (array_search($bssIndexer->getProcessId(), $reindex_ids) !== false) {
            return $this;
        }

        if(array_search(7, $reindex_ids) !== false) {
            $this->updateAfterCatalogSearchReindex($o);
        }
        return $this;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $o
     * @return unknown
     */
    public function updateAfterIndivIndex($o) {
        //@nelkaake -a 16/11/10: If controller is trying to rebuild Search Indexes (id = 7)
        $req = $o->getEvent()->getControllerAction()->getRequest();
        $process_id = $req->getParam('process', null);
        if($process_id == 7) {
            $this->updateAfterCatalogSearchReindex($o);
        }

        return $this;
    }

    public function updateAfterCatalogSearchReindex($o) {
        //@nelkaake -a 16/11/10: Indexing rewrite is not used for Mage 1.3 or lower.
        if(!Mage::helper('bss/version')->isBaseMageVersionAtLeast('1.4')) {
            return $this;
        }

        $this->reindexAll();
    }

    /**
     * Check if event can be matched by process
     * Overwrote for check is flat catalog product is enabled and specific save
     * attribute, store, store_group
     *
     * @param Mage_Index_Model_Event $event
     * @return bool
     */
    public function matchEvent(Mage_Index_Model_Event $event)
    {
        $data       = $event->getNewData();
        $resultKey  = 'catalogsearch_fulltext_match_result';
        if (isset($data[$resultKey])) {
            return $data[$resultKey];
        }

        $result = null;
        $entity = $event->getEntity();
        if ($entity == Mage_Catalog_Model_Resource_Eav_Attribute::ENTITY) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            $attribute      = $event->getDataObject();

            if ($event->getType() == Mage_Index_Model_Event::TYPE_SAVE) {
                $result = $attribute->dataHasChangedFor('is_searchable');
            } else if ($event->getType() == Mage_Index_Model_Event::TYPE_DELETE) {
                $result = $attribute->getIsSearchable();
            } else {
                $result = false;
            }
        } else if ($entity == Mage_Core_Model_Store::ENTITY) {
            if ($event->getType() == Mage_Index_Model_Event::TYPE_DELETE) {
                $result = true;
            } else {
                /* @var $store Mage_Core_Model_Store */
                $store = $event->getDataObject();
                if ($store->isObjectNew()) {
                    $result = true;
                } else {
                    $result = false;
                }
            }
        } else if ($entity == Mage_Core_Model_Store_Group::ENTITY) {
            /* @var $storeGroup Mage_Core_Model_Store_Group */
            $storeGroup = $event->getDataObject();
            if ($storeGroup->dataHasChangedFor('website_id')) {
                $result = true;
            } else {
                $result = false;
            }
        } else if ($entity == Mage_Core_Model_Config_Data::ENTITY) {
            $data = $event->getDataObject();
            if (in_array($data->getPath(), $this->_relatedConfigSettings)) {
                $result = $data->isValueChanged();
            } else {
                $result = false;
            }
        } else {
            $result = parent::matchEvent($event);
        }

        $event->addNewData($resultKey, $result);

        return $result;
    }

    /**
     * Register data required by process in event object
     *
     * @param Mage_Index_Model_Event $event
     */
    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
        switch ($event->getEntity()) {
            case Mage_Catalog_Model_Product::ENTITY:
                $this->_registerCatalogProductEvent($event);
                break;

            case Mage_Catalog_Model_Convert_Adapter_Product::ENTITY:
                $event->addNewData('catalogsearch_fulltext_reindex_all', true);
                break;

            case Mage_Core_Model_Config_Data::ENTITY:
            case Mage_Core_Model_Store::ENTITY:
            case Mage_Catalog_Model_Resource_Eav_Attribute::ENTITY:
            case Mage_Core_Model_Store_Group::ENTITY:
                $event->addNewData('catalogsearch_fulltext_skip_call_event_handler', true);
                $process = $event->getProcess();
                $process->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
                break;
        }
    }

    /**
     * Register data required by catatalog product process in event object
     *
     * @param Mage_Index_Model_Event $event
     * @return Mage_CatalogSearch_Model_Indexer_Search
     */
    protected function _registerCatalogProductEvent(Mage_Index_Model_Event $event)
    {
        switch ($event->getType()) {
            case Mage_Index_Model_Event::TYPE_SAVE:
                /* @var $product Mage_Catalog_Model_Product */
                $product = $event->getDataObject();

                $event->addNewData('catalogsearch_update_product_id', $product->getId());
                break;
            case Mage_Index_Model_Event::TYPE_DELETE:
                /* @var $product Mage_Catalog_Model_Product */
                $product = $event->getDataObject();

                $event->addNewData('catalogsearch_delete_product_id', $product->getId());
                break;
            case Mage_Index_Model_Event::TYPE_MASS_ACTION:
                /* @var $actionObject Varien_Object */
                $actionObject = $event->getDataObject();

                $reindexData  = array();
                $rebuildIndex = false;

                // check if status changed
                $attrData = $actionObject->getAttributesData();
                if (isset($attrData['status'])) {
                    $rebuildIndex = true;
                    $reindexData['catalogsearch_status'] = $attrData['status'];
                }

                // check changed websites
                if ($actionObject->getWebsiteIds()) {
                    $rebuildIndex = true;
                    $reindexData['catalogsearch_website_ids'] = $actionObject->getWebsiteIds();
                    $reindexData['catalogsearch_action_type'] = $actionObject->getActionType();
                }

                // register affected products
                if ($rebuildIndex) {
                    $reindexData['catalogsearch_product_ids'] = $actionObject->getProductIds();
                    foreach ($reindexData as $k => $v) {
                        $event->addNewData($k, $v);
                    }
                }
                break;
        }

        return $this;
    }

    /**
     * Process event
     *
     * @param Mage_Index_Model_Event $event
     */
    protected function _processEvent(Mage_Index_Model_Event $event)
    {
        $data = $event->getNewData();
        if (!empty($data['catalogsearch_fulltext_reindex_all'])) {
            $this->reindexAll();
        } else if (!empty($data['catalogsearch_delete_product_id'])) {
            $productId = $data['catalogsearch_delete_product_id'];
            //@nelkaake -d 5/11/10: Search indexes will do this for us so this is unneccesary
            //$this->_getIndexer()->cleanIndex(null, $productId)
            //    ->resetSearchResults();
        } else if (!empty($data['catalogsearch_update_product_id'])) {
            $productId = $data['catalogsearch_update_product_id'];
            $this->_getIndexer()->rebuildCatalogIndex(null, $productId)
                ->resetSearchResults();
        } else if (!empty($data['catalogsearch_product_ids'])) {
            // mass action
            $productIds = $data['catalogsearch_product_ids'];

            if (!empty($data['catalogsearch_website_ids'])) {
                $websiteIds = $data['catalogsearch_website_ids'];
                $actionType = $data['catalogsearch_action_type'];

                foreach ($websiteIds as $websiteId) {
                    foreach (Mage::app()->getWebsite($websiteId)->getStoreIds() as $storeId) {
                        if ($actionType == 'remove') {
                            //@nelkaake -d 5/11/10: Search indexes will do this for us so this is unneccesary
                            //$this->_getIndexer()
                            //    ->cleanIndex($storeId, $productIds)
                            //    ->resetSearchResults();
                        } else if ($actionType == 'add') {
                            $this->_getIndexer()
                                ->rebuildCatalogIndex($storeId, $productIds)
                                ->resetSearchResults();
                        }
                    }
                }
            }
            if (isset($data['catalogsearch_status'])) {
                $status = $data['catalogsearch_status'];
                if ($status == Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {
                    $this->_getIndexer()
                        ->rebuildCatalogIndex(null, $productIds)
                        ->resetSearchResults();
                } else {
                    //@nelkaake -d 5/11/10: Search indexes will do this for us so this is unneccesary
                    //$this->_getIndexer()
                    //    ->cleanIndex(null, $productIds)
                    //    ->resetSearchResults();
                }
            }
        }
    }

    /**
     * Rebuild all index data
     *
     */
    public function reindexAll()
    {
        $this->_getIndexer()->rebuildIndex();
    }
}
