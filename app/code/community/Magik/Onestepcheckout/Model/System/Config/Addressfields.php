<?php
class Magik_Onestepcheckout_Model_System_Config_Addressfields
{
   public function toOptionArray() {
        return array(
		array('value' =>'no',	'label'=>Mage::helper('onestepcheckout')->__('No')),
		array('value'=>'opt', 'label'=>Mage::helper('onestepcheckout')->__('Optional')),
		array('value'=>'req', 'label'=>Mage::helper('onestepcheckout')->__('Required'))
		
        );
    }
}
 
