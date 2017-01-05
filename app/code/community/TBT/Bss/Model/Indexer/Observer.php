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

class TBT_Bss_Model_Indexer_Observer extends Varien_Object
{
    /**
     * Cms page indexer resource model
     *
     * @var
     */
    protected $_indexer;

    protected  function _construct()
    {
        $this->_indexer = Mage::getResourceSingleton('bss/cms_fulltext');
    }

    public function processCmsPageSave(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        if (! $event) {
            return $this;
        }

        $page = $event->getDataObject();
        if (! $page) {
            return $this;
        }
        // rebuild index for this specific CMS page
        $this->_indexer
            ->rebuildIndex(null, $page->getPageId());

        return $this;
    }

    public function processCmsPageDelete(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        if (! $event) {
            return $this;
        }

        $page = $event->getDataObject();
        if (! $page) {
            return $this;
        }
        // clean index for this specific CMS page and reset CMS search results
        $this->_indexer
            ->cleanIndex(null, $page->getPageId())
            ->resetSearchResults();

        return $this;
    }

    public function processCategoriesIndexes(Varien_Event_Observer $observer)
    {
        Mage::getModel("catalogsearch/fulltext")->resetSearchResults();

        if (Mage::helper('bss/config')->isCategoryMatchEnabled()) {
            $process = Mage::getSingleton('index/indexer')->getProcessByCode("catalog_category_product");
            if ($process) {
                $process->reindexEverything();
            }
            Mage::getModel("bss/mysql4_dym_categories")->updateCategoriesIndexes(null, null, true);
        }
    }

    /*
     * Update sproduct tag
     *
     * Event: tag_save_after
     */
    public function processIndexer(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        if (! $event){
            return $this;
        }

        // Status(Approve/ Pending/ Disable) checking when run sql, Because have to recreate tag string
        if (Mage::helper('bss/config')->isTagMatchEnabled()) {
            try {
                Mage::getModel("bss/mysql4_dym_tag")->updateTagIndexes();
            } catch (Exception $e) {
                Mage::helper('bss')->log($e->getMassage());
            }
        }

        return $this;
    }

}
