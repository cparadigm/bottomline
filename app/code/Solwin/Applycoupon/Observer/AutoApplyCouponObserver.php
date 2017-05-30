<?php

/**
 * Solwin Infotech
 * Solwin Discount Coupon Code Link Extension
 *
 * @category   Solwin
 * @package    Solwin_Applycoupon
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/ 
 */

namespace Solwin\Applycoupon\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as Observer;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

class AutoApplyCouponObserver implements ObserverInterface {

    protected $_request;
    protected $_customerSession;
    protected $_checkoutSession;
    protected $_quote;
    protected $_cartHelper;
    protected $_salesCoupon;
    protected $_salesRule;
    protected $_genericSession;
    protected $_cookieManager;
    protected $_cookieMetadataFactory;
    protected $_cookieMetadata;
    protected $_messageManager;
    protected $_response;
    protected $_responseUrl;
    protected $_storeManager;
    protected $_resultFactory;
    protected $_helper;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Session\Generic $genericSession
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\SalesRule\Model\Coupon $salesCoupon
     * @param \Magento\SalesRule\Model\Rule $salesRule
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\App\Response\Http $responseUrl
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ResultFactory $resultFactory
     * @param \Solwin\Applycoupon\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\App\Request\Http $request, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Session\Generic $genericSession, \Magento\Quote\Model\Quote $quote, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\SalesRule\Model\Coupon $salesCoupon, \Magento\SalesRule\Model\Rule $salesRule, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager, CookieMetadataFactory $cookieMetadataFactory, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\App\ResponseInterface $response, \Magento\Framework\App\Response\Http $responseUrl, \Magento\Store\Model\StoreManagerInterface $storeManager, ResultFactory $resultFactory, \Solwin\Applycoupon\Helper\Data $helper
    ) {
        $this->_request = $request;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_quote = $quote;
        $this->_cartHelper = $cartHelper;
        $this->_salesCoupon = $salesCoupon;
        $this->_salesRule = $salesRule;
        $this->_genericSession = $genericSession;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_messageManager = $messageManager;
        $this->_response = $response;
        $this->_responseUrl = $responseUrl;
        $this->_storeManager = $storeManager;
        $this->_resultFactory = $resultFactory;
        $this->_helper = $helper;
    }

    /**
     * Set cookie meta data
     */
    public function setCookieMetadata() {
        $this->_cookieMetadata = $this->_cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(time() + 86400)
                ->setPath('/');
    }

    /**
     * Get cookie meta data
     */
    public function getCookieMetadata() {
        return $this->_cookieMetadata;
    }

    public function execute(Observer $observer) {

        $moduleEnable = $this->_helper->getConfigValue(
                'applycouponsection/applycoupongroup/enable'
        );
        $returnUrl = '';
        if ($moduleEnable) {

            $this->setCookieMetadata();
            $params = array_change_key_case(
                    $this->_request->getParams(), CASE_LOWER
            );
            if (isset($params['quote_id'])) {
                $quote = $this->_quote->load(intval($params['quote_id']));
                if (is_object($quote) && $quote->getId() && $quote->getIsActive()) {
                    if ($this->_customerSession->isLoggedIn() && $quote->getCustomerId()) {
                        if ($quote->getCustomerId() !=
                                        $this->_customerSession
                                        ->getCustomer()
                                        ->getId()) {
                            $this->_customerSession->logout();
                            $this->_response
                                    ->setRedirect(
                                            '/customer/account/login', 301
                                    )
                                    ->sendResponse();
                        } else {
                            $this->_checkoutSession
                                    ->setQuoteId($quote->getId());
                        }
                    } elseif ($this->_customerSession->isLoggedIn() && !$quote->getCustomerId()) {
                        $this->_checkoutSession->setQuoteId($quote->getId());
                    } elseif (!$this->_customerSession->isLoggedIn()) {
                        if ($quote->getCustomerId()) {
                            $this->_response
                                    ->setRedirect(
                                            '/customer/account/login', 301
                                    )
                                    ->sendResponse();
                        } else {
                            $this->_checkoutSession
                                    ->setQuoteId($quote->getId());
                        }
                    }
                } else {
                    $this->_messageManager
                            ->addNotice('Sorry, looks like you\'ve got Invalid '
                                    . 'Cart ID or you have already made a '
                                    . 'purchase with the cart you are trying '
                                    . 'to access. Thank you!');
                }
            } else {
                
            }

            $discountEmail = '';
            if (array_key_exists('discount-email', $params) !== false) {
                $discountEmail = $params['discount-email'];
            } elseif (array_key_exists('email', $params) !== false) {
                $discountEmail = $params['email'];
            } elseif (array_key_exists('utm_email', $params) !== false) {
                $discountEmail = $params['utm_email'];
            }
            if (isset($discountEmail)) {
                if ($discountEmail != '') {
                    if (\Zend_Validate::is($discountEmail, 'EmailAddress')) {
                        if ($this->_cartHelper->getItemsCount()) {
                            $this->_checkoutSession->getQuote()
                                    ->setCustomerEmail($discountEmail)
                                    ->save();
                            $this->_cookieManager
                                    ->deleteCookie(
                                            'discount-email', $this->getCookieMetadata()
                            );
                        } else {
                            $this->_cookieManager
                                    ->setPublicCookie(
                                            'discount-email', $discountEmail, $this->getCookieMetadata()
                            );
                        }
                    }
                }
            }

            if (isset($params['coupon']) || (isset($params['utm_promocode']))) {

                $couponCode = $params['coupon'];
                $couponCollection = $this->_helper
                        ->getCouponCollection($couponCode);

                $couponStatus = 0;
                foreach ($couponCollection as $coupon) {
                    $couponStatus = $coupon['status'];
                }

                // check coupon status is enabled.

                if ($couponStatus) {

                    if (isset($params['coupon'])) {
                        $coupon = $params['coupon'];
                    }

                    if (isset($params['utm_promocode'])) {
                        $coupon = $params['utm_promocode'];
                    }

                    if ($coupon != '') {
                        if ($this->_isCouponValid($coupon)) {
                            if ($this->_cartHelper->getItemsCount()) {
                                $this->_checkoutSession->getQuote()
                                        ->setCouponCode($coupon)
                                        ->save();
                                $this->_cookieManager
                                        ->deleteCookie(
                                                'discount_code', $this->getCookieMetadata()
                                );
                            } else {
                                $this->_cookieManager
                                        ->setPublicCookie(
                                                'discount_code', $coupon, $this->getCookieMetadata()
                                );
                            }
                            $returnUrl = $params['return_url'];

                            if ($returnUrl != 'no') {
                                header('Location: ' . $params['return_url']
                                        . '?cc=' . $params['coupon']
                                        . '&codesuccess=1');
                                exit;
                            } else if ($returnUrl == 'no') {
                                $redirectUrl = explode(
                                        '?', $this->_helper->getCurrentUrl()
                                );
                                header('Location: ' . $redirectUrl[0]
                                        . '?cc=' . $params['coupon']
                                        . '&codesuccess=1');
                                exit;
                            }
                        }
                    }
                }
            } else {
                $this->checkoutCartAddProductComplete();
            }
        }
    }

    public function checkoutCartAddProductComplete() {
        $coupon = $this->_cookieManager->getCookie('discount_code');
        if (($coupon) && ($this->_isCouponValid($coupon)) && ($this->_cartHelper->getItemsCount())) {
            $this->_checkoutSession
                    ->getQuote()
                    ->setCouponCode($coupon)
                    ->save();
            $this->_cookieManager
                    ->deleteCookie(
                            'discount_code', $this->getCookieMetadata()
            );
        }

        $email = $this->_cookieManager->getCookie('discount-email');
        if ($email && \Zend_Validate::is($email, 'EmailAddress')) {
            if ($this->_cartHelper->getItemsCount()) {
                $this->_checkoutSession
                        ->getQuote()
                        ->setCustomerEmail($email)
                        ->save();
                $this->_cookieManager
                        ->deleteCookie(
                                'discount-email', $this->getCookieMetadata()
                );
            }
        }
    }

    protected function _isCouponValid($couponCode) {
        try {
            $coupon = $this->_salesCoupon->load($couponCode, 'code');
            if (is_object($coupon)) {
                $rule = $this->_salesRule->load($coupon->getRuleId());
                if (is_object($rule)) {
                    $conditionsUnSerialized = unserialize(
                            $rule->getConditionsSerialized()
                    );
                    if ($rule->getIsActive()) {
                        $todaydate = strtotime(date('Y-m-d'));
                        $startDate = strtotime($rule->getFromDate());
                        $endDate = strtotime($rule->getToDate());
                       // if (($todaydate >= $startDate) && ($todaydate <= $endDate)) {
                            if (is_array($conditionsUnSerialized) && (
                                    isset($conditionsUnSerialized['conditions'])
                                    ) && (
                                    is_array($conditionsUnSerialized['conditions'])
                                    )
                            ) {
                                foreach ($conditionsUnSerialized['conditions']
                                as $condition) {
                                    if (isset($condition['attribute']) && ($condition['attribute'] ==
                                            'base_subtotal') &&
                                            (isset($condition['operator'])) && ($condition['operator'] == '>=') && (isset($condition['value'])) && ($condition['value'] > 0) && (
                                                    $this->_checkoutSession
                                                    ->getQuote()
                                                    ->getSubtotal() <
                                            $condition['value']
                                            )
                                    ) {
                                        $this->_cookieManager
                                                ->setPublicCookie(
                                                        'discount_code', $couponCode, $this->getCookieMetadata()
                                        );
                                        return false;
                                    }
                                }
                            }
                            return true;
                      //  }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

}
