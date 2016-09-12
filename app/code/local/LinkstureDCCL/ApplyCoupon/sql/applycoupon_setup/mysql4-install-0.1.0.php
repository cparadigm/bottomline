<?php 
$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('ltpl_applycoupon')} (
	`id` int(10) NOT NULL auto_increment,
	`rule_name` varchar(100) default NULL,
	`coupon_code` varchar(100) default NULL,
	`websites` int(3) NOT NULL default '0',
	`redirect_url` varchar(255) default NULL,
	`link_with_redirection` varchar(255) default 'First Specify Redirect Url.',
	`link_without_redirection` varchar(255) default 'First Specify Redirect Url.',
	`views` int(10) NOT NULL default '0',
	`status` int(2) NOT NULL default '0',
	`sid` int(10) NOT NULL default '0',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->endSetup();
?>