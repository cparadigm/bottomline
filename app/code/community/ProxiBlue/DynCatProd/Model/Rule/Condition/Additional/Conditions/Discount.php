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
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Discount extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Combine
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_discount')
            ->setProcessingOrder(200);
    }

    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml() . Mage::helper('dyncatprod')->__("Any product that matches these discount conditions:", $this->getAggregatorElement()->getHtml());
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
            array('value' => 'dyncatprod/rule_condition_additional_conditions_discount_specialprice', 'label' => Mage::helper('dyncatprod')->__('Discount given using "special price" product attribute')),
            array('value' => 'dyncatprod/rule_condition_additional_conditions_discount_catalogrule', 'label' => Mage::helper('dyncatprod')->__('Discount given by applied (and valid date range) Catalog Price Rule')),
            )
        );

        return $conditions;
    }

}
