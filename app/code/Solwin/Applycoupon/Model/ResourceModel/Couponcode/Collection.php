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
namespace Solwin\Applycoupon\Model\ResourceModel\Couponcode;

class Collection
extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * ID Field Name
     * 
     * @var string
     */
    protected $_idFieldName = 'couponcode_id';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'solwin_applycoupon_couponcode_collection';

    /**
     * Event object
     * 
     * @var string
     */
    protected $_eventObject = 'couponcode_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
                'Solwin\Applycoupon\Model\Couponcode',
                'Solwin\Applycoupon\Model\ResourceModel\Couponcode'
                );
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\Zend_Db_Select::GROUP);
        return $countSelect;
    }

}