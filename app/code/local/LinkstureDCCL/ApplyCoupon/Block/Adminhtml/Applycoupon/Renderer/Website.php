<?php
class LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Applycoupon_Renderer_Website extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		$website = Mage::app()->getWebsite($value);
		return $website->getName();
	}
}
?>