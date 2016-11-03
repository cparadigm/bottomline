<?php

//class TBT_Testsweet_IndexController extends Mage_Core_Controller_Front_Action { /*bypass admin*/
class TBT_Testsweet_TestsweetController extends Mage_Adminhtml_Controller_Action {

    protected function _isAllowed()
    {
        return true;
    }
    
    public function allAction() {
        /* @var $test TBT_Testsweet_Model_Test_Abstract */
        Mage::getModel('testsweet/test')->all();
    }

    public function indexAction() {
        echo "<pre>";
        $this->allAction();
        echo "</pre>";
    }
}