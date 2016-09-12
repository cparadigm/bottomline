<?php

$this->startSetup();
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'payment_block_attribute', array(
    'group'         => 'Custom Design',
    'input'         => 'select',
    'type'          => 'varchar',
    'label'         => 'Payment Block',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'option'        => array (
        'value' => array(
            'billmepayment'=>array(0=>'Bill Me Payment'),
            'magentopayment'=>array(0=>'Magento Payment Options'),
            'bothpayment'=>array(0=>'Bill Me and Magento Payment Options')
        )
    ),
));

$this->endSetup();