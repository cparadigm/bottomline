<?php

/* 
  -- Created by Moinul Al-Mamun (moinulkuet@gmail.com)
  -- This code is copyright protected by eCommHub, Inc. 
  -- and any attempts to copy or reproduce this code will be vigorously pursued.
  -- © 2009-2013 eCommHub, Inc. All rights reserved.
  */

//exit('I am her ere');

$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

//Mage::getModel("ecommhub/email_notification")->newInstallationNotificationEmailToEcommhubSupport();

// remove eCommHub user if existis
$condition = array($conn->quoteInto('username=?', 'eCommHub'));
$conn->delete($this->getTable('api_user'), $condition);

// remove role ecommhub if existis
$condition = array($conn->quoteInto('role_name=?', 'ecommhub'));
$conn->delete($this->getTable('api_role'), $condition);

// remove role ecommhub if existis
$condition = array($conn->quoteInto('role_name=?', 'eComm'));
$conn->delete($this->getTable('api_role'), $condition);

// create api user "eCommHub" with random api key
$user = Mage::getModel("api/user")
		->setUsername('eCommHub')
		->setFirstname('eComm')
		->setLastname('Hub')
		->setEmail('support@ecommhub.com')
		->setApiKey('eCommHub!@#123')
		->save();

// create api role "ecommhub"
$role = Mage::getModel("api/role");
$role->setRoleName("ecommhub")
	 ->setRoleType('G')
	 ->save();

// rules has been set for ecommhub role. 
// eCommHub can only access ecommhub module related resources/objects
$rule = Mage::getModel("api/rules")
		->setRoleId($role->getId())
		->setResources(array
						 (	"ecommhub", 
							"ecommhub/cataloginventory_stock_item_list", 
							"ecommhub/cataloginventory_stock_item_massupdate",
							"ecommhub/sales_order_list",
							"ecommhub/sales_order_shipment_addtrack",
							"ecommhub/cataloginventory_stock_item_update", 
							"ecommhub/catalog_product_list", 
							"ecommhub/catalog_product_info", 
							"ecommhub/list", 
							"ecommhub/versioninfo",				 
							"catalog",
							"catalog/product",
							"catalog/product/create",
							"catalog/product/info",
							"catalog/product/delete",
							"catalog/product/update",
							"catalog/product/attribute",
							"catalog/product/attribute/set",
							"catalog/product/attribute/set/list",
							"catalog/category",
							"catalog/category/info",
							"catalog/category/tree",
						 
							"sales",
							"sales/order",
							"sales/order/invoice",
							"sales/order/invoice/cancel",
							"sales/order/invoice/info",
							"sales/order/invoice/void",
							"sales/order/invoice/capture",
							"sales/order/invoice/comment",
							"sales/order/invoice/create", 
						 	"sales/order/shipment", 
						 	"sales/order/shipment/send", 
						 	"sales/order/shipment/info", 
						 	"sales/order/shipment/track", 
						 	"sales/order/shipment/comment", 
						 	"sales/order/shipment/create", 
							"sales/order/info",
							"sales/order/change",
						 
							"cataloginventory", 
						 	"cataloginventory/info", 
						 	"cataloginventory/update"
						 )
					)
		->saveRel();

// This role has been imposed to eCommHub user
$user->setRoleId($role->getId())->setUserId($user->getId());
$user->add();

// email to eCommHub support
Mage::getModel("ecommhub/email_notification")->newInstallationNotificationEmailToEcommhubSupport();

$installer->endSetup();

?>
