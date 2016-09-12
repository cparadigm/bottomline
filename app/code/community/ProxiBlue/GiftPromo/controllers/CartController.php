<?php

/**
 * Gift Promo cart controller
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 * */
require_once(Mage::getModuleDir('controllers',
        'Mage_Checkout') . DS . 'CartController.php');

/**
 * Shopping cart controller
 */
class ProxiBlue_GiftPromo_CartController
    extends Mage_Checkout_CartController
{

    /**
     * Internal holder for helper class
     *
     * @var object
     */
    private $_helper;

    /**
     * Get the helper class and cache teh object
     * @return object
     */
    private function _getHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::Helper('giftpromo');
        }
        return $this->_helper;
    }

    /**
     * Add product to shopping cart action
     */
    public function addGiftAction()
    {
        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        try {
            if (isset($params['super_attribute'])) {
                $params['super_attribute'] = array_pop($params['super_attribute']);
            }
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array(
                    'locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }
            $product = $this->_initProduct();
            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();
                return;
            }
            // load the rule if given
            if ($this->getRequest()->getParam('rule_id')) {
                $ruleObject = Mage::getModel('giftpromo/promo_rule')->load($this->getRequest()->getParam('rule_id'));
                if ($ruleObject->getAllowGiftSelection() && $ruleObject->validate($cart->getQuote())) {
                    $giftProducts = $ruleObject->getItemsArray();
                    $giftProductToAdd = $giftProducts[$product->getId()];
                    // flag as current selected gift
                    $currentSelectedGifts = $this->_getHelper()->getCurrentSelectedGifts();
                    // are we trying to replace the same gift?
                    if (array_key_exists($this->getRequest()->getParam('selected_gift_item_key'),
                            $currentSelectedGifts) && $currentSelectedGifts[$this->getRequest()->getParam('selected_gift_item_key')] == $giftProductToAdd->getId()) {
                        $this->_goBack();
                        return $this;
                    }
                    $currentSelectedGifts = array(
                        $this->getRequest()->getParam('selected_gift_item_key') => $giftProductToAdd->getId()) + $currentSelectedGifts;

                    $currentSelectedGiftsParts = explode("_",
                        $this->getRequest()->getParam('selected_gift_item_key'));
                    if (is_array($currentSelectedGiftsParts)) {
                        $params['parent_quote_item_id'] = array_pop($currentSelectedGiftsParts);
                    }
                    $this->_getHelper()->setCurrentSelectedGifts($currentSelectedGifts);
                    try {
                        $this->_getHelper()->addGiftItems(array(
                            $giftProductToAdd),
                            $ruleObject,
                            $params);
                    } catch (Exception $e) {
                        //something wrong, clear selected data
                        $this->_getHelper()->resetCurrentSelectedGift($ruleObject->getId(),
                            $giftProductToAdd->getId());
                        //throw exception forward
                        Mage::throwException($e->getMessage());
                    }
                }
            } else {
                mage::log('Rule param for gifting not passed to controller.');
            }
            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            $this->_goBack();
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n",
                        $e->getMessage()));
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
            $this->_getSession()->addException($e,
                $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            $this->_goBack();
        }
    }


    /**
     * Add product to shopping cart action
     */
    public function deleteGiftAction()
    {
        $params = $this->getRequest()->getParams();
        if (array_key_exists('rule_id',
                $params)) {
            $cartItem = $this->_getQuote()->getItemById($params['id']);
            $infoBuyRequest = $cartItem->getOptionByCode('info_buyRequest');
            $buyRequest = new Varien_Object(unserialize($infoBuyRequest->getValue()));
            $currentSelectedGifts = $this->_getHelper()->getCurrentSelectedGifts();
            if (array_key_exists($buyRequest->getSelectedGiftItemKey(),
                    $currentSelectedGifts)) {
                unset($currentSelectedGifts[$buyRequest->getSelectedGiftItemKey()]);
                $this->_getHelper()->setCurrentSelectedGifts($currentSelectedGifts);
            }
        } else if (array_key_exists('id',
                $params)) {
            $giftItem = $this->_getQuote()->getItemById($params['id']);
            $parentOfGift = $this->_getHelper()->getParentQuoteItemOfGift($giftItem);
            if ($parentOfGift !== false) {
                $this->_getHelper()->resetCurrentSelectedGift($parentOfGift->getId(),
                    $giftItem->getProductId());
                $this->_getHelper()->parkGift($parentOfGift->getId(),
                    $giftItem->getProduct()->getId());
            }
        }
        $this->_forward('delete');
    }

    /**
     * Get request for product add to cart procedure
     *
     * @param   mixed $requestInfo
     * @return  Varien_Object
     */
    private function _getProductRequest($requestInfo)
    {
        if ($requestInfo instanceof Varien_Object) {
            $request = $requestInfo;
        } elseif (is_numeric($requestInfo)) {
            $request = new Varien_Object(array(
                'qty' => $requestInfo));
        } else {
            $request = new Varien_Object($requestInfo);
        }

        if (!$request->hasQty()) {
            $request->setQty(1);
        }

        return $request;
    }

    /**
     * Simply return the number of items in the cart
     */
    public function getTopCartQtyAction()
    {
        $cartQty = (int) Mage::Helper('checkout/cart')->getSummaryCount();
        if (is_integer($cartQty)) {
            $this->getResponse()->setHeader('Content-type',
                'application/json')->setBody($this->_success($cartQty));
        } else {
            $this->getResponse()->setHeader('Content-type',
                'application/json')->setBody($this->_error($cartQty));
        }
        return $this;
    }

    /**
     * Render the cart display skippping cache (top cart slider on all pages)
     */
    public function getTopCartAction()
    {
        $this->loadLayout();
        $layout = Mage::getSingleton('core/layout');
        $block = $layout->getBlock('cart_sidebar');
        $html = $block->toHtml();
        $this->getResponse()->setHeader('Content-type',
            'application/json')->setBody($this->_success($html));
        return $this;
    }

    /**
     * Generate a JSON error object
     *
     * @param string $content
     */
    protected function _error($content)
    {
        return Zend_Json::encode(array(
                "error" => true,
                "content" => $content
                )
        );
    }

    /**
     * Success wrappper
     *
     * @var String
     *
     * @return string
     */
    protected function _success($content)
    {
        return Zend_Json::encode(array(
                "error" => false,
                "content" => $content
        ));
    }

}
