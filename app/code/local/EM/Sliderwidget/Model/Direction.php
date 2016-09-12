<?php
class EM_Sliderwidget_Model_Direction extends Mage_Core_Model_Abstract
{
	/*
		Get List of Direction
		return : array(
			array(
				'value'	=>	'',
				'label'	=>	''
			)
		)
	*/
	public function toOptionArray()
    {
		return array(
			array(
				'value'	=>	'0',
				'label'	=>	Mage::helper('sliderwidget')->__('Horizontal')
			),
			array(
				'value'	=>	'1',
				'label'	=>	Mage::helper('sliderwidget')->__('Vertical')
			)			
		);
    }
}