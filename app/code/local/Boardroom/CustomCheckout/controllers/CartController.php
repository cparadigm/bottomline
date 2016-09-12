<?php

require_once "Mage/Checkout/controllers/CartController.php";
class Boardroom_CustomCheckout_CartController extends Mage_Checkout_CartController
{
	
    /**
     * Add product to shopping cart action
     *
     * @return Mage_Core_Controller_Varien_Action
     * @throws Exception
     */
    public function addAction()
    {
        $skipcartPost = $this->getRequest()->getPost('skip_cart');
        $skipcartParam = $this->getRequest()->getParam('skip_cart');
        if ($skipcartPost || $skipcartParam) {
        	if (!$this->_validateFormKey()) {
           		$this->_goBack();
           		return;
        	}
        	$cart   = $this->_getCart();
        	$params = $this->getRequest()->getParams();
			$data = $this->getRequest()->getPost();
        	try {

                if($data['product']) {
                    foreach ($data['product'] as $product) {
		           	 	$product = Mage::getModel('catalog/product')
                			->setStoreId(Mage::app()->getStore()->getId())
                			->load($product);

		            	/**
		             	* Check product availability
		             	*/
		            	if (!$product) {
		                	$this->_goBack();
		                	return;
		            	}

						$cart->addProduct($product, array('qty'=>1));

						/**
						* @todo remove wishlist observer processAddToCart
						*/
						Mage::dispatchEvent('checkout_cart_add_product_complete',
							array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
						);
                    }
					$cart->save();
					$this->_getSession()->setCartWasUpdated(true);
                }

				if (!$this->_getSession()->getNoCartRedirect(true)) {
					if (!$cart->getQuote()->getHasError()) {
						if ($skipcartPost=='Yes' || $skipcartParam=='Yes') {
							$this->_redirect('checkout/onepage');
						} else if ($skipcartPost=='No' || $skipcartParam=='No') {
							$this->_redirect('checkout/cart');
						}
					}
				}
			} catch (Mage_Core_Exception $e) {
				if ($this->_getSession()->getUseNotice(true)) {
					$this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
				} else {
					$messages = array_unique(explode("\n", $e->getMessage()));
					foreach ($messages as $message) {
						$this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
					}
				}

				$url = $this->_getSession()->getRedirectUrl(true);
				if ($url) {
					$this->getResponse()->setRedirect($url);
				} else {
					$this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
				}
			} catch (Exception $e) {
				$this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
				Mage::logException($e);
				$this->_goBack();
			}
		} else {
			parent::addAction();
		}
    }
	
}

?>