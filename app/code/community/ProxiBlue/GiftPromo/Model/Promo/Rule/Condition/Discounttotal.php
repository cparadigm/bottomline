<?php

/**
 * Subtotal rule condition
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Discounttotal extends ProxiBlue_GiftPromo_Model_Promo_Rule_Condition_Subtotal {

    const TOTAL_TYPE = 'Sub Total After Discounts';

    public function __construct() {
        Mage_Rule_Model_Condition_Abstract::__construct();
        $this->setType('giftpromo/promo_rule_condition_discounttotal')
		->setValue(null)
                ->setConditions(array())
                ->setActions(array());
    }


    /**
     * Render this as html
     * @return string
     */
    public function asHtml() {
        $html = $this->getTypeElement()->getHtml() .
                Mage::helper('giftpromo')->__("If the %s %s %s", self::TOTAL_TYPE, $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
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
	if (is_array($totals) && array_key_exists('subtotal', $totals)) {
	    // in some instances, it would seem the subtotal comes back with tax inclusive
	    // which then skews totals calculation.
	    // It seems that when this happens, grand_total is 0.
	    if (array_key_exists('grand_total', $totals) && $totals['grand_total']->getValue() == 0 && $totals['subtotal']->getValueExclTax() > 0) {
		$subtotal = $totals['subtotal']->getValueExclTax();
	    } else {
		$subtotal = $totals['subtotal']->getValue();
	    }
	} else {
	    $subtotal = $object->getSubtotal();
	}
	if ($subtotal == 0) {
	    // IWD onestepcheckout seems to re-evaluate collectotals in checkout,
	    // but for some reason the quote object sometimes do not have the totals correct
	    // so if that happens, calculate the totals, using the line items
	    // an ugly workaround, until I find a better way
	    $cartItems = $object->getAllVisibleItems();
	    foreach ($cartItems as $item) {
		$subtotal += $item->getPrice();
	    }
	}
	foreach ($currentGiftItems as $giftItem) {
	    $subtotal = $subtotal - $giftItem->getPrice();
	}
	if (is_array($totals) && array_key_exists('discount', $totals)) {
	    $subtotal = $subtotal + $totals['discount']->getValue();
	}

	return $this->validateAttribute($subtotal);
    }

}
