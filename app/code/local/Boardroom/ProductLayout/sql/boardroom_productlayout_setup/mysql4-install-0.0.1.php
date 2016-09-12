<?php

$installer = $this;
$installer->startSetup();

$installer->addAttribute('catalog_product',  'product_view_type', array(
    'group' => 'General',
    'type' => 'varchar',
    'label' => 'Product View Type',
    'input' => 'select',
    'filterable' => 1,
    'filterable_in_search' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'searchable' => true,
    'visible_in_advanced_search' => false,
    'used_in_product_listing' => true,
    'used_for_sort_by' => false,
    'required'          => false,
    'user_defined'      => true,
    'visible' => '1',
    'is_configurable' => '0',
    'option' => array('values' => array('Book', 'Vitamin', 'Default'))
));

$this->endSetup();