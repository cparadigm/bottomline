<?php
 
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
$categoryId = 158; //replace with your category id
$newPosition = 1000; //replace with your new position
$category = Mage::getModel('catalog/category')->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->load($categoryId);
$products = $category->getProductsPosition();
foreach ($products as $id=>$value){
    $products[$id] = $newPosition;
}
$category->setPostedProducts($products);
$category->save();