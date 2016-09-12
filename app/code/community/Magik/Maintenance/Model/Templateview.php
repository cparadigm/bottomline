<?php
class Magik_Maintenance_Model_Templateview
{
    public function toOptionArray()
    {
        return array(
	   
	     array('value'=>'maintenance', 'label'=>Mage::helper('maintenance')->__('Maintenance Page')),
            
            
        );
    }

}
