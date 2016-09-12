<?php

 /**
 * WebShopApps Shipping Module
 *
 * @category    WebShopApps
 * @package     WebShopApps_USPSV2
 * User         Joshua Stewart
 * Date         24/07/2013
 * Time         12:13
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license     http://www.WebShopApps.com/license/license.txt - Commercial license
 *
 */
class Webshopapps_Wsauspsv2_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Clean service name from unsupported strings and characters
     *
     * Taken from Magento 1.7. Only used in pre 1.5 installs
     *
     * @param  string $name
     * @return string
     */
    public function filterServiceName($name)
    {
        $name = (string)preg_replace(array('~<[^/!][^>]+>.*</[^>]+>~sU', '~\<!--.*--\>~isU', '~<[^>]+>~is'), '', html_entity_decode($name));
        $name = str_replace('*', '', $name);

        return $name;
    }
}