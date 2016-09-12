<?php

/**
 * Helper routines to build the gifticon
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Helper_GiftIcon extends Mage_Catalog_Helper_Data {

    public function giftIconHtml($product) {   
        $layout = Mage::getSingleton('core/layout');
        $giftBlock = $block = $layout->createBlock(
        'giftpromo/product_list_icon',
        'catalog.product.gifticon',
            array('template' => 'giftpromo/catalog/product/list/icon.phtml')
        );
        if($giftBlock){
            $html = $giftBlock->setProduct($product)->toHtml();
            return $html;
        } 
        return '';
    }

}
