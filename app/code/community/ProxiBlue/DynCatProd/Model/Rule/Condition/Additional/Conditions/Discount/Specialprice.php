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
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Discount_Specialprice extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Abstract
{

    protected $_inputType = 'text';

    /**
     * Set rule type
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_discount_specialprice')
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
                Mage::helper('dyncatprod')->__("If a product has a special price value set that gives %s %s off the product price", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
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
    public function validate(Varien_Object $object)
    {
        $collection = $object->getCollection();
        $value = $this->getValueParsed();
        $operator = $this->_operatorMapToSql[$this->getOperator()];
        $todayDate = Mage::app()->getLocale()->date()->toString(Varien_Date::DATE_INTERNAL_FORMAT);
        $collection->addAttributeToFilter(
            array(
            array(
                'attribute' => "special_to_date",
                'null' => true
            ),
            array(
                'attribute' => "special_to_date",
                'from' => $todayDate,
                //'to'      => $todayDate,
                'date' => true
            )
                ), null, 'left'
        );
        $collection->addAttributeToFilter(
            array(
            array(
                'attribute' => "special_from_date",
                'null' => true
            ),
            array(
                'attribute' => "special_from_date",
                //'from'    => $todayDate,
                'to' => $todayDate,
                'date' => true
            )
                ), null, 'left'
        );
        $collection->addAttributeToSelect('special_price', 'left');
        $collection->addAttributeToSelect('price', 'left');
        $select = $collection->getSelect();
        $helper = mage::helper('dyncatprod');
        if (strpos($value, '%') > 0) {
            $value = str_replace('%', '', $value);
            $select->where('( 100 - (( ' . $helper->getColumnName($select->getPart(Zend_Db_Select::COLUMNS), 'special_price') . '.value * 100 ) / ' . $helper->getColumnName($select->getPart(Zend_Db_Select::COLUMNS), 'price') . '.value ) )  ' . $operator . ' ' . $value);
        } else {
            $select->where('((' . $helper->getColumnName($select->getPart(Zend_Db_Select::COLUMNS), 'price') . '.value - ' . $helper->getColumnName($select->getPart(Zend_Db_Select::COLUMNS), 'special_price') . '.value)) ' . $operator . ' ' . $value);
        }
        $this->getHelper()->debug('SPECIAL PRICE SQL Adjusted: ' . $collection->getSelect());

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
