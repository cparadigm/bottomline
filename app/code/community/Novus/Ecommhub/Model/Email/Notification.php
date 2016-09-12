<?php

/* 
  -- Created by Moinul Al-Mamun (moinulkuet@gmail.com)
  -- This code is copyright protected by eCommHub, Inc. 
  -- and any attempts to copy or reproduce this code will be vigorously pursued.
  -- (c) 2009-2013 eCommHub, Inc. All rights reserved.
  */

class Novus_Ecommhub_Model_Email_Notification extends Mage_Core_Model_Resource_Setup {

	/*
	 *  email to eCommHub support 
	 */
	public function newInstallationNotificationEmailToEcommhubSupport()
	{
		//Create an array of variables to assign to template
		$var = array();
		$modules = Mage::getConfig()->getNode('modules')->children();
		$modulesArray = (array) $modules;
		
		$var['ecommhubVersion']	= $modulesArray['Novus_Ecommhub']->version;
		$var['magentoVersion']	= (string) Mage::getVersion();
		$var['SERVER_NAME']		= $_SERVER['SERVER_NAME'];
		$var['SERVER_ADDR']		= $_SERVER['SERVER_ADDR'];
		$var['HTTP_HOST']		= $_SERVER['HTTP_HOST'];
		$var['SERVER_ADMIN']	= $_SERVER['SERVER_ADMIN'];
		$var['SERVER_SIGNATURE']= $_SERVER['SERVER_SIGNATURE'];
		
		// multiple recipients
		//$to  = 'moinulkuet@gmail.com' . ', '; // note the comma
		$to = 'magento@ecommhub.com';
		
		// subject
		$subject = 'eCommHub extension installation notification - '.$var['SERVER_NAME'];
		
		// message
		$message = '		
		<html>
		<head>
		  <title>eCommHub extension installation notification</title>
		</head>
		<body>
		    <br />
		    Find the below server information where eCommHub magento extension has been installed.
			<br />
			<br />
			<table width="600" border="1" cellpadding="1">
			  <tr>
				<th scope="row">eCommHub Version</th>
				<td>'.$var['ecommhubVersion'].'</td>
			  </tr>
			  <tr>
				<th scope="row">Magento Version</th>
				<td>'.$var['magentoVersion'].'</td>
			  </tr>
			  <tr>
				<th scope="row">Server Name</th>
				<td>'.$var['SERVER_NAME'].'</td>
			  </tr>
			  <tr>
				<th scope="row">SERVER ADDR</th>
				<td>'.$var['SERVER_ADDR'].'</td>
			  </tr>
			  <tr>
				<th scope="row">HTTP HOST</th>
				<td>'.$var['HTTP_HOST'].'</td>
			  </tr>
			  <tr>
				<th scope="row">SERVER ADMIN</th>
				<td>'.$var['SERVER_ADMIN'].'</td>
			  </tr>
			  <tr>
				<th scope="row">SERVER SIGNATURE</th>
				<td>'.$var['SERVER_SIGNATURE'].'</td>
			  </tr>
			</table>
		</body>
		</html>';
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Mail it
		mail($to, $subject, $message, $headers);
	}
}
?>
