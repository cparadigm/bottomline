<?php

class EM_Saleproducts_Model_Featuredattributeset extends Mage_Core_Model_Abstract 
{

	public function toOptionArray()
	{
		$result[]=array('value' => 'Featured','label' =>  'Featured Product');
		$result[]=array('value' => 'Deal','label' =>  'Special Deal');
		$result[]=array('value' => 'Hot','label' =>  'Hot Product');
		return $result;
	}
	
}
?>
