<?php

require_once "Mage/Checkout/controllers/OnepageController.php";
class Boardroom_CustomCheckout_OnepageController extends Mage_Checkout_OnepageController
{

    public function indexAction() {
        $skipcartPost = $this->getRequest()->getPost('skip_cart');
        $skipcartParam = $this->getRequest()->getParam('skip_cart');
        if ($skipcartPost || $skipcartParam) {
			/*
            $data = $this->getRequest()->getPost();
            $cart = Mage::getSingleton('checkout/cart');
			$quote = Mage::getSingleton('checkout/type_onepage')->getQuote();

            if (!$quote->hasItems()) {
                if($data['product']) {
                    foreach ($data['product'] as $product) {
                        $product = Mage::getModel('catalog/product')->load($product);
                        $cart->addProduct($product, array('qty' => 1));
                        Mage::getSingleton('checkout/session')->setSkipCartProduct($product);
						$cart->save();
                    }
                }
			
                $cart->save();
                $quote->collectTotals()->save();
				//Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
            }
			*/
/*
            if (!$quote->hasItems() || $quote->getHasError()) {
                $this->_redirect('checkout/cart');
                return;
            }
            
            $this->getOnepage()->initCheckout();
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
            
            $this->getLayout()->getBlock('checkout.onepage')->setTemplate('boardroom/customcheckout/onepage.phtml');
            $this->getLayout()->getBlock('checkout.onepage.login')->setTemplate('boardroom/customcheckout/onepage/login.phtml');
            $this->getLayout()->getBlock('checkout.onepage.billing')->setTemplate('boardroom/customcheckout/onepage/billing.phtml');
            $this->getLayout()->getBlock('checkout.onepage.shipping')->setTemplate('boardroom/customcheckout/onepage/shipping.phtml');
            $this->getLayout()->getBlock('checkout.onepage.payment')->setTemplate('boardroom/customcheckout/onepage/payment.phtml');
            $this->getLayout()->getBlock('checkout.payment.methods')->setTemplate('boardroom/customcheckout/onepage/payment/info.phtml');
            $this->getLayout()->getBlock('checkout.progress')->setTemplate('boardroom/customcheckout/onepage/rightbar.phtml');
            */
			///var_dump(Mage::getSingleton('checkout/type_onepage')->getQuote()->hasItems());die();
			parent::indexAction();
            //$this->renderLayout();
        } else {
            parent::indexAction();
        }

    }

    public function addCrossSellAction() {
        $data = $this->getRequest()->getPost();
        $quote = $this->getOnepage()->getQuote();
        $cart = Mage::getModel('checkout/cart');
        $cart->init();

        foreach($data['crosssellId'] as $product) {
            $product = Mage::getModel('catalog/product')->load($product);
            $cart->addProduct($product, 1);
        }
        Mage::getSingleton('customer/session')->setCartWasUpdated(true);
        $cart->save();
        $quote->collectTotals()->save();

        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->_redirect('checkout/onepage',array('_query'=>'skip_cart=1'));
        return;

    }

    public function submitAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $quote = $this->getOnepage()->getQuote();
            $quoteItems = $quote->getAllVisibleItems();
            foreach($quoteItems as $quoteItem) {
                $itemId = $quoteItem->getId();
                $quote->updateItem($itemId,array('qty'=>$data['qty']));
                $quote->save();
            }

            $websiteId = Mage::app()->getWebsite()->getId();
            $store = Mage::app()->getStore();
            $customer = Mage::getModel("customer/customer");
            $customer->website_id = $websiteId;
            $customer->setStore($store);
            try {
                $customer->loadByEmail($data['login']['username']);
                $session = Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
                $session->login($data['login']['username'], $data['login']['password']);
            }catch(Exception $e){
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($data['billing']['firstname'])
                    ->setLastname($data['billing']['lastname'])
                    ->setEmail($data['login']['username'])
                    ->setPassword($data['login']['password']);
                try{
                    $customer->save();
                }
                catch (Exception $e) {
                    Mage::getSingleton('checkout/session')->addError($this->__('There was an error creating your account.'));
                    $this->_redirect('checkout/onepage',array('skip_cart'=>true));
                }

            }

            //save billing
            try {
                if (isset($data['login']['username'])) {
                    $data['billing']['email'] = trim($data['login']['username']);
                }
                $this->getOnepage()->saveBilling($data['billing'], $data['billing']['address_id']);
            }
            catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addError($this->__('There was an error saving your billing address.'));
                $this->_redirect('checkout/onepage',array('skip_cart'=>true));
            }

            //save shipping address
            try {
                $this->getOnepage()->saveShipping($data['shipping'], $data['shipping']['address_id']);
            }
            catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addError($this->__('There was an error saving your shipping address.'));
                $this->_redirect('checkout/onepage',array('skip_cart'=>true));
            }

            //save shipping method
            try {
                $this->getOnepage()
                    ->getQuote()
                    ->getShippingAddress()
                    ->setShippingMethod('tablerate_bestway')
                    ->setCollectShippingRates(true)
                    ->save();
            }
            catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addError($this->__('There was an error saving shipping.'));
                $this->_redirect('checkout/onepage',array('skip_cart'=>true));
            }

            //save payment
            try {
                $this->getOnepage()->savePayment($data['payment']);
            }
            catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addError($this->__('There was an error saving you payment information.'));
                $this->_redirect('checkout/onepage',array('skip_cart'=>true));
            }

            //save order
            if ($data['payment']) {
                $data['payment']['checks'] = Mage_Payment_Model_Method_Abstract::CHECK_USE_CHECKOUT
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
                    | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
                    | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL;
                $this->getOnepage()->getQuote()->getPayment()->importData($data['payment']);
            }
            $test = $this->getOnepage()->getQuote()->getTotals();
//var_dump($test['grand_total']->getValue());die();
            $this->getOnepage()->saveOrder();
            $this->getOnepage()->getQuote()->save();

            $this->_redirect('checkout/onepage/success');
        }

    }

}
