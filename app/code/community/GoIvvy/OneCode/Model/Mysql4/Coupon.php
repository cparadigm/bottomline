<?php
class GoIvvy_OneCode_Model_Mysql4_Coupon extends Mage_SalesRule_Model_Mysql4_Coupon
{
    protected function _construct()
    {   
        $this->_init('salesrule/coupon', 'coupon_id');
    }   
}
