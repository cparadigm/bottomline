<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('sales_flat_order')}  ADD `pcd_processed` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_pcd`
");

$installer->endSetup();