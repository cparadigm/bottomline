<?php

class ProxiBlue_Giftpromo_Block_Product_View extends Mage_Catalog_Block_Product_View {

    public function __construct() {
        parent::__construct();
        $this->addData(array(
            'cache_lifetime' => null,
            'cache_tags' => array(Mage_Cms_Model_Block::CACHE_TAG)
        ));
    }

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

    protected function _prepareLayout() {
        
    }

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo() {
        $cacheKey = array(
            'BLOCK_TPL',
            Mage::app()->getStore()->getCode() . '-' . $this->getProduct()->getId(),
            $this->getTemplateFile(),
            'template' => $this->getTemplate()
        );

        return $cacheKey;
    }
    
    /**
     * Obtain sorted child blocks
     *
     * @return array
     */
    public function getSortedChildBlocks()
    {
        $children = array();
        $giftuid = rand(0,10000000000) . md5(uniqid(rand(0,10000000000) . '_', true)) . rand(0,10000000000);
        foreach ($this->getSortedChildren() as $childName) {
            $block = $this->getLayout()->getBlock($childName);
            $block->setGiftUid($giftuid);
            $children[$childName] = $block;
        }
        return $children;
    }

}
