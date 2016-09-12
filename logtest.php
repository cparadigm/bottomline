<?php
require_once 'app/Mage.php';

    Mage::app('default');

    ini_set("log_errors", 1);
    ini_set("error_log", "/var/www/html/brmage/var/log/order-3.log");
    error_log( "logtest transaction begins" );


?>