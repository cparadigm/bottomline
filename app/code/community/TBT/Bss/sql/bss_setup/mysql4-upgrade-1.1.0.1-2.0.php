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
try {
    $installer->run("
    ALTER TABLE {$this->getTable('catalogsearch_fulltext')}
        ADD COLUMN (
        `pns` varchar(256) DEFAULT NULL,
        `merged_sku` varchar(128) DEFAULT NULL,
        `merged_name` varchar(128) DEFAULT NULL
        );
    ");
} catch( Exception $e) {
    Mage::helper('bss')->log("An error occured while trying to add the pns/merged_sku/merged_name columns to the catalogsearch_fulltext table.  Please check the exception.log file.");
    Mage::logException($e);
}

$installer->endSetup();