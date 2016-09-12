<?php

class Magestore_Giftvoucher_Block_Adminhtml_Order_Sendbackend extends Mage_Adminhtml_Block_Sales_Order_Create_Abstract {

    protected function _prepareLayout() {
        parent::_prepareLayout();
    }

    protected function _formatPrices($prices) {
        Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
        foreach ($prices as $key => $price)
            $prices[$key] = $store->formatPrice($price, false);
        return $prices;
    }

    public function messageMaxLen() {
        return (int) Mage::helper('giftvoucher')->getInterfaceConfig('max');
    }

    public function enablePhysicalMail() {
        return Mage::helper('giftvoucher')->getInterfaceConfig('postoffice');
    }

    public function getFormConfigData() {
        $request = Mage::app()->getRequest();
        $action = $request->getRequestedRouteName() . '_' . $request->getRequestedControllerName() . '_' . $request->getRequestedActionName();
        if ($action == 'checkout_cart_configure' && $request->getParam('id')) {
            $request = Mage::app()->getRequest();
            $options = Mage::getModel('sales/quote_item_option')->getCollection()->addItemFilter($request->getParam('id'));
            $formData = array();
            foreach ($options as $option)
                $formData[$option->getCode()] = $option->getValue();
            return new Varien_Object($formData);
        }
        return new Varien_Object();
    }

    public function enableScheduleSend() {
        return Mage::helper('giftvoucher')->getInterfaceConfig('schedule');
    }

    public function getGiftAmountDescription() {
        if (!$this->hasData('gift_amount_description')) {
            $product = $this->getProduct();
            $this->setData('gift_amount_description', '');
            if ($product->getShowGiftAmountDesc()) {
                if ($product->getGiftAmountDesc()) {
                    $this->setData('gift_amount_description', $product->getGiftAmountDesc());
                } else {
                    $this->setData('gift_amount_description', Mage::helper('giftvoucher')->getInterfaceConfig('description')
                    );
                }
            }
        }
        return $this->getData('gift_amount_description');
    }

    /**
     * 
     * @return type
     */
    public function getAvailableTemplate() {
        $templates = Mage::getModel('giftvoucher/gifttemplate')->getCollection()
                ->addFieldToFilter('status', '1');
        return $templates->getData();
    }

    public function getPriceFormatJs() {
        $priceFormat = Mage::app()->getLocale()->getJsPriceFormat();
        return Mage::helper('core')->jsonEncode($priceFormat);
    }

    public function isGiftvoucherProduct() {
        $items = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getAllItems();
        foreach ($items as $item) {
            if ($item->getProductType() == 'giftvoucher') {
                return true;
            }
        }
        return false;
    }

}
