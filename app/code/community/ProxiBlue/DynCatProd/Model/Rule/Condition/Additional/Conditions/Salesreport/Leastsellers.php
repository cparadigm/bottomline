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
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Leastsellers extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Bestsellers
{

    protected $_inputType = 'text';

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_salesreport_leastsellers')
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
                Mage::helper('dyncatprod')->__("Least selling products from the best sellers sales report %s %s day(s) before the day the category is viewed", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
        if ($this->getId() != '1') {
            $html .= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    /**
     * validate
     *
     * @param  Varien_Object $object
     * @return boolean
     */
    public function _validate(Varien_Object $object)
    {
        try {
            $value = $this->getValueParsed();
            if ($value != '0' || $value != '') {
                $collection = $object->getCollection();
                $customCollection = $this->getCollection();
                $customCollection->addOrderedQty($this->getDateBack($value), $this->getTodayDate());
                $this->mergeCollections($collection, $customCollection);
                $customCollection->getSelect()->order('ordered_qty ' . Zend_Db_Select::SQL_ASC);
                $customCollection->getSelect()->order('order_items_name ' . Zend_Db_Select::SQL_ASC);
                $this->getHelper()->debug('LEAST SELLERS SQL: ' . $customCollection->getSelect());
                // replace the current collection with the sales one.
                $object->setCollection($customCollection);

                return true;
            }
        } catch (Exception $e) {
            mage::logException($e);
        }

        return false;
    }

}
