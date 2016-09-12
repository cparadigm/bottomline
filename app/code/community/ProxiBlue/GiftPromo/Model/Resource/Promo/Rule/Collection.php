<?php
/**
 * Rule collection
 * 
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 **/
class ProxiBlue_GiftPromo_Model_Resource_Promo_Rule_Collection extends ProxiBlue_GiftPromo_Model_Resource_Promo_Rule_Collection_Abstract {

    protected $_dateFilter = false;

    /**
     * Constructor
     *
     */
    protected function _construct() {
        $this->_init('giftpromo/promo_rule');
    }

    public function enableDateFilter() {
        $this->_dateFilter = true;
    }

}
