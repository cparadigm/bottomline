<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('bss_cms_index')}` (
      `page_id` int(10) unsigned NOT NULL,
      `store_id` smallint(6) unsigned NOT NULL,
      `content` text NOT NULL,
      PRIMARY KEY (`page_id`, `store_id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC;
");

$installer->run("
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('bss_cms_result')}` (
      `query_id` int(10) unsigned NOT NULL,
      `page_id` smallint(6) NOT NULL,
      `relevance` decimal(6,4) NOT NULL default '0.0000',
      PRIMARY KEY (`query_id`, `page_id`),
      KEY `IDX_QUERY` (`query_id`),
      KEY `IDX_PAGE` (`page_id`),
      KEY `IDX_RELEVANCE` (`query_id`, `relevance`),
      CONSTRAINT `FK_BSS_CMS_RESULT_QUERY` FOREIGN KEY (`query_id`) REFERENCES `{$installer->getTable('catalogsearch_query')}` (`query_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `FK_BSS_CMS_RESULT_PAGE` FOREIGN KEY (`page_id`) REFERENCES `{$installer->getTable('cms_page')}` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;
");

$connection = $installer->getConnection();
$connection->addColumn($installer->getTable('catalogsearch_query'), 'is_cms_processed', 'tinyint(1) DEFAULT 0 AFTER `is_processed`');

$installer->endSetup();
