<?php

/**
 * Subtotal rule condition
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Subtotal extends Mage_Rule_Model_Condition_Abstract {

    protected $_inputType = 'text';

    public function __construct() {
        Mage_Rule_Model_Condition_Abstract::__construct();
        $this->setType('giftpromo/promo_rule_condition_subtotal')
                ->setValue(null)
                ->setConditions(array())
                ->setActions(array());
    }

    /**
     * Load the given array into the object as rule data
     *
     * @param array $arr
     * @param string $key
     * @return \ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Subtotal
     */
    public function loadArray($arr, $key = 'conditions') {
        $this->setOperator($arr['operator']);
        parent::loadArray($arr, $key);
        return $this;
    }

    /**
     *Return the rule data as xml
     * @param string $containerKey
     * @param string $itemKey
     * @return string
     */
    public function asXml($containerKey = 'conditions', $itemKey = 'condition') {
        $xml = '<attribute>' . $this->getAttribute() . '</attribute>'
                . '<operator>' . $this->getOperator() . '</operator>'
                . parent::asXml($containerKey, $itemKey);
        return $xml;
    }

    public function loadValueOptions() {
        $this->setValueOption(array());
        return array();
    }

    /**
     * Populate the internal Operator data with accepatble operators
     *
     * @return \ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Subtotal
     */
    public function loadOperatorOptions() {
        $this->setOperatorOption(array(
            '>=' => Mage::helper('rule')->__('equals or greater than'),
            '<=' => Mage::helper('rule')->__('equals or less than'),
        ));
        return $this;
    }

    /**
     * Get this models Element Type
     * @return type
     */
    public function getValueElementType() {
        return $this->_inputType;
    }

    /**
     * Get the renderer to use for this value type
     * @return object
     */
    public function getValueElementRenderer() {
        return Mage::getBlockSingleton('rule/editable');
    }

    /**
     * Render this as html
     * @return string
     */
    public function asHtml() {
        $html = $this->getTypeElement()->getHtml() .
                Mage::helper('giftpromo')->__("If the Sub Total %s %s", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
        if ($this->getId() != '1') {
            $html .= $this->getRemoveLinkHtml();
        }
        return $html;
    }

    /**
     * validate
     *
     * @param Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object) {
        $totals = $object->getTotals();
        // adjust totals and remove any current gift items price from the total
        $currentGiftItems = Mage::helper('giftpromo')->getAllGiftBasedCartItems();
        if(is_array($totals) && array_key_exists('subtotal',$totals)){
            // in some instances, it would seem the subtotal comes back with tax inclusive
            // which then skews totals calculation.
            // It seems that when this happens, grand_total is 0.
            if(array_key_exists('grand_total', $totals) && $totals['grand_total']->getValue() == 0 && $totals['subtotal']->getValueExclTax() > 0) {
                $subtotal = $totals['subtotal']->getValueExclTax();
            } else {
                $subtotal = $totals['subtotal']->getValue();
            }
        } else {
            $subtotal = $object->getSubtotal();
        }
        if($subtotal == 0) {
            // IWD onestepcheckout seems to re-evaluate collectotals in checkout,
            // but for some reason the quote object sometimes do not have the totals correct
            // so if that happens, calculate the totals, using the line items
            // an ugly workaround, until I find a better way
            $cartItems = $object->getAllVisibleItems();
            if(is_array($cartItems)) {
                foreach($cartItems as $item) {
                    $subtotal += $item->getPrice();
                }
            }

        }
        if($subtotal > 0) {
            foreach($currentGiftItems as $giftItem){
                $subtotal = $subtotal - $giftItem->getPrice();
            }
        }
        return $this->validateAttribute($subtotal);
    }

}
