<?php


class Magik_Eclipsesettings_Model_Config_Menu
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value'=>'classic-menu',
	            'label' => Mage::helper('eclipsesettings')->__('Classic Menu')),
            array(
	            'value'=>'mega-menu',
	            'label' => Mage::helper('eclipsesettings')->__('Mega Menu')),                       

        );
    }

}
