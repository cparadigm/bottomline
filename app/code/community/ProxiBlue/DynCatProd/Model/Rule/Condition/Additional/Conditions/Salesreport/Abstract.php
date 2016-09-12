<?php

/**
 * Abstract Rule product condition data model - does not exist in magento prior to 1.7 / 1.12
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Abstract extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Abstract
{

    /**
     * Merge the product Collection with the sales report collection
     *
     * @param type $productCollection
     * @param type $salesCollection
     */
    public function mergeCollections($productCollection, $salesCollection)
    {
        // first we must strip the fields from the productCollection
        $productSelect = $productCollection->getSelect();
        $salesSelect = $salesCollection->getSelect();
        $productSelect->reset(Zend_Db_Select::COLUMNS);
        $productSelect->setPart(Zend_Db_Select::COLUMNS, array(array('e', 'entity_id', null)));
        // do we have a limiter? Older versions of mysql do not support limit in subquery
        $limit = $productSelect->getPart(Zend_Db_Select::LIMIT_COUNT);
        if ($limit > 0) {
            $salesSelect->limit($limit);
            $productSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        }
        $limitOffset = $productSelect->getPart(Zend_Db_Select::LIMIT_OFFSET);
        if ($limitOffset > 0) {
            $salesSelect->offset($limitOffset);
            $productSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        }
        // we do not need ordering at this level
        $productSelect->reset(Zend_Db_Select::ORDER);
        $this->getHelper()->debug('PRODUCT SQL MERGED: ' . $productSelect);
        // now add productCollection as a subselect to the reportCollection
        $subselect = new Zend_Db_Expr((string) $productSelect);
        $salesSelect->where('e.entity_id IN (?)', $subselect);
    }

    /**
     * Populate the internal Operator data with accepatble operators
     *
     * @return \ProxiBlue_DynCatProd_Model_Promo_Rule_Condition_Register
     */
    public function loadOperatorOptions()
    {
        $this->setOperatorOption(
            array(
            '>=' => Mage::helper('rule')->__('starts')
            )
        );

        return $this;
    }

    protected function getTodayDate()
    {
        return Mage::app()->getLocale()->date()->toString(Varien_Date::DATE_INTERNAL_FORMAT);
    }

    protected function getDateBack($value)
    {
        return $dateBack = date('Y-m-j G:i:s', strtotime('-' . $value . ' day' . $this->getTodayDate()));
    }

    /**
     * Get the sales report collection
     * @return object
     */
    protected function getCollection()
    {
        $storeId = Mage::app()->getStore()->getId();

        return Mage::getResourceModel('reports/product_collection')
                        ->addAttributeToSelect('*')
                        ->setStoreId($storeId)
                        ->addStoreFilter($storeId)
                        ->addAttributeToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG));
    }

    /**
     * validate wrapper
     * Sets the sales report category flag to not allow positions to be set manually
     *
     * @param  Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object)
    {
        $category = $object->getCategory();
        $category->setIgnoreManualPositions(true);

        return parent::validate($object);
    }

}
