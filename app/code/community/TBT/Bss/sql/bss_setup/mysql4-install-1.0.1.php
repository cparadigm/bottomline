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

//@nelkaake Added on Thursday June 17, 2010: IF the attribute does not exist, create it. Sometimes the extension
// might be installed and for some reason mysql4-install-1.9 does not execute.
$installer = $this;

$installer->startSetup();


//@nelkaake Added on Wednesday June 2, 2010: If the model exists, delete it.
$type_model = Mage::getModel('eav/entity_type')->loadByCode('catalog_product');
$model = Mage::getModel('catalog/entity_attribute');
$model->loadByCode($type_model->getId(), 'bss_weight');
if(!$model->getId()) {
    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    $setup->addAttribute('catalog_product', 'bss_weight', array(
        'label'                => 'Search Weight Modifier +/-',
        'type'                => 'int',
        'visible'            => true,
        'required'            => false,
        'position'             => 1,
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

}


$installer->endSetup();