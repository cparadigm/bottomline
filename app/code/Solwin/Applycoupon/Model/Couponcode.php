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
namespace Solwin\Applycoupon\Model;

/**
 * @method Couponcode setRuleName($ruleName)
 * @method Couponcode setCouponCode($couponCode)
 * @method Couponcode setRedirectUrl($redirectUrl)
 * @method Couponcode setLinkWithRedirection($linkWithRedirection)
 * @method Couponcode setLinkWithoutRedirection($linkWithoutRedirection)
 * @method Couponcode setViewsCount($viewsCount)
 * @method Couponcode setStatus($status)
 * @method mixed getRuleName()
 * @method mixed getCouponCode()
 * @method mixed getRedirectUrl()
 * @method mixed getLinkWithRedirection()
 * @method mixed getLinkWithoutRedirection()
 * @method mixed getViewsCount()
 * @method mixed getStatus()
 * @method Couponcode setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Couponcode setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 */
class Couponcode extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'solwin_applycoupon_couponcode';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'solwin_applycoupon_couponcode';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'solwin_applycoupon_couponcode';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Solwin\Applycoupon\Model\ResourceModel\Couponcode');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['status'] = '2';
        return $values;
    }
}
