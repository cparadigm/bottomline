<?php

$installer = $this;

$installer->startSetup();

$i = array();
if(function_exists('Mage::getVersionInfo'))
   $i = Mage::getVersionInfo();
else{
   $i = explode('.',Mage::getVersion());
   $i['major'] = $i[0];
   $i['minor'] = $i[1];
}

try{
 if($i['major'] == 1 && $i['minor'] > 5) 
     $installer->run("ALTER TABLE {$this->getTable('salesrule/coupon')} DROP INDEX `UNQ_SALESRULE_COUPON_CODE`;");
 else
     $installer->run("ALTER TABLE {$this->getTable('salesrule/coupon')} DROP INDEX `UNQ_COUPON_CODE`;");
}catch(Exception $e){}

$installer->endSetup();
