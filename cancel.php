<?php
require_once '/var/www/html/brmage/app/Mage.php';

Mage::init();
$orderIncrementId = $argv[1];
$order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)->save();

?>

