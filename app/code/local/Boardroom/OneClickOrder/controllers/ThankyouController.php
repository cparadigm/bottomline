<?php

class Boardroom_OneClickOrder_ThankyouController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        //Get current layout state
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock(
                'Mage_Core_Block_Template', 'Boardroom_OneClickOrder', array(
            'template' => 'boardroom/one-click-order/thankyou.phtml'
                )
        );echo 999;
        //$this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session');
        $this->renderLayout();
    }

}
?>