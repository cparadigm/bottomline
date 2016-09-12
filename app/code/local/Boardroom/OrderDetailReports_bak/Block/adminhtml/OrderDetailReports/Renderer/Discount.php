<?php
class Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_Discount extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $html = array();
        $discountIds = $row->getAppliedRuleIds();
        if ($discountIds && $discountIds != '') {
            $discountIds = explode(',',$discountIds);
            foreach ($discountIds as $discountId) {
                $coupon = Mage::getModel('salesrule/rule')->load($discountId);
                $html[] = $coupon->getName();
            }
            return implode("<br>",$html);
        }

    }

}