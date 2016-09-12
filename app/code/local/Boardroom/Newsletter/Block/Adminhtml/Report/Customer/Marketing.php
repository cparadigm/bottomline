<?php

class Boardroom_Newsletter_Block_Adminhtml_Report_Customer_Marketing extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'boardroom_newsletter';
        $this->_controller = 'adminhtml_report_customer_marketing';
        $this->_headerText = Mage::helper('boardroom_newsletter')->__('Send Marketing Report');
        parent::__construct();
        $this->_removeButton('add');
    }
}