<?php 
class Magik_Maintenance_Model_Observer {

  public function initControllerRouters($request) {
      $enableExt= Mage::app()->getStore()->getConfig('mainmodesec/general/enabled');

      if($enableExt==1){
	  $adminFrontName = Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
	  $area = Mage::app()->getRequest()->getOriginalPathInfo();
	  $page=Mage::app()->getStore()->getConfig('mainmodesec/general/templateview');
	  
	 if ((!preg_match('/' . $adminFrontName . '/', $area)) && Mage::app()->getRequest()->getBaseUrl() != "/downloader") {

		$storeId = Mage::app()->getStore()->getStoreId();
		$allowedIPs = Mage::getStoreConfig('mainmodesec/general/allowedIPs', $storeId);
		$allowAdmin=Mage::getStoreConfig('mainmodesec/general/allowforadmin', $storeId);
		$allowedIPs = preg_replace('/ /', '', $allowedIPs);
		$IPs = array();
		  
		if ('' !== trim($allowedIPs)) {
		      $IPs = explode(',', $allowedIPs);
		}
		$currentIP = $_SERVER['REMOTE_ADDR'];

		if (!in_array($currentIP, $IPs)) {
			  $templatename='magik/maintenance/'.$page.'.phtml';
		      $html = Mage::getSingleton('core/layout')->createBlock('core/template')->setTemplate($templatename)->toHtml();

		      if ('' !== $html) {
			      Mage::getSingleton('core/session', array('name' => 'front'));
			      $response = $request->getEvent()->getFront()->getResponse();
			      $response->setHeader('HTTP/1.1', '503 Service Temporarily Unavailable');
			      $response->setHeader('Status', '503 Service Temporarily Unavailable');
			      $response->setHeader('Retry-After', '5000');
			      $response->setBody($html);
			      $response->sendHeaders();
			      $response->outputBody();
		      }
			  exit();
		} 
	  }
     
      }//enable extension
  }
} 
