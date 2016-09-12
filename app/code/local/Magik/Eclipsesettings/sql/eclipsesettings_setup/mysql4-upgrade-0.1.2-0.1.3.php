<?php


$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_category', 'eclipse_menu_type', array(
    'group'                => 'Eclipse Menu Settings',
    'label'                => 'Top Menu Image Position',
    'note'                 => 'This field is for only top-level categories and Mega Menu Type.',
    'type'                 => 'varchar',
    'input'                => 'select',
    'source'               => 'eclipsesettings/category_attribute_source_menutype',
    'visible'              => true,
    'required'             => false,
    'backend'              => '',
    'frontend'             => '',
    'searchable'           => false,
    'filterable'           => false,
    'comparable'           => false,
    'user_defined'         => true,
    'visible_on_front'     => true,
    'wysiwyg_enabled'      => false,
    'is_html_allowed_on_front'    => false,
    'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->endSetup();

