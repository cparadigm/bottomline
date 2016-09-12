<?php

$this->startSetup();

try {

	/**
	 * Add product attribute: 'Allow Subscriptions'
	 */
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'allow_autoship', array(
		'group'             => 'General',
		'type'              => 'int',
		'backend'           => '',
		'frontend'          => '',
		'source'            => 'eav/entity_attribute_source_boolean',
		'input'             => 'select',
		'label'             => 'Allow Autoship',
		'class'             => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => true,
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'visible_on_front'  => false,
		'unique'            => false,
		'apply_to'          => null,
		'is_configurable'   => false,
		'default'           => '1'
	));

}
catch( Exception $e ) {
	echo $e->getMessage();
}

$this->endSetup();
