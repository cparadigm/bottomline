<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `" . $this->getTable('xtento_orderexport_profile') . "` CHANGE  `test_id`  `test_id` VARCHAR( 255 ) NOT NULL;
");

$installer->endSetup();