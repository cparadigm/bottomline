<?php

class EM_Productlabels_Model_Rule_Combine extends Mage_Rule_Model_Condition_Combine
{
    public function getNewChildSelectOptions()
    {
        $productCondition = Mage::getModel('salesrule/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $pAttributes = array();
        $pAttributes[] = array('value'=>'productlabels/rule_condition_product|is_new', 'label'=>'Is New');
        $pAttributes[] = array('value'=>'productlabels/rule_condition_product|is_special', 'label'=>'Is Special Price');
        $pAttributes[] = array('value'=>'productlabels/rule_condition_product|qty', 'label'=>'Qty');
        $pAttributes[] = array('value'=>'productlabels/rule_condition_product|out_of_stock', 'label'=>'Is Out Of Stock');
        $pAttributes[] = array('value'=>'productlabels/rule_condition_product|best_seller', 'label'=>'Amount Best Seller');
        $iAttributes = array();
        foreach ($productAttributes as $code=>$label) {
            if (strpos($code, 'quote_item_')===0) {
                $iAttributes[] = array('value'=>'salesrule/rule_condition_product|'.$code, 'label'=>$label);
            } else {
                $pAttributes[] = array('value'=>'salesrule/rule_condition_product|'.$code, 'label'=>$label);
            }
        }
        
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array('value'=>'salesrule/rule_condition_product_combine', 'label'=>Mage::helper('catalog')->__('Conditions Combination')),
            //array('label'=>Mage::helper('catalog')->__('Cart Item Attribute'), 'value'=>$iAttributes),
            array('label'=>Mage::helper('catalog')->__('Product Attribute'), 'value'=>$pAttributes),
        ));
        return $conditions;
    }

    public function loadArray($arr, $key='conditions')
    {
        $this->setAggregator(isset($arr['aggregator']) ? $arr['aggregator']
                : (isset($arr['attribute']) ? $arr['attribute'] : null))
            ->setValue(isset($arr['value']) ? $arr['value']
                : (isset($arr['operator']) ? $arr['operator'] : null));

        if (!empty($arr[$key]) && is_array($arr[$key])) {
            foreach ($arr[$key] as $condArr) {
                try {
                    if(trim($condArr['type']) == 'salesrule/rule_condition_product')
                        $cond = Mage::getModel('productlabels/rule_condition_product');
                    else
                        $cond = @Mage::getModel($condArr['type']);
                    if (!empty($cond)) {
                        $this->addCondition($cond);
                        $cond->loadArray($condArr, $key);
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        return $this;
    }
}
