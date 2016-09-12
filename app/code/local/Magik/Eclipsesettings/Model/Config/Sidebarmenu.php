<?php


class Magik_Eclipsesettings_Model_Config_Sidebarmenu
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value'=>'sidebar-classic-menu',
	            'label' => Mage::helper('eclipsesettings')->__('Sidebar Classic Menu')),
            array(
	            'value'=>'sidebar-mega-menu',
	            'label' => Mage::helper('eclipsesettings')->__('Sidebar Mega Menu')),                       

        );
    }

}
