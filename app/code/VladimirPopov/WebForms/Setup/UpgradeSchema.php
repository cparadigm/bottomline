<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.7.3', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('webforms'),
                'email_customer_sender_name',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'Sender name for customer email'
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.7.4', '<')) {
            $setup->getConnection()->changeColumn(
                $setup->getTable('webforms_results'),
                'approved',
                'approved',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'unsigned' => false,
                    'nullable' => false,
                    'comment' => 'Approved status'
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.7.6', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('webforms'),
                'bcc_admin_email',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'BCC Admin Email'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('webforms'),
                'bcc_customer_email',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'BCC Customer Email'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('webforms'),
                'bcc_approval_email',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'BCC Approval Email'
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.7.7', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('webforms'),
                'accept_url_parameters',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 1,
                    'comment' => 'Accept URL parameters'
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.7.8', '<')) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('webforms_files'))
                ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                ], 'Id')
                ->addColumn('result_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                    'nullable' => false,
                    'unsigned' => true,
                ], 'Result ID')
                ->addColumn('field_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                    'nullable' => false,
                    'unsigned' => true,
                ], 'Field ID')
                ->addColumn('name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, [
                    'nullable' => false
                ], 'File Name')
                ->addColumn('size', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                    'nullable' => true,
                    'unsigned' => true
                ])
                ->addColumn('mime_type', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
                    'nullable' => false
                ], 'Mime Type')
                ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, [
                    'nullable' => false
                ], 'File Path')
                ->addColumn('link_hash', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
                    'nullable' => false
                ], 'Link Hash')
                ->addColumn('created_time', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null, [
                        'nullable' => false
                        ]
                );

            $table->addForeignKey(
                $setup->getFkName('webforms_files', 'result_id', 'webforms_results', 'id'),
                'result_id',
                $setup->getTable('webforms_results'),
                'id');

            $table->addForeignKey(
                $setup->getFkName('webforms_files', 'field', 'webforms_fields', 'id'),
                'field_id',
                $setup->getTable('webforms_fields'),
                'id');

            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}