<?php

 /* 
  -- Created by Moinul Al-Mamun (moinulkuet@gmail.com)
  -- This code is copyright protected by eCommHub, Inc. 
  -- and any attempts to copy or reproduce this code will be vigorously pursued.
  -- (c) 2009-2013 eCommHub, Inc. All rights reserved.
  */

class Novus_Ecommhub_Adminhtml_InformationController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction(){
	
		$this->_title($this->__('eCommHub information'));

        $this->loadLayout();
        $this->_setActiveMenu('ecommhub');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('eCommHub'), Mage::helper('adminhtml')->__('eCommHub'));
		$this->loadLayout()->renderLayout();
    }
}

?>