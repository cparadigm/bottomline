<?php

/**
 * Customer Registration rule condition
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Lowstock extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Abstract
{

    protected $_inputType = 'text';

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_salesreport_lowstock')
            ->setValue(null)
            ->setConditions(array())
            ->setActions(array());
    }

    /**
     * Render this as html
     * @return string
     */
    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml() .
                Mage::helper('dyncatprod')->__("Products from the low stock report where the stock level is %s %s on the day the category is viewed", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
        if ($this->getId() != '1') {
            $html .= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    public function loadOperatorOptions()
    {
        $this->setOperatorOption(
            array(
            '==' => Mage::helper('rule')->__('equals to '),
            '>' => Mage::helper('rule')->__('more than '),
            '<' => Mage::helper('rule')->__('less than '),
            '>=' => Mage::helper('rule')->__('more than or equals to'),
            '<=' => Mage::helper('rule')->__('less than or equals to'),
            )
        );

        return $this;
    }

    /**
     * validate
     *
     * @param  Varien_Object $object Quote
     * @return boolean
     */
    public function _validate(Varien_Object $object)
    {
        try {
            $collection = $object->getCollection();
            $customCollection = $this->getCollection();
            $this->mergeCollections($collection, $customCollection);
            $this->getHelper()->debug('LOW STOCK SQL: ' . $customCollection->getSelect());
            $object->setCollection($customCollection);

            return true;
        } catch (Exception $e) {
            mage::logException($e);
        }

        return false;
    }

    protected function getCollection()
    {
        $storeId = Mage::app()->getStore()->getId();
        $collection = $customCollection = Mage::getResourceModel('reports/product_lowstock_collection')
                ->addAttributeToSelect('*')
                ->setStoreId($storeId)
                ->filterByIsQtyProductTypes()
                ->joinInventoryItem('qty')
                ->useManageStockFilter($storeId)
                //->useNotifyStockQtyFilter($storeId)
                ->setOrder('qty', Varien_Data_Collection::SORT_ORDER_ASC);
        $collection->getSelect()->where('qty ' . $this->_operatorMapToSql[$this->getOperator()] . ' ?', (int) $this->getValue());
        if ($storeId) {
            $collection->addStoreFilter($storeId);
        }

        return $collection;
    }

}
