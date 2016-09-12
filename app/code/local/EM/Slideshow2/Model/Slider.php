<?php

class EM_Slideshow2_Model_Slider extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('slideshow2/slider');
    }
	
	public function toOptionArray()
    {
        return $this->getAttributeSetList();
    }
    public function getAttributeSetList()
    {
		$collection = $this->getCollection()->addFieldToFilter("status",1);
		$data	=	$collection->getData();
		$result	= array();
		$result[] = array('value' => '','label' => 'Please choose slideshow');
		foreach($data as $value)
			$result[] = array('value' => $value['id'],'label' => $value['name']);
		return $result;
	}
}