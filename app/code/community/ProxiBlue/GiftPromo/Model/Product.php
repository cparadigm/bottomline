<?php

/**
 * Gift product model
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 * 
 */
class ProxiBlue_GiftPromo_Model_Product extends Mage_Catalog_Model_Product {

    const ADD_METHOD_DIRECT = 0;
    const ADD_METHOD_SELECT = 1;
    const ADD_METHOD_SELECT_ONE = 2;

    /**
     * Internal holder for helper class
     * 
     * @var object 
     */
    private $_helper;
    
    /**
     * Clone an existing product to turn it into a gift product type
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param bool $isGift
     * @return \ProxiBlue_GiftPromo_Model_Product
     */
    public function cloneProduct(Mage_Catalog_Model_Product $product) {
        parent::load($product->getId());
        $infoBuyRequest = $product->getCustomOption('info_buyRequest');
        if ($infoBuyRequest) {
            $buyRequest = new Varien_Object(unserialize($infoBuyRequest->getValue()));
            if ($buyRequest->getAddedByRule()) {
                $ruleModel = Mage::getModel('giftpromo/promo_rule')->load($buyRequest->getAddedByRule());
                $ruleGiftproducts = $ruleModel->getGiftedProducts();
                if (array_key_exists($this->getId(), $ruleGiftproducts)) {
                    $newData = array_merge($this->getData(), $ruleGiftproducts[$this->getId()]);
                    $this->setData($newData);
                }
            }
        }
        return $this;
    }
    
    /**
     * Get the helper class and cache teh object
     * @return object
     */
    private function _getHelper() {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::Helper('giftpromo');
        }
        return $this->_helper;
    }
    
}
