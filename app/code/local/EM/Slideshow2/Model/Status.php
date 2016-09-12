<?php

class EM_Slideshow2_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('slideshow2')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('slideshow2')->__('Disabled')
        );
    }
}