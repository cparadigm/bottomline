<?php
/*
*
*
*
*/

class Webtex_Giftcards_Block_Checkout_Coupon extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('webtex/giftcards/checkout/onepage/coupon.phtml');
    }
}