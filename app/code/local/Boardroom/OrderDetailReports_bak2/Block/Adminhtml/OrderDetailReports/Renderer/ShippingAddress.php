<?php
class Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_ShippingAddress extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $shippingAddress = $row->getShippingAddress();
        if ($shippingAddress && $shippingAddress->getId()) {
            $street = $shippingAddress->getStreet();
            if (is_array($street)) {
                $street = trim(implode("\n", $street));
            }
            return $street."<br>".$shippingAddress->getCity().", ".$shippingAddress->getRegion().", ".$shippingAddress->getPostcode();
        } else {
            return Mage::helper('boardroom_orderdetailreports')->__('NO ADDRESS ASSIGNED');
        }

    }

}