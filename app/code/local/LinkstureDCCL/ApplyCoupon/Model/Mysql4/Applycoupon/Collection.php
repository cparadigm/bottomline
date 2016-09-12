<?php
class LinkstureDCCL_ApplyCoupon_Model_Mysql4_ApplyCoupon_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     public function _construct()
     {

	     parent::_construct();

	     $this->_init('applycoupon/applycoupon');
	    
     }
}  
?>