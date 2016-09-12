<?php
class Boardroom_Orderdetailreports_Block_Adminhtml_Orderdetailreports_Renderer_BillingAddress extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $billingAddress = $row->getBillingAddress();
        if ($billingAddress && $billingAddress->getId()) {
            $street = $billingAddress->getStreet();
            if (is_array($street)) {
                $street = trim(implode("\n", $street));
            }
            return $street."<br>".$billingAddress->getCity().", ".$billingAddress->getRegion().", ".$billingAddress->getPostcode();
        } else {
            return Mage::helper('boardroom_orderdetailreports')->__('NO ADDRESS ASSIGNED');
        }

    }

}