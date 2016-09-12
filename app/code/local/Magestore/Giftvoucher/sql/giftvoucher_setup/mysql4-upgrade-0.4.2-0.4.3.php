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
    $this->getTable('giftvoucher/template'), 'giftcard_template_id', "int(11) NOT NULL");
$installer->getConnection()->addColumn(
    $this->getTable('giftvoucher/template'), 'giftcard_template_image', "varchar(255) NULL");
$installer->getConnection()->addColumn(
    $this->getTable('giftvoucher'), 'timezone_to_send', "text(100) default NULL");
$installer->getConnection()->addColumn(
    $this->getTable('giftvoucher'), 'day_store', 'datetime NULL');

$installer->endSetup();
