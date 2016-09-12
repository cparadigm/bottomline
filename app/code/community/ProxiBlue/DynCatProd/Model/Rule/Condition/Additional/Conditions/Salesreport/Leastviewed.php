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
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Leastviewed extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport_Mostviewed
{

    protected $_inputType = 'text';

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_salesreport_leastviewed')
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
                Mage::helper('dyncatprod')->__("Least Viewed Products from the most viewed report %s %s day(s) before the day the category is viewed", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
        if ($this->getId() != '1') {
            $html .= $this->getRemoveLinkHtml();
        }

        return $html;
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
            $value = $this->getValueParsed();
            if ($value != '0' || $value != '') {
                $collection = $object->getCollection();
                $customCollection = $this->getCollection();
                $customCollection->addViewsCount($this->getDateBack($value), $this->getTodayDate());
                $this->removeOrder($customCollection);
                $this->mergeCollections($collection, $customCollection);
                $customCollection->getSelect()->order('views ' . Zend_Db_Select::SQL_ASC);
                $customCollection->getSelect()->order('entity_id ' . Zend_Db_Select::SQL_DESC);
                $this->getHelper()->debug('LEAST VIEWED SQL: ' . $customCollection->getSelect());
                $object->setCollection($customCollection);

                return true;
            }
        } catch (Exception $e) {
            mage::logException($e);
        }

        return false;
    }

    protected function removeOrder($collection)
    {
        $select = $collection->getSelect();
        $select->reset(Zend_Db_Select::ORDER);
    }

}
