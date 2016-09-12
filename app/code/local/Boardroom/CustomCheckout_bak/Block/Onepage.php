<?php

class Boardroom_CustomCheckout_Block_Onepage extends Mage_Checkout_Block_Onepage
{

    /**
     * Get 'one step checkout' step data
     *
     * @return array
     */
    public function getSteps()
    {
        $steps = array();
        $stepCodes = $this->_getStepCodes();

        if ($this->isCustomerLoggedIn()) {
            $stepCodes = array_diff($stepCodes, array('login'));
        }

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }

        return $steps;
    }

    /**
     * Get checkout steps codes
     *
     * @return array
     */
    protected function _getStepCodes()
    {
        //$offer = $this->getRequest()->getPost('skip_cart');
        //if ($offer) {
        //    return array('login', 'billing', 'shipping', 'payment');
        //} else {
            return array('login', 'billing', 'shipping', 'shipping_method', 'payment', 'review');
			//}
    }

}