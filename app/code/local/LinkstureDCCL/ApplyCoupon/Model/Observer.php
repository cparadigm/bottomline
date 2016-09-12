<?php
class LinkstureDCCL_ApplyCoupon_Model_Observer 
{
	public function applyCouponEvent($observer)
	{
		if (Mage::getStoreConfig('applycoupon_section/applycoupon_group/active')) {
			$coupon_code = trim(Mage::getSingleton("checkout/session")->getData("coupon_code"));
			if ($coupon_code != '')
			{
				Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode($coupon_code)->save();
			}
		}
	}

	public function addCouponEvent($observer)
	{
		if (Mage::getStoreConfig('applycoupon_section/applycoupon_group/active')) {
			$rule = $observer->getEvent()->getRule();
			$id=$rule->getRuleId();
			$rulename = $rule->getName();
			$couponcode = $rule->getCouponCode();
			$website_ids = $rule->getWebsiteIds();
			$status = $rule->getIsActive();

			$applycoupon = Mage::getModel('applycoupon/applycoupon');
			$collections = $applycoupon->getCollection();
			$nid=array();
			$i = 0;
			
			foreach ($collections as $collection) {
				if ($collection->getSid() == $id) {
					$nid[$i] = $collection->getId();
					$websites[$i] = $collection->getWebsites();
					$i += 1;
				}
			}
			if (count($website_ids) == count($nid)) {
				for ($i=0; $i < count($nid); $i++) { 
					$applycoupon->setData(array('id' => $nid[$i],'rule_name' => $rulename,'coupon_code' => $couponcode,'websites' => $website_ids[$i], 'status' => $status,'sid' => $id));
					$applycoupon->save();
				}
			}elseif (count($website_ids) > count($nid)) {
				for ($i=0; $i < count($website_ids); $i++) { 
					if (!in_array($website_ids[$i], $websites)) {
						$base_url = Mage::app()->getWebsite($website_ids[$i])->getDefaultStore()->getBaseUrl();
						$link_without_redirection  = $base_url.'applycoupon/index/?code='.$couponcode.'&return_url=no';
						$applycoupon->setData(array('sid' => $id,'rule_name' => $rulename, 'coupon_code' => $couponcode,'websites' => $website_ids[$i], 'link_without_redirection' => $link_without_redirection, 'status' => $status));
						$applycoupon->save();
					}
					else{
							$myids = $collections->addFieldToSelect('id')
									->addFieldToFilter('id',$nid)
									->addFieldToFilter('websites',$website_ids[$i]);
							$array_id = $myids->getData();
							$myid= $array_id[0]['id'];
							$applycoupon->setData(array('id' => $myid,'rule_name' => $rulename,'coupon_code' => $couponcode,'websites' => $website_ids[$i],'status' => $status,'sid' => $id,));
							$applycoupon->save();
						}
					
				}
			}elseif (count($website_ids) < count($nid)) {
				echo "delete";
				for ($i=0; $i < count($websites); $i++) { 
					 if (!in_array($websites[$i], $website_ids)) {
						if($nid[$i] > 0){
				    		$applycoupon->load($nid[$i]);
				    		$applycoupon->delete();
						}
					}else{
							
								$myids = $collections->addFieldToSelect('id')
									->addFieldToFilter('id',$nid)
									->addFieldToFilter('websites',$website_ids);
								$array_id = $myids->getData();
								$myid= $array_id[0]['id'];
								$applycoupon->setData(array('id' => $myid,'rule_name' => $rulename,'coupon_code' => $couponcode,'websites' => $website_ids[$i],'status' => $status,'sid' => $id,));
								$applycoupon->save();
						}
				}
			}
		}
	}
	public function deleteCouponEvent($observer)
	{
		if (Mage::getStoreConfig('applycoupon_section/applycoupon_group/active')) {
			$sid = Mage::app()->getRequest()->getParam('id');
			$obj = Mage::getModel('applycoupon/applycoupon');
			$collections = $obj->getCollection();
			$i = 0;
			foreach ($collections as $collection) {
					if ($collection->getSid() == $sid) {
	    				$id[$i]=$collection->getId();
	    				$i += 1;
					}
	    		}
	    		for ($i=0; $i < count($id); $i++) { 
	    			if($id[$i] > 0){
			    		$obj->load($id[$i]);
				   		$obj->delete();
					}
	    		}
	    }
	}
}
