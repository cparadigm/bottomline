<?php

class ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Product_Subselect extends Mage_SalesRule_Model_Rule_Condition_Product_Subselect {

    public function __construct() {
        parent::__construct();
        $this->setType('giftpromo/promo_rule_condition_product_subselect')
                ->setValue(null);
    }

    /**
     * validate
     *
     * @param Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object) {
        if (!$this->getConditions()) {
            return false;
        }

        $attr = $this->getAttribute();
        $total = 0;
        foreach ($object->setData('trigger_recollect',0)->getAllVisibleItems() as $item) {
            //make sure the product is fully populated for validation.
            $productId = ($item instanceof Mage_Catalog_Model_Product)?$item->getId():$item->getProduct()->getId();
            $item->setProduct(mage::getModel('catalog/product')->load($productId));

            if (Mage_SalesRule_Model_Rule_Condition_Product_Combine::validate($item)) {
                $total += $item->getData($attr);
            }
        }

        return $this->validateAttribute($total);
    }

    public function loadValueOptions() {
        $this->setValueOption(array());
        return array();
    }

}
