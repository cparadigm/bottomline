<?php

class EM_Bestsellerproducts_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('bestsellerproducts')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('bestsellerproducts')->__('Disabled')
        );
    }
}