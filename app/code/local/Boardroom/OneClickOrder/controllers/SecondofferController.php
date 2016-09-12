<?php

class Boardroom_OneClickOrder_SecondofferController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        //Get current layout state
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template', 'boardroom.one_click_order', array(
                'template' => 'boardroom/one-click-order/secondoffer.phtml'
            )
        );
        $this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session');
        $this->renderLayout();
    }

    public function submitAction() {
        $data = $this->getRequest()->getParams();
        $quote = Mage::getModel('sales/quote')->load(Mage::getSingleton('core/session')->getQuoteId());

        if (isset($data['secondoffer'])&&isset($data['category_id'])&&(int)$data['category_id']>0) {
            $buyInfo = array('qty' => 1);
            $category = Mage::getModel('catalog/category')->load($data['category_id']);
            $products = $category->getProductCollection();
            foreach($products as $product) {
              //  $quote->addProduct($product, new Varien_Object($buyInfo));
            }
            $prod = Mage::getModel('catalog/product')->load(56);
            $quote->addProduct($prod, new Varien_Object($buyInfo));
        
        }

        $quote->collectTotals();
        $quote->save();

        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();

        $order = $service->getOrder();
        $order->getSendConfirmation(null);
        $order->sendNewOrderEmail();

        $this->_redirect('checkout/onepage/success');
    }

}
?>