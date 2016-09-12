<?php

 /**
 * WebShopApps Shipping Module
 *
 * @category    WebShopApps
 * @package     WebShopApps_Wsauspsv2
 * User         Joshua Stewart
 * Date         24/07/2013
 * Time         12:24
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license     http://www.WebShopApps.com/license/license.txt - Commercial license
 *
 */
class Webshopapps_Wsauspsv2_Model_Shipping_Carrier_Usps_Source_Method
{
    public function toOptionArray()
    {
        $usps = Mage::getSingleton('wsauspsv2/shipping_carrier_usps');
        $arr = array();
        foreach ($usps->getCode('method') as $v) {
            $arr[] = array('value'=>$v, 'label'=>$v);
        }
        return $arr;
    }
}