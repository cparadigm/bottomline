<?php


class Magik_Eclipsesettings_Model_Config_Position
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value'=>'top-left',
	            'label' => Mage::helper('eclipsesettings')->__('Top Left')),
            array(
	            'value'=>'top-right',
	            'label' => Mage::helper('eclipsesettings')->__('Top Right')),                       

        );
    }

}
