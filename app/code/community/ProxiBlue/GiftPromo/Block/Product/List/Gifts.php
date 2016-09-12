<?php

/**
 * Get gifts products attached to currnt viewed product
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Block_Product_List_Gifts extends Mage_Catalog_Block_Product_Abstract {

    protected $_itemCollection;


    protected function _prepareData() {
        try {
            $this->_itemCollection = Mage::helper('giftpromo')->testItemHasValidGifting(Mage::registry('product'), false);
            return $this;
        } catch (Exception $e){
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
    }

    protected function _beforeToHtml() {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    public function getItems() {
        return $this->_itemCollection;
    }

}
