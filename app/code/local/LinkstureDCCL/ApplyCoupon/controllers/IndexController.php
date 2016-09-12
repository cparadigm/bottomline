<?php
class LinkstureDCCL_ApplyCoupon_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction() 
	{
		if (Mage::getStoreConfig('applycoupon_section/applycoupon_group/active')) {
			$coupon_code = $this->getRequest()->getParam('code');
			$return_url=$this->getRequest()->getParam('return_url');
			if ($coupon_code != '') 
			{
				$c_available = 0;
				$applycoupon = Mage::getModel('applycoupon/applycoupon');
				$collections = $applycoupon->getCollection();
				$websiteId = Mage::app()->getStore()->getWebsiteId();
				foreach ($collections as $collection) {
					if ($collection->getCouponCode() == $coupon_code && $collection->getWebsites() == $websiteId && $collection->getStatus() == 1) {
						$nid=$collection->getId();
						$views=$collection->getViews();
						$c_available = 1;
					}
				}
				if ($c_available == 1) {
					$views += 1;
					$applycoupon->setData(array('id' => $nid,'views' => $views));
					$applycoupon->save();
				}else{
					$s_rules = Mage::getResourceModel('salesrule/rule_collection')->load();
					foreach ($s_rules as $s_rule) {
						if ($s_rule->getIsActive() == 1 && $s_rule->getCode() == $coupon_code) {
							$c_available = 1;
						}
					}
				}
				if ($c_available == 1) {
						Mage::getSingleton("checkout/session")->setData("coupon_code",$coupon_code);
						Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode($coupon_code)->save();
						Mage::getSingleton('core/session')->setMiscellaneous_Scripts('script');	
						
						if(Mage::getStoreConfig('applycoupon_section/applycoupon_group/popup')) {
							Mage::getSingleton('core/session')->setMyPopup('popup');
						}else{
							$successmsg = Mage::getStoreConfig('applycoupon_section/applycoupon_group/success_message');
							Mage::getSingleton('core/session')->addSuccess($successmsg);
						}
					}else{
						if(Mage::getStoreConfig('applycoupon_section/applycoupon_group/popup')) {
							Mage::getSingleton('core/session')->setMyCPopup('cpopup');
							Mage::getSingleton('core/session')->setMyCouponcode($coupon_code);
						}else{
							Mage::getSingleton('core/session')->setMyCouponcode($coupon_code);
							Mage::getSingleton('core/session')->addError('Coupon code "' . $coupon_code . '" is not valid.');
						}
					}
			}
			else 
			{
				Mage::getSingleton("checkout/session")->setData("coupon_code","");
				$cart = Mage::getSingleton('checkout/cart');
				foreach( Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item ) 
				{
					$cart->removeItem( $item->getId() );
				}
			    $cart->save();
			}
		
			if($return_url == 'no' || $return_url == '' || $return_url == NULL)
			{
				if (isset($_SERVER['HTTP_REFERER']))
				{
					$simple = $_SERVER['HTTP_REFERER']; 
				}
				else
				{
					$simple = '';
				}
				
				 if($simple == ''){
				 	$simple = Mage::getUrl();
				 }
				header('Location: ' . $simple);
				exit();
			}
			else
			{ 
				header("Location: ".$return_url);
				exit();
			}
		}else{
			header("Location: ".Mage::getUrl('errors/404'));
			exit();
		}
		
	}
}