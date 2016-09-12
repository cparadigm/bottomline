<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('sales_flat_order_item')}  ADD `is_pcd` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_subscription`
");
$installer->run("
    ALTER TABLE {$this->getTable('sales_flat_order')}  ADD `is_pcd` TINYINT(1) NOT NULL DEFAULT '0' AFTER `applied_gift_rule_ids`
");

$installer->endSetup();