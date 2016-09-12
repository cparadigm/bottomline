<?php
class Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_CustomerName extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        if ($row->getData('customer_firstname') != NULL || $row->getData('customer_lastname') != NULL) {
            $firstName = $row->getData('customer_firstname');
            $lastName = $row->getData('customer_lastname');
            if ($lastName != NULL) {
                return $firstName . ' ' . $lastName;
            } else {
                return $firstName;
            }
        } else {
            return Mage::helper('boardroom_orderdetailreports')->__('NO NAME ASSIGNED');
        }

    }

}