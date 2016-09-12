<?php

class Boardroom_OneClickOrder_Model_Observer extends Mage_Core_Model_Abstract {

    public function controller_action_predispatch_checkout_onepage_saveShipping($observer) {
        $address = Mage::app()->getRequest()->getPost();
        $address['shipping']['region'] = Mage::getModel('directory/region')->load($address['shipping']['region_id'])->getName();

        $cart = Mage::getModel('checkout/cart')->getQuote();
        $restrictedProducts = array();
        foreach ($cart->getAllItems() as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
            if ($product->getAttributeText('vendor')=='PCD') {
                $restrictedProducts[] = $product->getName();
            }
        }

        if ($address['shipping']['region']=='Florida' && !empty($restrictedProducts)) {
            $message = 'The following products can not be shipped to Florida:<br>';
            foreach($restrictedProducts as $restrictedProduct) {
                $message .= $restrictedProduct.'<br>';
            }
            $message .= 'Please remove these products or change shipping address to continue';
            Mage::getSingleton('core/session')->addError($message);
            $this->_redirect('checkout/cart');
            return;
        }
        return;
    }

}