<?php
class Magik_Onestepcheckout_Model_System_Config_Paymentmethods
{   /*		  
    protected function _getPaymentMethods()
    {
    	
        return Mage::getSingleton('payment/config')->getActiveMethods();
    }
	public function getPaymentMe(){
		
		//var_dump($this-> _getPaymentMethods());
	}*/
    public function toOptionArray()
    {
        $methods = array(array('value'=>'', 'label'=>''));
        $payments = Mage::getSingleton('payment/config')->getActiveMethods();
        foreach ($payments as $paymentCode=>$paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = array(
                'label'   => $paymentTitle,
                'value' => $paymentCode,
            );
        }

        return $methods;
    }
} 
?>