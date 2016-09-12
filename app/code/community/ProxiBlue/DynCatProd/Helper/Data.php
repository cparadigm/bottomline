<?php

/**
 * Helper functions
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Helper_Data
    extends Mage_Core_Helper_Abstract
{

    /**
     * Generate a list of Product Ids that validate against the rules
     *
     * @param  object $category
     * @return type
     */
    public function getDynamicProductIds($category)
    {
        try {
            if (is_object($category)) {
                $mainCollectionIds = array();
                $collection = $category->getProductCollection();
                if ($category->getDynamicAttributes() || strlen(trim($category->getDynamicAttributes())) > 0) {
                    $ruleModel = Mage::getSingleton('dyncatprod/rule');
                    $ruleModel->preLoadPost(
                        array('conditions' => $category->getDynamicAttributes()),
                        $category
                    );
                    // remove from the collection the links to the flat tables
                    // we do not want to use flat tables as they may not contain all the
                    // attributes that are present in the rules.
                    $this->removeCatProPart($collection);
                    $collection->getSelect()->distinct(true);
                    $collection->getSelect()->group('e.entity_id');
                    $this->debug(
                        "Collection after removed flat tables, adding in distinct and group: "
                        . $collection->getSelect()
                    );
                    $object = new Varien_Object();
                    $object->setCollection($collection);
                    $object->setCategory($category);
                    $result = $ruleModel->validate($object);
                    if ($result == false) {
                        return false;
                    }

                }
                // was transformation rules flagged to run?
                // they must run at the end, after collection was built, and where collectors been integrated
                // into the collection
                if ($collection->getFlag('transform_parents'))
                {
                    $collection->getFlag('transform_parents')->validateLater($object);
                }

                // may have been replaced entirely (salesrules as an example)
                $collection = $object->getCollection();

                if ($collection->getFlag('replace_ids')) {
                    $this->debug(
                        "FINAL COLLECTION BYPASSED BY REPLACED IDS: " . implode(
                            ',',
                            $collection->getFlag('replace_ids')
                        )
                    );
                    $mainCollectionIds = $collection->getFlag('replace_ids');

                    return $mainCollectionIds;
                }
                //skip collection build if there is no WHERE clause - no filters were set
                //and if categoryControl is the only used rule in the set.
                $wherePart = $collection->getSelect()->getPart(Zend_Db_Select::WHERE);
                if ($collection->getFlag('category_control')
                    && is_array($wherePart)
                    && count($wherePart) == 0
                    && $collection->getFlag('applied_catalog_rule_id') != true
) {
                    $this->debug(
                        "FINAL COLLECTION: NONE - No WHERE part defined, thus no rules to filter - "
                        . $collection->getSelect()
                    );
                } else {
                    $mainCollectionIds = array();
                    $this->debug("FINAL COLLECTION: " . $collection->getSelect());
                    $items = $collection->load()->getItems();
                    if (is_array($items)) {
                        $mainCollectionIds = array_keys($items);
                    }
                }

                    $category->setCategoryControl($collection->getFlag('category_control'));

                    return $mainCollectionIds;
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            mage::logException($e);
        }
    }

    /**
     * Remove Catalog Product Link elements from collection
     *
     * @param  type $collection
     * @return type
     */
    public function removeCatProPart($collection)
    {
        $select = $collection->getSelect();
        $fromPart = $select->getPart(Zend_Db_Select::FROM);
        $select->reset(Zend_Db_Select::FROM);
        if (array_key_exists(
            'cat_pro',
            $fromPart
        )) {
            unset($fromPart['cat_pro']);
            // also remove any reference to the table in the rest of the query
            $columns = $select->getPart(Zend_Db_Select::COLUMNS);
            $columnRemoved = false;
            foreach ($columns as $columnKey => $column) {
                if ($column[0] == 'cat_pro') {
                    unset($columns[$columnKey]);
                    $columnRemoved = true;
                }
            }
            if ($columnRemoved) {
                $select->setPart(
                    Zend_Db_Select::COLUMNS,
                    $columns
                );
            }
            $orderPart = $select->getPart(Zend_Db_Select::ORDER);
            $orderRemoved = false;
            foreach ($orderPart as $orderKey => $order) {
                if ($order[0] == 'cat_pro') {
                    unset($orderPart[$orderKey]);
                    $orderRemoved = true;
                }
            }
            if ($orderRemoved) {
                $select->setPart(
                    Zend_Db_Select::ORDER,
                    $orderPart
                );
            }
        }
        $select->setPart(
            Zend_Db_Select::FROM,
            $fromPart
        );

        return $collection;
    }

    public function addCategoryControl($controlData, $collection)
    {
        $currentControlData = $collection->getFlag('category_control');
        if (!is_array($currentControlData)) {
            $currentControlData = array($controlData);
        } else {
            $currentControlData[] = $controlData;
        }
        $collection->setFlag(
            'category_control',
            $currentControlData
        );
    }

    /**
     * Reindex
     */
    public function reindex()
    {
        if (Mage::getStoreConfig('dyncatprod/rebuild/ignore_indexers')) {
            return $this;
        }
        if (Mage::getStoreConfig('dyncatprod/rebuild/disable_indexers')) {
            $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
            foreach ($pCollection as $process) {
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log('Dynamic categories build enabling indexer: ' . $process->getIndexerCode());
                }
                $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log('Dynamic categories rebuilding indexer: ' . $process->getIndexerCode());
                }
                $process->indexEvents();
            }
        }
    }

    /**
     * disable all indexes
     */
    public function disableIndexes()
    {
        if (Mage::getStoreConfig('dyncatprod/rebuild/ignore_indexers')) {
            return $this;
        }
        if (Mage::getStoreConfig('dyncatprod/rebuild/disable_indexers')) {
            $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
            foreach ($pCollection as $process) {
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log('Dynamic categories build disabling indexer: ' . $process->getIndexerCode());
                }
                $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
            }
        }
    }

    /**
     * Common routine to rebuild category.
     * Allows observer, cron and cli to run teh same code, thus allowing for consitency
     *
     * @param type $category
     */
    public function rebuildCategory($category, $isCron = false)
    {
        $products = $this->getDynamicProductIds($category);
        $productsToAdd = array();
        if (is_array($products) && count($products) > 0) {
            $products = array_filter(
                $products,
                'is_numeric'
            );
            //failsafe, to prevent integrety constraint
            //Make sue the products actually exist. Some rules could be using sales
            // data (like sales reports) and try and insert products that have been deleted from the catalog
            if (count($products) > 0) {
                $failsafeProductCollection = mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('entity_id')
                    ->addFieldToFilter(
                        'entity_id',
                        array('IN' => $products)
                    )
                    ->load();
                $failsafeProductIds = $failsafeProductCollection->getAllIds();
                $noLongerValidProducts = array_diff(
                    $products,
                    $failsafeProductIds
                );
                if (count($noLongerValidProducts) > 0) {
                    foreach ($noLongerValidProducts as $key => $noLongerValid) {
                        mage::log(
                            'Dynamic categories could not add product to category as product no longer exists : '
                            . $noLongerValid
                        );
                        unset($products[$key]);
                    }
                }
                $products = array_flip($products);
            }
            $category->setDynamicProducts($products);
            if (!Mage::getStoreConfig('dyncatprod/rebuild/max_exec')) {
                ini_set(
                    'max_execution_time',
                    Mage::getStoreConfig('dyncatprod/rebuild/max_exec_time')
                );
            }
            $resourceModel = Mage::getResourceModel('dyncatprod/category');
            $currentDynamicProducts = $resourceModel->getCurrentDynamicProducts($category);
            if ($category->getIgnorePostedProducts()) {
                $currentPostedProducts = array();
            } else {
                $currentPostedProducts = $category->getPostedProducts();
            }
            // filter out any dynamic products
            $nonDynamicProducts = array_diff_key(
                $currentPostedProducts,
                $currentDynamicProducts
            );
            // and keep any set positions for the current dynamic products into the new to assign products
            // I am sure there is a smart internal method to do this with, but I am not getting it today,
            // so a loop it is
            if (!$category->getIgnoreManualPositions()) {
                foreach ($currentPostedProducts as $productId => $position) {
                    if (array_key_exists(
                        $productId,
                        $products
                    )) {
                        $products[$productId] = $position;
                    }
                }
            }
            $productsToAdd = $products + $nonDynamicProducts;
            $category->setPostedProducts($productsToAdd);
            $category->setIsDynamic(true);
        } else {
            // remove all the dynamic products from this category
            $category->setRemoveAllDynamic(true);
        }
        // determine any special category controll actions.
        $categoryControl = $category->getCategoryControl();
        if ($categoryControl) {
            foreach ($categoryControl as $control) {
                //action on parent?
                if ($control->getValue() == 'parent') {
                    $parentCatId = $category->getParentId();
                    $parentCategory = mage::getModel('catalog/category')->load($parentCatId);
                    if ($parentCategory->getid()) {
                        // ok, so we have a parent, lets get all the children, or itself if none
                        if ($parentCategory->hasChildren()) {
                            $categoryLimit = explode(
                                ',',
                                $parentCategory->getAllChildren()
                            );
                        } else {
                            $categoryLimit = $parentCategory->getId();
                        }
                        // let see if there are any products in any of this
                        $productCollection = Mage::getResourceModel('catalog/product_collection')
                            //->setStoreId(Mage::app()->getStore())
                            ->joinField(
                                'category_id',
                                'catalog/category_product',
                                'category_id',
                                'product_id=entity_id',
                                null,
                                'left'
                            )
                            ->addAttributeToFilter(
                                'category_id',
                                array('in' => $categoryLimit)
                            )
                            //->addAttributeToSelect('*')
                            ->addAttributeToFilter(
                                'status',
                                Mage_Catalog_Model_Product_Status::STATUS_ENABLED
                            );
                        $productCollection->getSelect()->group('product_id')->distinct(true);
                        $productCollection->load();
                        foreach ($control->getConditions() as $cond) {
                            if ($control->getAggregator() == 'any' && count($productCollection->getItems()) > 0) {
                                $cond->validate($parentCategory);
                                $parentCategory->save();
                            } elseif ($control->getAggregator() == 'none' && count($productCollection->getItems()) == 0) {
                                $cond->validate($parentCategory);
                                $parentCategory->save();
                            }
                        }
                    }
                } else {
                    foreach ($control->getConditions() as $cond) {
                        if ($control->getAggregator() == 'any' && count($productsToAdd) > 0) {
                            $cond->validate($category);
                        } elseif ($control->getAggregator() == 'none' && count($productsToAdd) == 0) {
                            $cond->validate($category);
                        }
                    }
                }
            }
        }
        if ($isCron && mage::getStoreConfig('dyncatprod/notify/enabled') && count($category->getPostedProducts()) < mage::getStoreConfig('dyncatprod/notify/product_notify_count')) {
            $message = Mage::helper('core')->__(
                "Category '%s' (%s) was rebuilt with %s products.<br/><br/>"
                . "Notification is set to warn when less than %s products are in the category",
                $category->getName(),
                $category->getId(),
                count($category->getPostedProducts()),
                mage::getStoreConfig('dyncatprod/notify/product_notify_count')
            );
            $this->sendEmail(
                $this->__(
                    "Dynamic Category Products Notification for %s",
                    $category->getName()
                ),
                $message
            );
        }
    }

    /**
     * Common debugger helper
     *
     * @param string $message
     */
    public function debug($message)
    {
        if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
            mage::log(
                $message,
                Zend_Log::DEBUG,
                'dyncatprod.log',
                false
            );
        }
    }

    /**
     * Test if this is a pre 1.6 install
     * @return boolean
     */
    public function isPre16()
    {
        $magentoVersion = Mage::getVersionInfo();
        if ($magentoVersion['minor'] < 6) {
            return true;
        }

        return false;
    }

    /**
     * Email notifications
     * @return boolean
     */
    public function sendEmail($subject, $message)
    {
        try {
            $mail = new Zend_Mail();
            $sender = mage::getStoreConfig('dyncatprod/notify/identity_from');
            $rec = mage::getStoreConfig('dyncatprod/notify/identity_to');
            $mail->setFrom(
                Mage::getStoreConfig('trans_email/ident_' . $sender . '/email'),
                Mage::getStoreConfig('trans_email/ident_' . $sender . '/name')
            );
            $mail->addTo(
                Mage::getStoreConfig('trans_email/ident_' . $rec . '/email'),
                Mage::getStoreConfig('trans_email/ident_' . $rec . '/name')
            );
            $mail->setSubject($subject);
            $mail->setBodyHtml($message);
            ini_set(
                'SMTP',
                Mage::getStoreConfig('system/smtp/host')
            );
            ini_set(
                'smtp_port',
                Mage::getStoreConfig('system/smtp/port')
            );
            $modules = Mage::getConfig()->getNode('modules')->children();
            $modulesArray = (array) $modules;
            if (isset($modulesArray['Aschroder_SMTPPro'])) {
                $transport = Mage::helper('smtppro')->getTransport(0);
                $mail->send($transport);
            } else {
                $mail->send();
            }
        } catch (Exception $e) {
            mage::logException($e);
            mage::log("Could not send notification email. Please check exception log for details.");
        }

        return true;
    }

    /*
     * Get the correct column name to use in sql inserted into collections.
     * Magento versions seem to have chnaged the naming conventions.
     *
     * @param  type $columns
     * @param  type $field
     * @return type
     */

    public function getColumnName($columns, $field)
    {
        foreach ($columns as $column) {
            if (array_key_exists(
                '2',
                $column
            ) && $column[2] == $field) {
                return $column[0];
            }
        }
        mage::log('Could not determine column name for field ' . $field);

        return $field;
    }

}
