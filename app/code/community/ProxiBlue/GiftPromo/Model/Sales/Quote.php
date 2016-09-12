<?php

/**
 * Sales quote
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 * */
class ProxiBlue_GiftPromo_Model_Sales_Quote extends Mage_Sales_Model_Quote {
    /**
     * Trigger collect totals after loading, if required
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _afterLoad() {
        // collect totals and save me, if required
        if (1 == $this->getData('trigger_recollect') && !Mage::getSingleton('checkout/session')->getSkipTriggerCollect()) {
            $this->collectTotals()->save();
        }
        Mage::getSingleton('checkout/session')->setSkipTriggerCollect(false);
        return call_user_func(array(get_parent_class(get_parent_class($this)), '_afterLoad'));
    }

}
