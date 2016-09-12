<?php

/* 
  -- Created by Moinul Al-Mamun (moinulkuet@gmail.com)
  -- This code is copyright protected by eCommHub, Inc. 
  -- and any attempts to copy or reproduce this code will be vigorously pursued.
  -- (c) 2009-2013 eCommHub, Inc. All rights reserved.
  */

class Novus_Ecommhub_Block_Information extends Mage_Core_Block_Template
{

  public function apiUserId(){
  
	$user = Mage::getModel("api/user")
			->loadByUsername('eCommHub');
	
	return $user->getId();
  }
  
  public function oneClickEcommhubUrl(){
  	
	$user = Mage::getModel("admin/session")->getUser();
	
	$webUrl = Mage::getStoreConfig('web/unsecure/base_url');
	$companyName = Mage::getStoreConfig('general/store_information/name');
	$phoneNumber = Mage::getStoreConfig('general/store_information/phone');
	$customerEmail = $user->getEmail();
	$firstName = $user->getFirstname();
	$lastName = $user->getLastname();
	
	$oneClickEcommhuburl = "https://ecommhub.com/magento/signup?";
	
	$oneClickEcommhuburl .= "company=".$companyName;
	$oneClickEcommhuburl .= "&email=".$customerEmail;
	$oneClickEcommhuburl .= "&first_name=".$firstName;
	$oneClickEcommhuburl .= "&last_name=".$lastName;
	$oneClickEcommhuburl .= "&phone=".$phoneNumber;
	$oneClickEcommhuburl .= "&website_url=".$webUrl;
	//$oneClickEcommhuburl .= "&secret=";
	
	return $oneClickEcommhuburl;
	
	/*public function urlEncode($url)
    {
        return strtr(base64_encode($url), '+/=', '-_,');
    }
	
	public function urlDecode($url)
    {
        $url = base64_decode(strtr($url, '-_,', '+/='));
        return Mage::getSingleton('core/url')->sessionUrlVar($url);
    }*/
	
  }	
  
  public function apikey(){
  
	$user = Mage::getModel("api/user")
			->loadByUsername('eCommHub');
	
	//echo $apiKey = $user->getApiKey();
	$enc = Mage::helper('core')->encrypt('123456');
	echo '<br />'.$enc;
	echo '<br />'.Mage::helper('core')->decrypt($enc);
	exit;
	//return Mage::helper('core')->decrypt('0de3270a99470ce8d4cd0bef595dda87');
	
	//return base64_decode('0de3270a99470ce8d4cd0bef595dda87');
	//exit;
	
	
	 //return str_replace("\x0", '', trim(Mage::getModel("core/encryption")->decrypt(base64_decode((string)$apiKey))));
	 
	//exit;
	
	//Mage::getModel("api/user")->_getEncodedApiKey($user->getApiKey());
	
	//echo '<pre>';
	//print_r($user);
	//exit;
	
	//return '1234567890123';
  
  }

}
?>