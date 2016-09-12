<?php

$this->startSetup();
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'skip_cart', array(
    'group'         => 'Custom Design',
    'input'         => 'select',
    'type'          => 'varchar',
    'label'         => 'Skip Cart Page',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'option'        => array (
        'value' => array(
            'no'=>array(0=>'No'),
            'yes'=>array(0=>'Yes')
        )
    )
));

$this->endSetup();