<?php
class Boardroom_Orderdetailreports_Block_Adminhtml_Orderdetailreports extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_orderdetailreports';
        $this->_blockGroup = 'boardroom_orderdetailreports';
        $this->_headerText = Mage::helper('boardroom_orderdetailreports')->__('Orderdetailreports Report');
        parent::__construct();
        $this->_removeButton('add');
    }
}
