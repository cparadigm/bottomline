<?php
class LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	 public function __construct()
    {
    	parent::__construct();
		$this->_removeButton('add');
        $this->_controller = 'adminhtml_applycoupon';
        $this->_blockGroup = 'applycoupon';
        $this->_headerText = 'Discount Coupon Code Links';
    }

    protected function _toHtml()
    {
        return $this->_getWarningHtml() . parent::_toHtml();
    }

    protected function _getWarningHtml()
    {
            echo '<div>
                <ul class="messages">
                    <li class="notice-msg">
                  
                        <ul>
                            <li>'.Mage::helper('applycoupon')->__('Notes:').'</li>
                            <li>'.Mage::helper('applycoupon')->__('1) Use “Link with redirection” in case of email, newsletter or any promotion.').'</li>
                            <li>'.Mage::helper('applycoupon')->__('2) Use “Link without redirection” in case if you want to show banner in this website like “50% off” and you want customer to stay on same page when user click on it.').'</li>
                            <li>'.Mage::helper('applycoupon')->__('3) Please use Redirection URL with http/https protocol like http://www.domain.com').'</li>
                        </ul>
                    </li>
                </ul>
                </div>';
    }
    
    
}