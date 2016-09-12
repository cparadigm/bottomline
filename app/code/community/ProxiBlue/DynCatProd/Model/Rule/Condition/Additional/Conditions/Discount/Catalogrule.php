<?php

/**
 * Catalog Rule Conditions
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Discount_Catalogrule extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Abstract
{

    protected $_inputType = 'text';

    /**
     * Set rule type
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_discount_catalogrule')
            ->setValue(null)
            ->setConditions(array())
            ->setActions(array());
    }

    /**
     * Populate the internal Operator data with accepatble operators
     */
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
     * Render this as html
     * @return string
     */
    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml() .
                Mage::helper('dyncatprod')->__("If a product has a Catalog Price Rule applied which gives %s %s off the product price", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
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
        $collection = $object->getCollection();
        $value = $this->getValueParsed();
        $operator = $this->_operatorMapToSql[$this->getOperator()];
        $conditions = " (price_rule.product_id = e.entity_id) ";
        if (strpos($value, '%') > 0) {
            $value = str_replace('%', '', $value);
            $conditions .= " AND action_operator = '" . Mage_SalesRule_Model_Rule::BY_PERCENT_ACTION . "' ";
        } else {
            $conditions .= " AND action_operator = '" . Mage_SalesRule_Model_Rule::BY_FIXED_ACTION . "' ";
        }
        $storeDate = Mage::app()->getLocale()->storeTimeStamp($this->getStoreId());
        $conditions .= " AND (from_time = 0
                    OR from_time <= " . $storeDate . ")
                    AND (to_time = 0
                    OR to_time >= " . $storeDate . ") ";
        $conditions .= " AND action_amount " . $operator . " " . $value;
        $collection->getSelect()->joinInner(
            array('price_rule' => $collection->getTable('catalogrule/rule_product')), $conditions
        );
        $this->getHelper()->debug('Catalog Rule SQL Adjusted: ' . $collection->getSelect());

        return true;
    }

    /**
     * Retrieve after element HTML
     *
     * @return string
     */
    public function getValueAfterElementHtml()
    {
        $html = ' ( use % to indicate percentage discount given )';

        return $html;
    }

}
