<?php
class Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_orderdetailreports';
        $this->_blockGroup = 'boardroom_orderdetailreports';
        $this->_headerText = Mage::helper('boardroom_orderdetailreports')->__('OrderDetailReports Report');
        parent::__construct();
        $this->_removeButton('add');
    }
}
