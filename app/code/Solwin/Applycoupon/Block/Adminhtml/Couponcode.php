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
namespace Solwin\Applycoupon\Block\Adminhtml;

class Couponcode extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_couponcode';
        $this->_blockGroup = 'Solwin_Applycoupon';
        $this->_headerText = __('Couponcodes');
        $this->_addButtonLabel = __('Create New Couponcode');
        parent::_construct();
    }
}