<?php

/**
 * Gift Product shopping cart item renderer
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Block_Cart_Item_Renderer_Abstract extends Mage_Checkout_Block_Cart_Item_Renderer {

    /**
     * Get the parent product of a gift
     * @return object 
     */
    public function getParentOfGift() {
        return Mage::helper('giftpromo')->getParentOfGift($this->getItem());
    }

    /**
     * Can it delete ?
     * @return boolean 
     */
    public function canDelete() {
        $buyRequest = Mage::helper('giftpromo')->isAddedAsGift($this->getItem());
        $ruleObject = Mage::getModel('giftpromo/promo_rule')->load($buyRequest->getAddedByRule());
        return $ruleObject->getAllowGiftSelection();
    }

    /**
     * Get item delete url
     *
     * @return string
     */
    public function getDeleteUrl() {
        if ($this->hasDeleteUrl()) {
            return $this->getData('delete_url');
        }

        $buyRequest = Mage::helper('giftpromo')->isAddedAsGift($this->getItem());

        return $this->getUrl(
                        'checkout/cart/deleteGift', array(
                    'id' => $this->getItem()->getId(),
                    'rule_id' => $buyRequest->getAddedByRule(),
                    Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper('core/url')->getEncodedUrl()
                        )
        );
    }

    public function compositeMessage() {
        $additonalMessage = '';
        $parentItem = Mage::helper('giftpromo')->getParentQuoteItemOfGift($this->getItem());
        if($parentItem !== false) {
            if ($parentItem->getProduct()->isComposite()) {
                //TODO: Add support fro bundles and Grouped
                if($parentItem->getProductType() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
                    $optionsRendererBlock = Mage::getModel('core/layout')->createBlock(
                            'Mage_Checkout_Block_Cart_Item_Renderer_Configurable', 
                            'configurable_options_block', array('template' => 'giftpromo/cart/item/options.phtml')
                    );
                    $optionsRendererBlock->setItem($parentItem);
                    $optionsHtml = $optionsRendererBlock->toHtml();
                    $multiple = ($optionsRendererBlock->getMultiple())?'s':'';
                    $additonalMessage = Mage::helper('giftpromo')->__('with option%s: %s ', $multiple, $optionsHtml);
                    
                }
                $checkoutSession = $this->getCheckoutSession();
                $messageFactory = Mage::getSingleton('core/message');
                $message = $messageFactory->notice(Mage::helper('giftpromo')->__('This gift added for "%s" %s ',$parentItem->getProduct()->getName(), $additonalMessage));
                $checkoutSession->addQuoteItemMessage($this->getItem()->getId(), $message);
            }
        }
    }

}
