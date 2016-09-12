<?php

class Boardroom_OneClickOrder_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        //Get current layout state
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock(
                'Mage_Core_Block_Template', 'boardroom.one_click_order', array(
            'template' => 'boardroom/one-click-order.phtml'
                )
        );
        $this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session');
        $this->renderLayout();
    }

    public function formAction() {
        //Get current layout state
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template', 'boardroom.one_click_order', array(
                'template' => 'boardroom/one-click-order/form.phtml'
            )
        );
        $this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session');
        $this->renderLayout();
    }

}
?>