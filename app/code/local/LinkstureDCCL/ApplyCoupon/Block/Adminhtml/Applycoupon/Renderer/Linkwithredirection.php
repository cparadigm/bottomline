<?php
class LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Applycoupon_Renderer_Linkwithredirection extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		 $value =  $row->getData($this->getColumn()->getIndex());
		 return '<span id="'.strtolower($row->getData('id')).'">'.$value.'</span><a href="javascript:void(0);" style="float:right;" onclick="selectCode('."'".strtolower($row->getData("id"))."'".');">Select & Copy</a>
			    <script type="text/javascript">
			        function selectCode(shortcodeId) {
			            if (document.selection) {
			                var range = document.body.createTextRange();
			                range.moveToElementText(document.getElementById(shortcodeId));
			                range.select();
			            } else if (window.getSelection) {
			                var range = document.createRange();
			                range.selectNode(document.getElementById(shortcodeId));
			                window.getSelection().addRange(range);
			            }
			        }
			    </script>';
	}
 
}
?>