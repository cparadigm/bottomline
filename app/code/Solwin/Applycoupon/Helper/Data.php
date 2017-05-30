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
namespace Solwin\Applycoupon\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var  \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $_storeManager;
    /**
     * @var  \Solwin\Applycoupon\Model\CouponcodeFactory
     */
    protected $_modelCouponcodeFactory;
    /**
     * @var  \Solwin\Applycoupon\Model\ResourceModel\Couponcode\Collection
     */
    protected $_collection;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Solwin\Applycoupon\Model\ResourceModel\Couponcode\Collection $col
     * @param \Solwin\Applycoupon\Model\CouponcodeFactory 
     * $modelCouponcodeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Solwin\Applycoupon\Model\ResourceModel\Couponcode\Collection $col,
        \Solwin\Applycoupon\Model\CouponcodeFactory $modelCouponcodeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_modelCouponcodeFactory = $modelCouponcodeFactory;
        $this->_collection = $col;
        parent::__construct($context);
    }

    /**
     * Get coupon colletion
     */
    public function getCouponCollection($couponCode) {
        $couponCollection = $this->_collection
                ->addFieldToFilter('coupon_code', $couponCode)
                ->addFieldToFilter('status', 1);

        return $couponCollection;
    }

    /**
     * Load coupon collection by id
     */
    public function getSingleCouponCollection($couponId) {
        $couponModel = $this->_modelCouponcodeFactory->create();
        $cpCollection = $couponModel->load($couponId);
        return $cpCollection;
    }
    
    /**
     * Get base url
     */
    public function getBaseUrl() {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get media url
     */
    public function getMediaUrl() {
        return $this->_storeManager->getStore()
                ->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        );
    }

    /**
     * Get current url
     */
    public function getCurrentUrl() {
        return $this->_urlBuilder->getCurrentUrl();
    }

    /**
     * Get configuration option value
     */
    public function getConfigValue($value = '') {
        return $this->scopeConfig->getValue(
                $value,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
    }

}