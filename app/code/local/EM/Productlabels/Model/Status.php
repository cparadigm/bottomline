<?php
class EM_Productlabels_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 0;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('productlabels')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('productlabels')->__('Disabled')
        );
    }
}