<?php

/**
 * Observer events
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Adminhtml_Observer
{

    /**
     * Event to add Tab to Category edit display
     *
     * @param  Varien_Event_Observer $observer
     * @return ProxiBlue_DynCatProd_Model_Adminhtml_Observer
     */
    public function adminhtml_catalog_category_tabs(Varien_Event_Observer $observer)
    {
        try {
            $tabs = $observer->getEvent()->getTabs();

            // place in layout file !
            Mage::app()->getLayout()->getBlock('head')->addJs('mage/adminhtml/rules.js');
            Mage::app()->getLayout()->getBlock('head')->addJs('dyncatprod/ext-rules.js');

            /**
 * the main tab block * */
            $tabBlock = $tabs->getLayout()->createBlock(
                'dyncatprod/adminhtml_catalog_category_tab_dyncatprod', 'category.dyncatprod.tab'
            );
            /**
 * the rules block * */
            $rulesBlock = $tabs->getLayout()->createBlock(
                'dyncatprod/adminhtml_catalog_category_tab_dyncatprod_rules', 'category.dyncatprod.rules'
            );

            $tabBlock->setChild('category_dyncatprod_rules', $rulesBlock);
            if (mage::getStoreConfig('dyncatprod/ept/enabled') && mage::helper('dyncatprod')->isPre16() == false) {
                if (mage::getStoreConfig('dyncatprod/ept/draggable_grid')) {
                    Mage::app()->getLayout()->getBlock('head')->addCss('dyncatprod.css');
                    Mage::app()->getLayout()->getBlock('head')->addJs('dyncatprod/enhancedgrid.js');
                }
                // remove the core tab, and insert our own tab as a replacement
                $tabs->removeTab('products');

                $productsGrid = Mage::app()->getLayout()
                        ->createBlock('dyncatprod/adminhtml_catalog_category_tab_product', 'category.product.grid')
                        ->toHtml();

                $tabs->addTab(
                    'products', array(
                    'label' => Mage::helper('catalog')->__('Products (Enhanced)'),
                    'content' => $tabBlock->toHtml() . $productsGrid
                    )
                );
            } else {
                $tabs->addTab(
                    'dyncatprod', array(
                    'label' => Mage::helper('catalog')->__('Dynamic Rules'),
                    'content' => $tabBlock->toHtml()
                    )
                );
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            mage::logException($e);
            //mage::throwException($e->getMessage());
        }

        return $this;
    }

    /**
     * Event to save admin
     *
     * @param  Varien_Event_Observer $observer
     * @return ProxiBlue_DynCatProd_Model_Adminhtml_Observer
     */
    public function catalog_category_prepare_save(Varien_Event_Observer $observer)
    {
        try {
            $event = $observer->getEvent();
            if ($data = $event->getRequest()->getPost()) {
                if (array_key_exists('rule', $data) && array_key_exists('conditions', $data['rule'])) {
                    $conditions = serialize($data['rule']['conditions']);
                    if (is_string($conditions)) {
                        $event->getCategory()->setData('dynamic_attributes', $conditions);
                        //get the product ids and attach to category
                        if (Mage::getStoreConfig('dyncatprod/rebuild/delayed') == false) {
                            mage::helper('dyncatprod')->rebuildCategory($event->getCategory());
                        } else {
                            // flag this category for a delayed build
                            $rebuild = Mage::getModel('dyncatprod/delaybuild');
                            $rebuild->load($event->getCategory()->getId(), 'category_id');
                            if (!$rebuild->getId()) {
                                $rebuild->setCategoryId($event->getCategory()->getId());
                                $rebuild->save();
                            }
                        }
                    } else {
                        mage::log(
                            'Could not save serialized dynamic rules. '
                            . 'Potentially the conditions were not serialized'
                        );
                    }
                }
                unset($data['rule']);
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            mage::logException($e);
            mage::throwException($e->getMessage());
        }

        return $this;
    }

    /**
     * Remove the prefix of a value
     *
     * @param string $value
     *
     * @return string
     */
    public function removeValuePrefix($value)
    {
        if (strpos($value, '_')) {
            $strip = explode('_', $value);
            $value = array_pop($strip);
            $strip = explode('.', $value);
            $value = array_shift($strip);
        }

        return $value;
    }

    /**
     * Event to update categories that contains any of the attributes that changed for the products
     *
     * @param  Varien_Event_Observer $observer
     * @return ProxiBlue_DynCatProd_Model_Adminhtml_Observer
     */
    public function catalog_product_save_after(Varien_Event_Observer $observer)
    {
        try {
            if (!Mage::getStoreConfig('dyncatprod/rebuild/product_save')) {
                $data = $observer->getProduct()->getData();
                ksort($data);
                $origData = $observer->getProduct()->getOrigData();
                if (is_array($origData)) { //new products will not have $origData set
                    ksort($origData);
                    $sameKeys = array_keys(array_intersect_key($data, $origData));
                } else {
                    $sameKeys = array_keys($data);
                    $origData = array();
                }
                foreach ($sameKeys as $key) {
                    if (!array_key_exists($key, $origData) || $data[$key] != $origData[$key]) {
                        $rebuild = Mage::getModel('dyncatprod/rebuild');
                        $rebuild->load($key, 'attribute_code');
                        if (!$rebuild->getId()) {
                            $rebuild->setAttributeCode($key);
                            $rebuild->save();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            mage::logException($e);
            //mage::throwException($e->getMessage());
        }

        return $this;
    }

    /**
     * Force a re-index of the category catalog_product after the dynamic data was saved
     *
     * @param  Varien_Event_Observer $observer
     * @return ProxiBlue_DynCatProd_Model_Adminhtml_Observer
     */
    public function catalog_category_save_after(Varien_Event_Observer $observer)
    {
        try {
            $category = $observer->getCategory();
            $resourceModel = Mage::getResourceModel('dyncatprod/category');
            if ($category->getRemoveAllDynamic()) {
                $resourceModel->removeDynamicProducts($category);
            }
            if ($category->getIsDynamic()) {
                $resourceModel->markProductsAsDynamic($category);
                // normal category save indexing does not detect that the dynamic products
                // has changed, thus indexing does not happen.
                // manually initiate this prior to the normal category save indexing
                $category->setIsChangedProductList(true);
                $indexEvent = Mage::getSingleton('index/indexer')->logEvent(
                    $category, Mage_Catalog_Model_Category::ENTITY, Mage_Index_Model_Event::TYPE_SAVE, false
                );
                Mage::getSingleton('index/indexer')
                        ->getProcessByCode('catalog_category_product') // Adjust the indexer process code as needed
                        ->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)
                        ->processEvent($indexEvent);
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.
            mage::logException($e);
            //mage::throwException($e->getMessage());
        }

        return $this;
    }

}
