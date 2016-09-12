<?php

class ProxiBlue_Giftpromo_Block_Product_Select_Options extends Mage_Catalog_Block_Product_View_Options {

    /**
     * Retrieve the current selecting product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct() {
        if ($this->getData('product')) {
            return $this->getData('product');
        } else {
            return Mage::getSingleton('catalog/product');
        }
    }

    /**
     * Get product options
     *
     * @return array
     */
    public function getOptions() {
        return $this->getProduct()->getOptions();
    }

}

