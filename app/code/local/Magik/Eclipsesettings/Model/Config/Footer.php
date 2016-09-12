<?php


class Magik_Eclipsesettings_Model_Config_Footer
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value'=>'simple',
	            'label' => Mage::helper('eclipsesettings')->__('simple')),
            array(
	            'value'=>'informative',
	            'label' => Mage::helper('eclipsesettings')->__('informative')),
        );
    }

}
