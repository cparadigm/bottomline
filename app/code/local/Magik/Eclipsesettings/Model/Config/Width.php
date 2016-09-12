<?php


class Magik_Eclipsesettings_Model_Config_Width
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value' => 'flexible',
	            'label' => Mage::helper('eclipsesettings')->__('flexible')),
            array(
	            'value' => 'fixed',
	            'label' => Mage::helper('eclipsesettings')->__('fixed')),
        );
    }

}
