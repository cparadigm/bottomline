<?php
class Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_Items extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $html = '';
        $items = $row->getAllItems();
        if ($items && count($items)>0) {
            $html .= "<table cellspacing='0' class='data'>";
            $html .= "<thead>";
            $html .= "<tr class='headings'>";
            $html .= "<th><span>Name</span></th>";
            $html .= "<th>SKU</th>";
            $html .= "<th>Qty</th>";
            $html .= "<th>Price</th>";
            $html .= "<th>Discount(s)</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            foreach ($items as $item) {
                $discoubuyerquestntHtml = '';
                $discountIds = $item->getAppliedRuleIds();
                if ($discountIds && $discountIds != '') {
                    $discountIds = explode(',', $discountIds);
                    $discountHtml = array();
                    foreach ($discountIds as $discountId) {
                        $coupon = Mage::getModel('salesrule/rule')->load($discountId);
                        $discountHtml[] = $coupon->getName();
                    }
                    $discountHtml = implode("<br>", $discountHtml);
                }
                $html .= "<tr>";
                $html .= "<td style='background-color:#fff;'>".$item->getName()."</td>";
                $html .= "<td style='background-color:#fff;'>".$item->getSku()."</td>";
                $html .= "<td style='background-color:#fff;'>".(int)$item->getQtyOrdered()."</td>";
                $html .= "<td style='background-color:#fff;'>".$this->helper('core')->currency($item->getPrice(), true, false)."</td>";
                $html .= "<td style='background-color:#fff;'>".$discountHtml."</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }
        return $html;

    }

}