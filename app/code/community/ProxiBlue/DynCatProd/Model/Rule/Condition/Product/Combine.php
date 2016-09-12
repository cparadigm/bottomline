<?php

/**
 * Conditions combine
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Combine extends ProxiBlue_DynCatProd_Model_Rule_Condition_Backport
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_product_combine')
            ->setAggregator('all')
            ->setValue(true)
            ->setConditions(array())
            ->setActions(array());
    }

    public function getNewChildSelectOptions()
    {
        $productCondition = Mage::getModel('dyncatprod/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $pAttributes = array();
        foreach ($productAttributes as $type => $attributeData) {
            foreach ($attributeData as $code => $label) {
                if (strpos($code, 'quote_item_') !== 0) {
                    $pAttributes[$type][] = array('value' => 'dyncatprod/rule_condition_product|' . $code, 'label' => $label);
                }
            }
        }
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions, array(
            array('label' => Mage::helper('catalog')->__('Special Conditions'), 'value' => array(
                    array('value' => 'dyncatprod/rule_condition_additional_conditions_discount', 'label' => Mage::helper('dyncatprod')->__('Discounts given')),
                    array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport', 'label' => Mage::helper('dyncatprod')->__('Using Sales Reports')),
                )),
            array('label' => Mage::helper('catalog')->__('Special Attributes'), 'value' => $pAttributes['special']),
            array('label' => Mage::helper('catalog')->__('Date Range Attributes'), 'value' => $pAttributes['date_range']),
            array('label' => Mage::helper('catalog')->__('Product Attribute'), 'value' => $pAttributes['normal']),
            )
        );

        return $conditions;
    }

    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }

        return $this;
    }

    /**
     * Build the condition array.
     *
     * From v3 this sets a processing order found form the class objects
     * This is set via a constant in the class named 'PROCESSING_ORDER';
     *
     * @param  type $condition
     * @return \ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Found
     */
    public function addCondition($condition)
    {
        $condition->setRule($this->getRule());
        $condition->setObject($this->getObject());
        $condition->setPrefix($this->getPrefix());

        $conditions = $this->getConditions();
        if ($condition->getProcessingOrder() === false || is_null($condition->getProcessingOrder())) {
            mage::helper('dyncatprod')->debug('Rule ' . get_class($condition) . ' does not have a processing order set. This can result in unexpected rules behaviour. Contact sales@proxiblue.com.au to correct this.');
            $condition->setProcessingOrder(rand(200, 500));
        }
        if ($condition->getProcessingOrder() == 'any') {
            $conditions[] = $condition;
        } else {
            if (array_key_exists($condition->getProcessingOrder(), $conditions)) {
                mage::helper('dyncatprod')->debug('Rule ' . get_class($condition) . ' appear to have the same processing orderthan another rule. This can result in unexpected rules behaviour. Contact sales@proxiblue.com.au to correct this. The rule order process was increased by 1 to try and allow the system to continue.');
                $condition->setProcessingOrder($condition->getProcessingOrder() + 1);
            }
            $conditions[$condition->getProcessingOrder()] = $condition;
        }
        if (!$condition->getId()) {
            $condition->setId($this->getId() . '--' . sizeof($conditions));
        }
        $this->setData($this->getPrefix(), $conditions);

        return $this;
    }

    /**
     * Load the conditons into the required objects.
     *
     * From v3, this sorts the conritions as per set processing order
     *
     * @param  type $arr
     * @param  type $key
     * @return \ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Found
     */
    public function loadArray($arr, $key = 'conditions')
    {
        $this->setAggregator(isset($arr['aggregator']) ? $arr['aggregator'] : (isset($arr['attribute']) ? $arr['attribute'] : null))
            ->setValue(isset($arr['value']) ? $arr['value'] : (isset($arr['operator']) ? $arr['operator'] : null))
            ->setCombiner(isset($arr['combiner']) ? $arr['combiner'] : 'AND');

        if (!empty($arr[$key]) && is_array($arr[$key])) {
            foreach ($arr[$key] as $condArr) {
                try {
                    $cond = $this->_getNewConditionModelInstance($condArr['type']);
                    if ($cond) {
                        $this->addCondition($cond);
                        $cond->loadArray($condArr, $key);
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        $conditions = $this->getConditions();
        ksort($conditions, SORT_NUMERIC);
        $this->setConditions($conditions);

        return $this;
    }

}
