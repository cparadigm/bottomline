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
 * @copyright  Copyright (c) 2013 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
 */
?>
<?php

$installer = $this;

$installer->startSetup();

// we need the default value, because if there's a null value it will mess up with Profiler results
$installer->addColumns($this->getTable('bss_index'), array(
    "`tag` VARCHAR(255) NOT NULL DEFAULT '|'",
    "`categories` VARCHAR(255) NOT NULL DEFAULT '|'",
    "`category_ids` VARCHAR(255) NOT NULL DEFAULT '|'"
));

$installer->attemptQuery("
    ALTER TABLE `{$this->getTable('bss_index')}` ADD FULLTEXT INDEX `BSS_INDEX_TAG_FULLTEXT` (`tag` ASC);
");

$installer->attemptQuery("
    ALTER TABLE `{$this->getTable('bss_index')}` ADD FULLTEXT INDEX `BSS_INDEX_CAT_FULLTEXT` (`categories` ASC);
");

// add manual category weight modifier EAV attribute
$installer->addAttribute('catalog_category', 'bss_cat_weight',  array(
    'type'         => 'int',
    'label'        => 'Search Weight Modifier +/-',
    'input'        => 'text',
    'global'       => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'      => true,
    'required'     => false,
    'user_defined' => false,
    'default'      => null
));
$installer->addAttributeToGroup(
    'catalog_category',
    'bss_cat_weight',
    '12'
);

// require BSS re-indexing
Mage::helper('bss')->invalidateBssIndex();

// clear cache
$installer->prepareForDb();

$installer->endSetup();
