<?php

/**
 * Get gifts products attached to currnt viewed product
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Block_Product_List_Icon extends Mage_Catalog_Block_Product_Abstract {

    /**
     * Collection of items
     * @var Varien_Collection
     */
    protected $_itemCollection;
    /**
     * Merged collection of all gift types
     * @var Varien_Collection
     */
    protected $_mergedCollection;

    /**
     * Prepare the data collection
     * @return \ProxiBlue_GiftPromo_Block_Product_List_Icon
     */
    protected function _prepareData() {
        try {
            if($this->isEnabled()){
                $this->_itemCollection = Mage::helper('giftpromo')->testItemHasValidGifting($this->getProduct(), false);
            }
            return $this;
        } catch (Exception $e){
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
    }

    /**
     * Is icons enabled?
     * @return bool
     */
    public function isEnabled(){
        return Mage::getStoreConfig('giftpromo/catalog/icons_enabled');
    }

    /**
     * Before html renderer
     * @return string
     */
    protected function _beforeToHtml() {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * Get items collection
     * @return Varien_Collection
     */
    public function getItems() {
        return $this->_itemCollection;
    }

    /**
     * Get assigned gift icon, or global icon if none assigned
     *
     * @param int $size
     * @return Varien_Object
     */
    public function getGiftIcon($size = 50) {
        $_product = $this->getProduct()->load($this->getProduct()->getId());
        $image = false;
        try {
            $image = $this->helper('catalog/image')->init($_product, 'gift_promotion_icon')->resize($size);
        } catch (Exception $e){
            // fail silently
        }
        if(is_object($image)){
            return $image;
        }
        return $this->getSkinUrl('images/giftpromo/gift-icon.png');
    }
}
