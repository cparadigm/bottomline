<?php

$installer = $this;

$installer->startSetup();

/**
 * Create table 'slideshow2/slider'
 */
if(!$installer->tableExists($installer->getTable('slideshow2/slider'))){
$table = $installer->getConnection()
    ->newTable($installer->getTable('slideshow2/slider'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Slideshow ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        ), 'Slideshow name')
	->addColumn('images', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'images')
	->addColumn('slider_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
        ), 'Slideshow type')
	->addColumn('slider_params', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'Slideshow params')
	->addColumn('delay', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
        ), 'Slideshow delay')
	->addColumn('touch', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        ), 'Slideshow touch')
	->addColumn('stop_hover', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        ), 'Slideshow stop hover')
	->addColumn('shuffle_mode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        ), 'Slideshow shuffle mode')
	->addColumn('stop_slider', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        ), 'Slideshow stop slider')
	->addColumn('stop_after_loop', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        ), 'Slideshow stop after loop')
	->addColumn('stop_at_slide', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        ), 'Slideshow stop at slide')
	->addColumn('position', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'position')
	->addColumn('appearance', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'appearance')
	->addColumn('navigation', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'navigation')
	->addColumn('thumbnail', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'thumbnail')
	->addColumn('visibility', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'visibility')
	->addColumn('trouble', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'trouble')
    ->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Slideshow Creation Time')
    ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Slideshow Modification Time')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Slideshow Active')
    ->setComment('EM Slideshow2 Slider Table');
$installer->getConnection()->createTable($table);
}

$installer->endSetup(); 