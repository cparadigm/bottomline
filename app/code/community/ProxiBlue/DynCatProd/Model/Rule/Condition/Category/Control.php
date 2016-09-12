<?php

/**
 *
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Category_Control extends Mage_Rule_Model_Condition_Combine
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_category_control')
            ->setProcessingOrder('9999999' . rand(200, 500))
            ->setValue('current')
            ->setAggregator('any');
    }

    public function loadAggregatorOptions()
    {
        $this->setAggregatorOption(
            array(
            'none' => Mage::helper('rule')->__('NONE'),
            'any' => Mage::helper('rule')->__('ANY'),
            )
        );

        return $this;
    }

    public function loadValueOptions()
    {
        $this->setValueOption(
            array(
            'current' => Mage::helper('rule')->__('CURRENT'),
            'parent' => Mage::helper('rule')->__('PARENT'),
            )
        );

        return $this;
    }

    public function asHtml()
    {
        if (!$this->getValue()) {
            // fix legacy rules to have a default value set.
            $this->setValue('current');
        }
        $html = $this->getTypeElement()->getHtml() . Mage::helper('dyncatprod')->__("Set the following category attribute values, if there are %s items found in the %s category.", $this->getAggregatorElement()->getHtml(), $this->getValueElement()->getHtml());
        if ($this->getId() != '1') {
            $html.= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    public function getNewChildSelectOptions()
    {
        $categoryCondition = Mage::getModel('dyncatprod/rule_condition_category_conditions_category');
        $categoryAttributes = $categoryCondition->loadAttributeOptions()->getAttributeOption();
        $pAttributes = array();
        foreach ($categoryAttributes as $code => $label) {
            if (strpos($code, 'quote_item_') !== 0) {
                $pAttributes[] = array('value' => 'dyncatprod/rule_condition_category_conditions_category|' . $code, 'label' => $label);
            }
        }

        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions, array(
            array('label' => Mage::helper('catalog')->__('Category Attribute'), 'value' => $pAttributes),
            )
        );

        return $conditions;
    }

    public function validate(Varien_Object $object)
    {
        if (!$this->getConditions()) {
            return true;
        }
        mage::Helper('dyncatprod')->addCategoryControl($this, $object->getCollection());

        return true;
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
        if (array_key_exists($this->getProcessingOrder($condition), $conditions)) {
            mage::helper('dyncatprod')->debug('Rule ' . get_class($condition) . ' appear to have the same processing order than another rule. This can result in unexpected rules behaviour. Contact sales@proxiblue.com.au to correct this. The rule order process was increased by 1 to try and allow the system to continue.');
            $condition->setProcessingOrder($this->getProcessingOrder($condition) + 1);
        }
        $conditions[$this->getProcessingOrder($condition)] = $condition;
        if (!$condition->getId()) {
            $condition->setId($this->getId() . '--' . sizeof($conditions));
        }
        $this->setData($this->getPrefix(), $conditions);

        return $this;
    }

    protected function getProcessingOrder($condition)
    {
        if ($condition->getProcessingOrder() === false || is_null($condition->getProcessingOrder())) {
            $this->_startProcessingorder = $this->_startProcessingorder + 1;
            $condition->setProcessingOrder($this->_startProcessingorder);
        }

        return $condition->getProcessingOrder();
    }

}
