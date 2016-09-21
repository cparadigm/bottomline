<?php

$installer = $this;

$installer->startSetup();

$hostname = version_compare(phpversion(), '5.3', '>=') ? gethostname() : php_uname('n');
$prefix = md5($hostname);
$token = sha1(uniqid($prefix, true).rand().microtime());
$installer->setConfigData('glew_settings/general/security_token', $token);
$installer->setConfigData('glew_settings/general/shownav', 1);

$installer->endSetup();
