<?php
$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute('customer', 'send_marketing', array(
    'input'         => 'select',
    'source'        => 'eav/entity_attribute_source_boolean',
    'type'          => 'int',
    'label'         => 'Send me marketing emails on new products',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'default'       => 0,
));

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'send_marketing',
    '100'
);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'send_marketing');
$oAttribute->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register','customer-account-login'));
$oAttribute->save();

$setup->endSetup();