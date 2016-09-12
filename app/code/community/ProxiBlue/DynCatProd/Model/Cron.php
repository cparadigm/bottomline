<?php

/**
 * Cron functions
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Cron
{

    /**
     * Rebuild all dynamic categories
     *
     * @param  string $schedule
     * @return void
     */
    public static function rebuildAllDynamic($schedule)
    {
        $tempDir = sys_get_temp_dir() . "/";
        $fp = fopen(
            $tempDir . "dyncatprod_rebuild.lock",
            "w+"
        );
        try {
            if (flock(
                $fp,
                LOCK_EX | LOCK_NB
            )) {
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log("DynCatProd - rebuildAllDynamic");
                }
                if (!Mage::getStoreConfig('dyncatprod/rebuild/max_exec')) {
                    ini_set(
                        'max_execution_time',
                        Mage::getStoreConfig('dyncatprod/rebuild/max_exec_time')
                    );
                }
                $categories = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToSelect('*')
                    //->addIsActiveFilter() removed after category control rules created.
                    // Must be able to evaluate inactive categories to allow enabling again
                    ->addAttributeToFilter(
                        'dynamic_attributes',
                        array('notnull' => true)
                    );
                self::rebuildCategories($categories);
                flock(
                    $fp,
                    LOCK_UN
                );
                unlink($tempDir . "dyncatprod_rebuild.lock");
            } else {
                mage::log('Could not execute cron for rebuildAllDynamic -file lock is in place, job may be running');
            }
        } catch (Exception $e) {
            flock(
                $fp,
                LOCK_UN
            );
            unlink($tempDir . "dyncatprod_rebuild.lock");
            mage::logException($e);

            return $e->getMessage();
        }
    }

    /**
     * Rebuild only categories that has products that changed attribute values
     *
     * @param string $schedule
     *
     * @return type
     */
    public static function rebuildChangedDynamic($schedule)
    {
        $tempDir = sys_get_temp_dir() . "/";
        $fp = fopen(
            $tempDir . "dyncatprod_changed_dynamic.lock",
            "w+"
        );
        try {
            if (flock(
                $fp,
                LOCK_EX | LOCK_NB
            )) {
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log("DynCatProd - rebuildChangedDynamic");
                }
                $rebuildCollection = Mage::getModel('dyncatprod/rebuild')->getCollection();
                $changed = array();
                foreach ($rebuildCollection as $rebuild) {
                    $changed[] = array('like' => '%' . $rebuild->getAttributeCode() . '%');
                    $rebuild->delete();
                }
                if (count($changed) > 0) {
                    $categories = Mage::getModel('catalog/category')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        //->addIsActiveFilter() removed after category control rules created.
                        //Must be able to evaluate inactive categories to allow enabling again
                        ->addAttributeToFilter(
                            'dynamic_attributes',
                            $changed
                        );
                    self::rebuildCategories($categories);
                }
                flock(
                    $fp,
                    LOCK_UN
                );
                unlink($tempDir . "dyncatprod_changed_dynamic.lock");
            } else {
                mage::log(
                    'Could not execute cron for rebuildChangedDynamic '
                    . '-file lock is in place, job may be running'
                );
            }
        } catch (Exception $e) {
            flock(
                $fp,
                LOCK_UN
            );
            unlink($tempDir . "dyncatprod_changed_dynamic.lock");
            mage::logException($e);

            return $e->getMessage();
        }
    }

    /**
     * Rebuild any delayed category
     *
     * @param  type $schedule
     * @return type
     */
    public static function rebuildDelayed($schedule)
    {
        $tempDir = sys_get_temp_dir() . "/";
        $fp = fopen(
            $tempDir . "dyncatprod_delayed_dynamic.lock",
            "w+"
        );
        try {
            if (flock(
                $fp,
                LOCK_EX | LOCK_NB
            )) {
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log("DynCatProd - rebuildDelayed");
                }
                $rebuildCollection = Mage::getModel('dyncatprod/delaybuild')->getCollection();
                $delayed = array();
                foreach ($rebuildCollection as $rebuild) {
                    $delayed[] = $rebuild->getCategoryId();
                    $rebuild->delete();
                }
                if (count($delayed) > 0) {
                    $categories = Mage::getModel('catalog/category')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        //->addIsActiveFilter() removed after category control rules created.
                        //Must be able to evaluate inactive categories to allow enabling again
                        ->addAttributeToFilter(
                            'entity_id',
                            array('IN' => $delayed)
                        );
                    self::rebuildCategories($categories);
                }
                flock(
                    $fp,
                    LOCK_UN
                );
                unlink($tempDir . "dyncatprod_delayed_dynamic.lock");
            } else {
                mage::log('Could not execute cron for rebuildDelayed -file lock is in place, job may be running');
            }
        } catch (Exception $e) {
            flock(
                $fp,
                LOCK_UN
            );
            unlink($tempDir . "dyncatprod_delayed_dynamic.lock");
            mage::logException($e);

            return $e->getMessage();
        }
    }

    /**
     * Rebuild one dynamic category
     *
     * @param type $catid
     *
     * @return type
     */
    public static function rebuildOneDynamic($catid)
    {
        $tempDir = sys_get_temp_dir() . "/";
        $fp = fopen(
            $tempDir . "dyncatprod_rebuild_one.lock",
            "w+"
        );
        try {
            if (flock(
                $fp,
                LOCK_EX | LOCK_NB
            )) {
                if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                    mage::log("DynCatProd - rebuildAllDynamic");
                }
                if (!Mage::getStoreConfig('dyncatprod/rebuild/max_exec')) {
                    ini_set(
                        'max_execution_time',
                        Mage::getStoreConfig('dyncatprod/rebuild/max_exec_time')
                    );
                }
                $category = Mage::getModel('catalog/category')->load($catid);
                self::rebuildCategories(array($category));
                flock(
                    $fp,
                    LOCK_UN
                );
                unlink($tempDir . "dyncatprod_rebuild_one.lock");
            } else {
                mage::log('Could not execute cron for rebuildOneDynamic -file lock is in place, job may be running');
            }
        } catch (Exception $e) {
            flock(
                $fp,
                LOCK_UN
            );
            unlink($tempDir . "dyncatprod_rebuild_one.lock");
            mage::logException($e);

            return $e->getMessage();
        }
    }

    /**
     * common function to rebuild categories
     *
     * @param array $categories
     *
     * @return void
     */
    public static function rebuildCategories($categories)
    {
        if (!Mage::getStoreConfig('dyncatprod/rebuild/max_exec')) {
            ini_set(
                'max_execution_time',
                Mage::getStoreConfig('dyncatprod/rebuild/max_exec_time')
            );
        }
        foreach ($categories as $category) {
            if (Mage::getStoreConfig('dyncatprod/debug/enabled')) {
                mage::log("rebuilding flaged changed:" . $category->getName() . ' ' . $category->getPath());
            }
            Mage::helper('dyncatprod')->disableIndexes();
            $resourceModel = Mage::getResourceModel('dyncatprod/category');
            $currentProducts = $resourceModel->getCurrentProducts($category);
            $category->setPostedProducts($currentProducts);
            $category->setIsDynamicCronRun(true);
            mage::helper('dyncatprod')->rebuildCategory($category, true);
            $category->save();
            Mage::helper('dyncatprod')->reIndex();
        }
    }

}
