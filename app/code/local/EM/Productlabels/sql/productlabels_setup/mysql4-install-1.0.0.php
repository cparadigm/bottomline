<?php
$pathFile = Mage::getBaseDir('var').DS.'install_productlabels.txt';
if(file_exists($pathFile)){
    echo 'Installing Product Labels extension, please come back in some minutes ...';
    exit;
}
$installer = $this;
$installer->startSetup();
$installer->run("
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS {$this->getTable('productlabels/productlabels_entity_varchar')};
DROP TABLE IF EXISTS {$this->getTable('productlabels/productlabels_entity_text')};
DROP TABLE IF EXISTS {$this->getTable('productlabels/productlabels_entity_int')};
DROP TABLE IF EXISTS {$this->getTable('productlabels/eav_attribute')};
DROP TABLE IF EXISTS {$this->getTable('productlabels/productlabels')};
DROP TABLE IF EXISTS {$this->getTable('productlabels/css_entity_text')};
DROP TABLE IF EXISTS {$this->getTable('productlabels/css')};
");

/* Create table 'productlabels/productlabels' */
$table = $installer->getConnection()
    ->newTable($installer->getTable('productlabels/productlabels'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Entity ID')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Created Date')
	->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Updated Date')		
    ->setComment('Productlabels Entity Table');
$installer->getConnection()->createTable($table);

/* Create table 'productlabels/productlabels_entity_varchar' */
$table = $installer->getConnection()
    ->newTable($installer->getTable(array('productlabels/productlabels', 'varchar')))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Attribute Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Store ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Value')
    ->addIndex(
        $installer->getIdxName(
            array('productlabels/productlabels', 'varchar'),
            array('entity_id', 'attribute_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('entity_id', 'attribute_id', 'store_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'varchar'), array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'varchar'), array('store_id')),
        array('store_id'))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'varchar'), array('entity_id')),
        array('entity_id'))
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'varchar'), 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'varchar'), 'entity_id', 'productlabels/productlabels', 'entity_id'),
        'entity_id', $installer->getTable('productlabels/productlabels'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'varchar'), 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Label Entity Varchar');
$installer->getConnection()->createTable($table);

/* Create table 'productlabels/productlabels_entity_int' */
$table = $installer->getConnection()
    ->newTable($installer->getTable(array('productlabels/productlabels', 'int')))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Attribute Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Store ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Value')
    ->addIndex(
        $installer->getIdxName(
            array('productlabels/productlabels', 'int'),
            array('entity_id', 'attribute_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('entity_id', 'attribute_id', 'store_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'int'), array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'int'), array('store_id')),
        array('store_id'))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'int'), array('entity_id')),
        array('entity_id'))
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'int'), 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'int'), 'entity_id', 'productlabels/productlabels', 'entity_id'),
        'entity_id', $installer->getTable('productlabels/productlabels'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'int'), 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Label Entity Int');
$installer->getConnection()->createTable($table);

/* Create table 'productlabels/productlabels_entity_text' */
$table = $installer->getConnection()
    ->newTable($installer->getTable(array('productlabels/productlabels', 'text')))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Attribute Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Store ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Value')
    ->addIndex(
        $installer->getIdxName(
            array('productlabels/productlabels', 'text'),
            array('entity_id', 'attribute_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('entity_id', 'attribute_id', 'store_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'text'), array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'text'), array('store_id')),
        array('store_id'))
    ->addIndex($installer->getIdxName(array('productlabels/productlabels', 'text'), array('entity_id')),
        array('entity_id'))
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'text'), 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'text'), 'entity_id', 'productlabels/productlabels', 'entity_id'),
        'entity_id', $installer->getTable('productlabels/productlabels'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/productlabels', 'text'), 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Label Entity Text');
$installer->getConnection()->createTable($table);

/**
 * Create table 'productlabels/eav_attribute'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('productlabels/eav_attribute'))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Attribute ID')
    ->addColumn('is_global', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Global')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Visible')
    ->addColumn('is_searchable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Searchable')
    ->addColumn('is_filterable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Filterable')
    ->addColumn('is_comparable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Comparable')
    ->addColumn('is_visible_on_front', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Visible On Front')
    ->addColumn('is_html_allowed_on_front', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is HTML Allowed On Front')
    ->addColumn('is_used_for_price_rules', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Used For Price Rules')
    ->addColumn('is_filterable_in_search', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Filterable In Search')
    ->addColumn('used_in_product_listing', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Used In Product Listing')
    ->addColumn('used_for_sort_by', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Used For Sorting')
    ->addColumn('is_configurable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Configurable')
    ->addColumn('apply_to', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        ), 'Apply To')
    ->addColumn('is_visible_in_advanced_search', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Visible In Advanced Search')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Position')
    ->addColumn('is_wysiwyg_enabled', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is WYSIWYG Enabled')
    ->addColumn('is_used_for_promo_rules', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is Used For Promo Rules')
    ->addIndex($installer->getIdxName('productlabels/eav_attribute', array('used_for_sort_by')),
        array('used_for_sort_by'))
    ->addIndex($installer->getIdxName('productlabels/eav_attribute', array('used_in_product_listing')),
        array('used_in_product_listing'))
    ->addForeignKey($installer->getFkName('productlabels/eav_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Productlabels EAV Attribute Table');
$installer->getConnection()->createTable($table);

/* Create table 'productlabels/css' */
$table = $installer->getConnection()
    ->newTable($installer->getTable('productlabels/css'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Entity ID')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')	
    ->setComment('Productlabels Css Entity Table');
$installer->getConnection()->createTable($table);

/* Create table 'productlabels/css_entity_text' */
$table = $installer->getConnection()
    ->newTable($installer->getTable(array('productlabels/css', 'text')))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Attribute Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Store ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Value')
    ->addIndex(
        $installer->getIdxName(
            array('productlabels/css', 'text'),
            array('entity_id', 'attribute_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('entity_id', 'attribute_id', 'store_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName(array('productlabels/css', 'text'), array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName(array('productlabels/css', 'text'), array('store_id')),
        array('store_id'))
    ->addIndex($installer->getIdxName(array('productlabels/css', 'text'), array('entity_id')),
        array('entity_id'))
    ->addForeignKey(
        $installer->getFkName(array('productlabels/css', 'text'), 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/css', 'text'), 'entity_id', 'productlabels/css', 'entity_id'),
        'entity_id', $installer->getTable('productlabels/css'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('productlabels/css', 'text'), 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Label Entity Text');
$installer->getConnection()->createTable($table);

$installer->installEntities(); 
$installer->endSetup(); 
unlink($pathFile);