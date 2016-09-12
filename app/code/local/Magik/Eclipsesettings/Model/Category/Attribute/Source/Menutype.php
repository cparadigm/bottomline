<?php
class Magik_Eclipsesettings_Model_Category_Attribute_Source_Menutype
	extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{	
	protected $_options;
    
	public function getAllOptions()
	{
        return array(
            array('value' => 'noimage', 'label' => Mage::helper('eclipsesettings')->__('No Image')),
            array('value' => 'singleimage', 'label' => Mage::helper('eclipsesettings')->__('Single Image')),
            array('value' => 'imgwithtext', 'label' => Mage::helper('eclipsesettings')->__('Image With Text'))
        );
    }
}
