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
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Transformations extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Combine
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_transformations')
            ->setProcessingOrder(999999)
            ->setAggregator('all')
            ->setValue(true);
    }

    /**
     * All salesrules must validate for results
     * @return string
     */
    public function getAggregator()
    {
        return 'all';
    }

    /**
     * All salesrules must validate for results
     * @return string
     */
    public function getValue()
    {
        return true;
    }

    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml() . Mage::helper('dyncatprod')->__("Transform the resulting data using the following rules:");
        if ($this->getId() != '1') {
            $html.= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    /**
     * Conditions child rules
     * Current supported:
     * Cart Subtotal
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions, array(
            array('value' => 'dyncatprod/rule_condition_additional_conditions_transformations_parents', 'label'
                => Mage::helper('dyncatprod')->__('Parents of simple products')),
            array('value' => 'dyncatprod/rule_condition_additional_conditions_transformations_manual', 'label'
                => Mage::helper('dyncatprod')->__('Manually assigned products')),
            )
        );
        return $conditions;
    }

}
