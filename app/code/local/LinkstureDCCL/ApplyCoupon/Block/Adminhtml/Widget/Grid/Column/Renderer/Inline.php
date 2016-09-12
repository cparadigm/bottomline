<?php
class LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Widget_Grid_Column_Renderer_Inline
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{
    public function render(Varien_Object $row)
    {
       	$html = '<input type="text" ';
        $html .= 'name="' . $this->getColumn()->getId() . '" ';
        $html .= 'value="' . $row->getData($this->getColumn()->getIndex()) . '"';
        $html .= 'style="width:200px"' . $this->getColumn()->getInlineCss() . '" onblur="updateField(this, '. $row->getId() .'); return false"/>';
        return $html;
    }
}