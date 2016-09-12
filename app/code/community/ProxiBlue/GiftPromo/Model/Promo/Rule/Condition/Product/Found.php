<?php

/**
 * Product promo rule
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 *
 * */
class ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Product_Found
    extends ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Product_Combine
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('giftpromo/promo_rule_condition_product_found');
    }

    /**
     * Load value options
     *
     * @return Mage_SalesRule_Model_Rule_Condition_Product_Found
     */
    public function loadValueOptions()
    {
        $this->setValueOption(array(
            1 => Mage::helper('giftpromo')->__('FOUND'),
            0 => Mage::helper('giftpromo')->__('NOT FOUND')
        ));
        return $this;
    }

    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml() . Mage::helper('giftpromo')->__("If an item is %s in the cart with %s of these conditions true:",
                $this->getValueElement()->getHtml(),
                $this->getAggregatorElement()->getHtml());
        if ($this->getId() != '1') {
            $html.= $this->getRemoveLinkHtml();
        }
        return $html;
    }

    /**
     * validate
     *
     * @param Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object)
    {
        $all = $this->getAggregator() === 'all';
        $true = (bool) $this->getValue();
        $found = false;
        $forcedValidItem = false;
        $allItems = $object->setData('trigger_recollect',
                0)->getAllVisibleItems();
        if (is_array($allItems)) {
            foreach ($allItems as $item) {

                if (Mage::helper('giftpromo')->testGiftTypeCode($item->getProductType())) {
                    continue;
                }
                // was this item already applied to this rule?
                $appliedGiftRuleIds = mage::Helper('giftpromo')
                    ->getAppliedRuleIds($item);
                if ($object->getSkipForced() !== true
                    && in_array($this->getRule()->getId(),
                        $appliedGiftRuleIds)) {
                    $forcedValidItem = $item;
                    continue;
                }
                $found = $all;
                foreach ($this->getConditions() as $cond) {
                    $validated = $cond->validate($item);
                    if (($all && !$validated) || (!$all && $validated)) {
                        $found = $validated;
                        break;
                    }
                }
                if (($found && $true) || (!$true && $found)) {
                    break;
                }
            }
            // found an item and we're looking for existing one
            if ($found && $true) {
                $object->setGiftTriggerItem($item);
                return true;
            }
            // not found and we're making sure it doesn't exist
            elseif (!$found && !$true) {
                $object->setGiftTriggerItem($item);
                return true;
            }
            //if nothing else validate, but one of the items had previously validated (thus is still actually valid)
            //then force a true with this item as the validated item, else it will get removed in the validator.
            if ($forcedValidItem) {
                $object->setGiftTriggerItem($forcedValidItem);
                return true;
            }
            return false;
        }
    }

    /**
     * validate with a count
     *
     * @param Varien_Object $object Quote
     * @return boolean
     */
    public function validateCount(Varien_Object $object)
    {
        $counted = array();
        foreach ($object->setData('trigger_recollect',
            0)->getAllItems() as $item) {
            if (Mage::helper('giftpromo')->testGiftTypeCode($item->getProductType())) {
                continue;
            }
            foreach ($this->getConditions() as $cond) {
                $validated = $cond->validate($item);
                if ($validated) {
                    $counted[] = $item;
                }
            }
        }
        return $counted;
    }

}
