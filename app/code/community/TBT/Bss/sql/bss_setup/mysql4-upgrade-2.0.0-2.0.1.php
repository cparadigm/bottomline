<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, Holger Brandt IT Solutions not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by Holger Brandt IT Solutions, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time Holger Brandt IT Solutions spent during the support process.
 * Holger Brandt IT Solutions does not guarantee compatibility with any other framework extension. Holger Brandt IT Solutions  is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * info@brandt-solutions.de, so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/


$installer = $this;

$installer->startSetup();

try {
    $installer->run("
        ALTER TABLE {$this->getTable('catalogsearch_fulltext')} DROP  `pns`;
    ");
} catch(Exception $e) { /* ignore and proceed if we couildnt do this operation */ }

try {
    $installer->run("
        ALTER TABLE {$this->getTable('catalogsearch_fulltext')} DROP  `merged_sku`;
    ");
} catch(Exception $e) { /* ignore and proceed if we couildnt do this operation */ }

try {
    $installer->run("
        ALTER TABLE {$this->getTable('catalogsearch_fulltext')} DROP  `merged_name`;
    ");
} catch(Exception $e) { /* ignore and proceed if we couildnt do this operation */ }


$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('bss_index')};
    CREATE TABLE `{$this->getTable('bss_index')}` (
      `product_id` int(10) unsigned NOT NULL,
      `store_id` int(5) unsigned NOT NULL,
      `pns` varchar(256) DEFAULT NULL,
      `merged_sku` varchar(128) DEFAULT NULL,
      `merged_name` varchar(128) DEFAULT NULL,
      PRIMARY KEY (`product_id`,`store_id`)
    ) ENGINE = MYISAM DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC;
");

$installer->endSetup();
