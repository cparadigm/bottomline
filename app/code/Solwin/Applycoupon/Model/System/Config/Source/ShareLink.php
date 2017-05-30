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
namespace Solwin\Applycoupon\Model\System\Config\Source;

class ShareLink implements \Magento\Framework\Option\ArrayInterface
{

    const WITH_REDIRECT = '0';
    const WITHOUT_REDIRECT = '1';

    /* get recaptcha theme */

    public function toOptionArray() {
        return [
            self::WITH_REDIRECT => __('With Redirection'),
            self::WITHOUT_REDIRECT => __('Without Redirection')
        ];
    }

}