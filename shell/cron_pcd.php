<?php

require_once '../app/Mage.php';
Mage::app();

$pcd = Mage::helper('boardroom_pcd/pcd');
$orders = Mage::getModel('sales/order')
    ->getCollection()
    ->addFieldToFilter('is_pcd',1)
    ->addFieldToFilter('pcd_processed',0);

foreach ($orders as $order) {
    $items = $order->getAllVisibleItems();
    foreach ($items as $item) {
        if ($item->getIsPcd()) {
            $pcd->processPcdItem($order, $item);
        }
    }
}
