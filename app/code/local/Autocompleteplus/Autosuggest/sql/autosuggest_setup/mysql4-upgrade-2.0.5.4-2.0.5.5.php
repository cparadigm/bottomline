<?php
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('autocompleteplus_autosuggest/pusher'))

    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'identity' => true,

        'unsigned' => true,

        'nullable' => false,

        'primary' => true

    ), 'Id')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'nullable' => false,

        'unsigned' => true

    ))

    ->addColumn('to_send', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'nullable' => false,

        'unsigned' => true

    ), 'Amount left to send')

    ->addColumn('offset', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'nullable' => false,

        'unsigned' => true

    ))

    ->addColumn('total_batches', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'nullable' => false,

        'unsigned' => true

    ))

    ->addColumn('batch_number', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'nullable' => false,

        'unsigned' => true

    ))

    ->addColumn('sent', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(

        'nullable' => false,

        'unsigned' => true

    ));

/*  TODO:
 * src: http://inchoo.net/magento/delete-test-orders-in-magento/
 * if ($executionPath == 'old') {
        $isTableExists = $connection->showTableStatus($table);
    } else {
        $isTableExists = $connection->isTableExists($table);
    }
 */

if ($installer->getConnection()->isTableExists($table->getName())) {

    $installer->getConnection()->dropTable($table->getName());

}



$installer->getConnection()->createTable($table);

$installer->endSetup();

