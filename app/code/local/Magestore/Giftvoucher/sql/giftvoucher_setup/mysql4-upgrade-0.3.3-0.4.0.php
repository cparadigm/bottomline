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
    $installer->getTable('giftvoucher'), 'notify_success', "tinyint(1) default '0'");
$installer->getConnection()->addColumn(
    $installer->getTable('giftvoucher'), 'giftcard_custom_image', "tinyint(1) default '0'");
$installer->getConnection()->addColumn(
    $installer->getTable('giftvoucher'), 'giftcard_template_id', "int(11) default '0'");
$installer->getConnection()->addColumn(
    $installer->getTable('giftvoucher'), 'giftcard_template_image', "varchar(255) NULL");
$installer->getConnection()->addColumn(
    $installer->getTable('giftvoucher_product'), 'giftcard_description', "text(500) default NULL");
$installer->getConnection()->addColumn(
    $installer->getTable('giftvoucher_product'), 'actions_serialized', 'mediumtext NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('giftvoucher'), 'actions_serialized', 'mediumtext NULL');

$installer->getConnection()->addColumn(
    $installer->getTable('sales/order'), 'base_giftvoucher_discount_for_shipping', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order'), 'giftvoucher_discount_for_shipping', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order'), 'base_giftcredit_discount_for_shipping', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order'), 'giftcredit_discount_for_shipping', 'decimal(12,4) NULL');
/* add fields for order item */
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order_item'), 'base_gift_voucher_discount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order_item'), 'gift_voucher_discount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order_item'), 'base_use_gift_credit_amount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/order_item'), 'use_gift_credit_amount', 'decimal(12,4) NULL');

/* add fields for invoice item */
$installer->getConnection()->addColumn(
    $installer->getTable('sales/invoice_item'), 'base_gift_voucher_discount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/invoice_item'), 'gift_voucher_discount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/invoice_item'), 'base_use_gift_credit_amount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/invoice_item'), 'use_gift_credit_amount', 'decimal(12,4) NULL');

/* add fields for creditmemo item */
$installer->getConnection()->addColumn(
    $installer->getTable('sales/creditmemo_item'), 'base_gift_voucher_discount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/creditmemo_item'), 'gift_voucher_discount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/creditmemo_item'), 'base_use_gift_credit_amount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/creditmemo_item'), 'use_gift_credit_amount', 'decimal(12,4) NULL');
$installer->getConnection()->addColumn(
    $installer->getTable('sales/creditmemo_item'), 'giftcard_refund_amount', 'decimal(12,4) NULL');
$installer->run("
    
DROP TABLE IF EXISTS {$this->getTable('giftcard_template')};    
CREATE TABLE {$this->getTable('giftcard_template')} (
  `giftcard_template_id` int(11) unsigned NOT NULL auto_increment,
  `template_name` varchar(255) NOT NULL,
  `style_color` varchar(255) NOT NULL,
  `text_color` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `notes` text(500) NULL,
  `images` text NULL,
  `design_pattern` smallint(5),
  `background_img` varchar(255) NULL,
  `status` smallint NOT NULL default 1,  
  PRIMARY KEY (`giftcard_template_id`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('giftvoucher')}
    ADD CONSTRAINT `FK_GIFTVOUCHER_RELATION_TEMPLATE` FOREIGN KEY (`giftcard_template_id`)
    REFERENCES {$this->getTable('giftcard_template')} (`giftcard_template_id`)
        ON DELETE CASCADE;
");


$model = Mage::getModel('giftvoucher/gifttemplate');
//template 1
$data = array();
$data[0]['template_name'] = Mage::helper('giftvoucher')->__('Default Template 1');
$data[0]['style_color'] = '#DC8C71';
$data[0]['text_color'] = '#949392';
$data[0]['caption'] = Mage::helper('giftvoucher')->__('Gift Card');
$data[0]['notes'] = '';
$data[0]['images'] = 'default.png';
$data[0]['background_img'] = 'default.png';
$data[0]['design_pattern'] = Magestore_Giftvoucher_Model_Designpattern::PATTERN_LEFT;
//template 2
$data[1]['template_name'] = Mage::helper('giftvoucher')->__('Default Template 2');
$data[1]['style_color'] = '#FFFFFF';
$data[1]['text_color'] = '#636363';
$data[1]['caption'] = Mage::helper('giftvoucher')->__('Gift Card');
$data[1]['notes'] = '';
$data[1]['images'] = 'default.png';
$data[1]['background_img'] = 'default.png';
$data[1]['design_pattern'] = Magestore_Giftvoucher_Model_Designpattern::PATTERN_TOP;
//template 3
$data[2]['template_name'] = Mage::helper('giftvoucher')->__('Default Template 3');
$data[2]['style_color'] = '#FFFFFF';
$data[2]['text_color'] = '#A9A7A7';
$data[2]['caption'] = Mage::helper('giftvoucher')->__('Gift Card');
$data[2]['notes'] = '';
$data[2]['images'] = 'default.png';
$data[2]['background_img'] = 'default.png';
$data[2]['design_pattern'] = Magestore_Giftvoucher_Model_Designpattern::PATTERN_CENTER;
foreach ($data as $template) {
    $model->setData($template);
    try {
        $model->save();
    } catch (Exception $exc) {
        
    }
}
$setup = new Mage_Eav_Model_Entity_Setup('catalog_setup');
$installer->startSetup();
$setup->removeAttribute('catalog_product', 'gift_amount');
$setup->removeAttribute('catalog_product', 'gift_type');
$setup->removeAttribute('catalog_product', 'show_gift_amount_desc');
$setup->removeAttribute('catalog_product', 'gift_amount_desc');
$setup->removeAttribute('catalog_product', 'giftcard_description');
$setup->removeAttribute('catalog_product', 'gift_value');
$setup->removeAttribute('catalog_product', 'gift_from');
$setup->removeAttribute('catalog_product', 'gift_to');
$setup->removeAttribute('catalog_product', 'gift_dropdown');
$setup->removeAttribute('catalog_product', 'gift_price_type');
$setup->removeAttribute('catalog_product', 'gift_price');
$setup->removeAttribute('catalog_product', 'gift_template_ids');
/**
 * add gift template attribute
 */
$attGiftTemplate = array(
    'group' => 'General',
    'type' => 'varchar',
    'input' => 'multiselect',
    'default' => 1,
    'label' => 'Select Gift Card templates ',
    'backend' => 'eav/entity_attribute_backend_array',
    'frontend' => '',
    'source' => 'giftvoucher/templateoptions',
    'visible' => 1,
    'user_defined' => 1,
    'used_for_price_rules' => 1,
    'position' => 2,
    'unique' => 0,
    'default' => '',
    'sort_order' => 100,
    'apply_to' => array('giftvoucher'),
);
$setup->addAttribute('catalog_product', 'gift_template_ids', $attGiftTemplate);
$attGiftTemplate = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_template_ids'));
$attGiftTemplate->addData(array(
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'is_required' => 1,
    'apply_to' => array('giftvoucher'),
    'is_configurable' => 1,
    'is_searchable' => 0,
    'is_visible_in_advanced_search' => 0,
    'is_comparable' => 0,
    'is_filterable' => 0,
    'is_filterable_in_search' => 1,
    'is_used_for_promo_rules' => 1,
    'is_html_allowed_on_front' => 0,
    'is_visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'used_for_sort_by' => 0,
))->save();
/**
 * add gift type attribute
 */
$attGifttype = array(
    'group' => 'Prices',
    'type' => 'int',
    'input' => 'select',
    'label' => 'Type of Gift Card value',
    'backend' => '',
    'frontend' => '',
    'source' => 'giftvoucher/gifttype',
    'visible' => 1,
    'user_defined' => 1,
    'used_for_price_rules' => 1,
    'position' => 2,
    'unique' => 0,
    'default' => '',
    'sort_order' => 101,
    'apply_to' => array('giftvoucher'),
);
$setup->addAttribute('catalog_product', 'gift_type', $attGifttype);
$giftType = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_type'));
$giftType->addData(array(
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'is_required' => 1,
    'apply_to' => array('giftvoucher'),
    'is_configurable' => 1,
    'is_searchable' => 0,
    'is_visible_in_advanced_search' => 0,
    'is_comparable' => 0,
    'is_filterable' => 0,
    'is_filterable_in_search' => 1,
    'is_used_for_promo_rules' => 1,
    'is_html_allowed_on_front' => 0,
    'is_visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'used_for_sort_by' => 0,
))->save();
/**
 * add gift_value attribute
 */
$attGiftValue = array(
    'group' => 'Prices',
    'type' => 'decimal',
    'input' => 'price',
    'class' => 'validate-number',
    'label' => 'Gift Card value',
    'backend' => '',
    'frontend' => '',
    'source' => '',
    'visible' => 1,
    'user_defined' => 1,
    'used_for_price_rules' => 1,
    'position' => 4,
    'unique' => 0,
    'default' => '',
    'sort_order' => 103,
);
$setup->addAttribute('catalog_product', 'gift_value', $attGiftValue);
$giftValue = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_value'));
$giftValue->addData(array(
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'is_required' => 1,
    'apply_to' => array('giftvoucher'),
    'is_configurable' => 1,
    'is_searchable' => 0,
    'is_visible_in_advanced_search' => 0,
    'is_comparable' => 0,
    'is_filterable' => 0,
    'is_filterable_in_search' => 1,
    'is_used_for_promo_rules' => 1,
    'is_html_allowed_on_front' => 0,
    'is_visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'used_for_sort_by' => 0,
))->save();
/**
 * add gift_price attribute
 */
$attGiftPrice = array(
    'group' => 'Prices',
    'type' => 'text',
    'input' => 'text',
    'label' => 'Gift Card price',
    'backend' => '',
    'frontend' => '',
    'source' => '',
    'visible' => 1,
    'user_defined' => 1,
    'used_for_price_rules' => 1,
    'position' => 8,
    'unique' => 0,
    'default' => '',
    'sort_order' => 105,
    'is_required' => 1,
    'note' => 'Notes: ',
);
$setup->addAttribute('catalog_product', 'gift_price', $attGiftPrice);
$giftPrice = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_price'));
$giftPrice->addData(array(
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'is_required' => 1,
    'apply_to' => array('giftvoucher'),
    'is_configurable' => 1,
    'is_searchable' => 0,
    'is_visible_in_advanced_search' => 0,
    'is_comparable' => 0,
    'is_filterable' => 0,
    'is_filterable_in_search' => 1,
    'is_used_for_promo_rules' => 1,
    'is_html_allowed_on_front' => 0,
    'is_visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'used_for_sort_by' => 0,
))->save();

/* add Gift Card product attribute */
//show description of giftcard
$attr = array(
    'group' => 'Prices',
    'type' => 'int',
    'input' => 'boolean',
    'label' => 'Show description of gift card value',
    'backend' => '',
    'frontend' => '',
    'source' => '',
    'visible' => 1,
    'user_defined' => 1,
    'used_for_price_rules' => 1,
    'position' => 10,
    'unique' => 0,
    'default' => '',
    'sort_order' => 109,
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'is_required' => 0,
    'apply_to' => 'giftvoucher',
    'is_configurable' => 1,
    'is_searchable' => 0,
    'is_visible_in_advanced_search' => 0,
    'is_comparable' => 0,
    'is_filterable' => 0,
    'is_filterable_in_search' => 1,
    'is_used_for_promo_rules' => 1,
    'is_html_allowed_on_front' => 0,
    'is_visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'used_for_sort_by' => 0,
);

/**
 * add gift from,to attribute for gift type range
 */
$attr['type'] = 'decimal';
$attr['input'] = 'price';
$attr['is_required'] = 1;
$attr['label'] = 'Minimum Gift Card value';
$attr['position'] = 4;
$attr['sort_order'] = 102;
$attr['class'] = 'validate-number';
$setup->addAttribute('catalog_product', 'gift_from', $attr);
$attribute = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_from'));
$attribute->addData($attr)->save();
$attr['type'] = 'decimal';
$attr['input'] = 'price';
$attr['label'] = 'Maximum Gift Card value';
$attr['position'] = 5;
$attr['sort_order'] = 103;
$attr['class'] = 'validate-number';
$setup->addAttribute('catalog_product', 'gift_to', $attr);
$attribute = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_to'));
$attribute->addData($attr)->save();
/**
 * add gift value attribute for gift type dropdown
 */
$attr['type'] = 'varchar';
$attr['input'] = 'text';
$attr['label'] = 'Gift Card values';
$attr['position'] = 6;
$attr['sort_order'] = 102;
$attr['backend_type'] = 'text';
$attr['class'] = '';
$attr['note'] = Mage::helper('giftvoucher')->__('Seperated by comma, e.g. 10,20,30');
$setup->addAttribute('catalog_product', 'gift_dropdown', $attr);
$attribute = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_dropdown'));
$attribute->addData($attr)->save();
//gift price type
$attr['type'] = 'int';
$attr['is_required'] = 0;
$attr['input'] = 'select';
$attr['source'] = 'giftvoucher/giftpricetype';
$attr['label'] = 'Type of Gift Card price';
$attr['position'] = 7;
$attr['sort_order'] = 104;
$attr['backend_type'] = 'text';
$attr['note'] = 'Gift Card price is the same as Gift Card value by default.';
$attr['class'] = '';
$setup->addAttribute('catalog_product', 'gift_price_type', $attr);
$attribute = Mage::getModel('catalog/resource_eav_attribute')
    ->load($setup->getAttributeId('catalog_product', 'gift_price_type'));
$attribute->addData($attr)->save();
$installer->endSetup();
