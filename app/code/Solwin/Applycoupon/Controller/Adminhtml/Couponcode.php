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
namespace Solwin\Applycoupon\Controller\Adminhtml;

abstract class Couponcode extends \Magento\Backend\App\Action
{
    /**
     * Couponcode Factory
     * 
     * @var \Solwin\Applycoupon\Model\CouponcodeFactory
     */
    protected $_couponcodeFactory;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * constructor
     * 
     * @param \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_couponcodeFactory     = $couponcodeFactory;
        $this->_coreRegistry          = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init Couponcode
     *
     * @return \Solwin\Applycoupon\Model\Couponcode
     */
    protected function _initCouponcode()
    {
        $couponcodeId  = (int) $this->getRequest()->getParam('couponcode_id');
        /** @var \Solwin\Applycoupon\Model\Couponcode $couponcode */
        $couponcode    = $this->_couponcodeFactory->create();
        if ($couponcodeId) {
            $couponcode->load($couponcodeId);
        }
        $this->_coreRegistry->register(
                'solwin_applycoupon_couponcode',
                $couponcode
                );
        return $couponcode;
    }
}