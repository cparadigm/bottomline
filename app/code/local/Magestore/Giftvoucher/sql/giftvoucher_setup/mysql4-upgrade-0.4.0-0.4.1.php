<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Giftvoucher
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'base_gift_voucher_discount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'gift_voucher_discount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'base_use_gift_credit_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'use_gift_credit_amount', 'decimal(12,4) NOT NULL default 0');

$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftvoucher_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftvoucher_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftcredit_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftcredit_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');

$installer->getConnection()->addColumn(
    $this->getTable('sales/order_item'), 'giftvoucher_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order_item'), 'giftvoucher_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order_item'), 'giftcredit_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order_item'), 'giftcredit_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');

$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'giftvoucher_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'giftvoucher_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'giftcredit_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'giftcredit_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');

$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'gift_voucher_discount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'base_gift_voucher_discount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'base_use_gift_credit_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/invoice'), 'use_gift_credit_amount', 'decimal(12,4) NOT NULL default 0');

$installer->getConnection()->addColumn(
    $this->getTable('sales/creditmemo'), 'giftvoucher_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/creditmemo'), 'giftvoucher_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/creditmemo'), 'giftcredit_base_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/creditmemo'), 'giftcredit_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');

$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftvoucher_base_shipping_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftvoucher_shipping_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftcredit_base_shipping_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->getConnection()->addColumn(
    $this->getTable('sales/order'), 'giftcredit_shipping_hidden_tax_amount', 'decimal(12,4) NOT NULL default 0');
$installer->endSetup();

