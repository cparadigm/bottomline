<?php

class EM_Dynamicproducts_Model_Featuredattributeset extends Mage_Core_Model_Abstract 
{

	public function toOptionArray()
	{
		$result[]=array('value' => 'em_featured','label' =>  'Featured Product');
		$result[]=array('value' => 'em_deal','label' =>  'Special Deal');
		$result[]=array('value' => 'em_hot','label' =>  'Hot Product');
		return $result;
	}
	
}
?>
