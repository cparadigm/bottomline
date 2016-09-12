<?php

class Boardroom_OneClickOrder_FormController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        //Get current layout state
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template', 'boardroom.one_click_order', array(
                'template' => 'boardroom/one-click-order/form.phtml'
            )
        );
        $this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session');
        $this->renderLayout();
    }

    public function submitAction() {
        $data = $this->getRequest()->getPost();
        
        if (isset($data['skip_cart'])) {
            $cart = Mage::getModel('checkout/cart');
            $cart->init();
            foreach($data['products'] as $product) {
                $product = Mage::getModel('catalog/product')->load($product);
                $cart->addProduct($product, array( 'product_id' => $product->getId(), 'qty' => 1));
            }
            $cart->save();
            $this->_redirect('checkout/onepage');
            return;
        }

        $quote = Mage::getModel('sales/quote')->setStoreId(Mage::app()->getStore()->getStoreId());

        $buyInfo = array('qty' => 1);

        foreach($data['products'] as $product) {
            $product = Mage::getModel('catalog/product')->load($product);
            $quote->addProduct($product, new Varien_Object($buyInfo));
        }

        $billingAddress = array(
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' =>  $data['email'],
            'street' => $data['street'],
            'city' => $data['city'],
            'region_id' => $data['region_id'],
            'region' => $data['region'],
            'postcode' => $data['postcode'],
            'country_id' => 'US',
            'telephone' =>  $data['telephone']
        );
        $quote->getBillingAddress()
            ->addData($billingAddress);
        $quote->getShippingAddress()
            ->addData($billingAddress);
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate')
            ->setCollectShippingRates(true)
            ->collectTotals();

        $payment_method = $data['payment'];
        if ($payment_method['method']=='ccsave') {
            $payment_method['cc_owner'] = $data['firstname'].' '.$data['lastname'];
        }

        $quote->getPayment()->importData($payment_method);
        $quote->setCheckoutMethod('guest')
            ->setCustomerId(null)
            ->setCustomerEmail($quote->getBillingAddress()->getEmail())
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
        $quote->save();

        Mage::getSingleton('core/session')->setQuoteId($quote->getId());
        session_write_close();

        if (isset($data['redirect'])&&$data['redirect']!='') {
            $this->_redirectUrl($data['redirect']);
        } else if (isset($data['redirect'])&&$data['redirect']=='') {
            $quote->collectTotals();
            $quote->save();

            $service = Mage::getModel('sales/service_quote', $quote);
            $service->submitAll();

            $order = $service->getOrder();
            $order->getSendConfirmation(null);
            $order->sendNewOrderEmail();

            $this->_redirect('checkout/onepage/success');
        } else {
            $this->_redirect('one-click-order/secondoffer');
        }
    }

}
