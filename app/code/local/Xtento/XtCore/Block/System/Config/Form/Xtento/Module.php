<?php

/**
 * Product:       Xtento_XtCore (1.0.0)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-10-24T11:47:54+02:00
 * File:          app/code/local/Xtento/XtCore/Block/System/Config/Form/Xtento/Module.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Block_System_Config_Form_Xtento_Module extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected function _getHeaderHtml($element)
    {
        $headerHtml = parent::_getHeaderHtml($element);
        if ($this->getGroup() && @current($this->getGroup()->data_model) !== false) {
            // Set up cache, using the Magento cache doesn't make sense as it won't cache if cache is disabled
            try {
                $cacheBackend = new Zend_Cache_Backend();
                $cache = Zend_Cache::factory('Core', 'File', array('lifetime' => 43200), array('cache_dir' => $cacheBackend->getTmpDir()));
            } catch (Exception $e) {
                return $headerHtml;
            }
            // Get data model
            $dataModelName = @current($this->getGroup()->data_model);
            $cacheKey = 'info_' . @current(explode("/", $dataModelName));
            if (@current($this->getGroup()->module_name) !== false) {
                $moduleVersion = (string)@Mage::getConfig()->getNode()->modules->{current($this->getGroup()->module_name)}->version;
                if (!empty($moduleVersion)) {
                    $cacheKey .= '_' . str_replace('.', '_', $moduleVersion);
                }
            }
            // Is the response cached?
            $cachedHtml = $cache->load($cacheKey);
            #$cachedHtml = false; // Test: disable cache
            if ($cachedHtml !== false && $cachedHtml !== '') {
                $storeHtml = $cachedHtml;
            } else {
                try {
                    $dataModel = Mage::getSingleton($dataModelName);
                    $dataModel->afterLoad();
                    // Fetch info whether updates for the module are available
                    $client = new Zend_Http_Client('ht' . 'tp://w' . 'ww.' . 'xte' . 'nto.' . 'co' . 'm/li' . 'cense/info/', array('timeout' => 10));
                    $client->setParameterGet('version', Mage::getVersion());
                    $client->setParameterGet('d', $dataModel->getValue());
                    $response = $client->request('GET');
                    $storeHtml = $response->getBody();
                    $cache->save($storeHtml, $cacheKey);
                } catch (Exception $e) {
                    return '------------------------------------------------<div style="display:none">Exception: ' . $e->getMessage() . '</div>' . $headerHtml;
                }
            }
            if (preg_match('/There has been an error processing your request/', $storeHtml)) {
                return $headerHtml;
            }
            $headerHtml = str_replace('</div><table cellspacing="0" class="form-list">', $storeHtml . '</div><table cellspacing="0" class="form-list">', $headerHtml); // below 1.6
            $headerHtml = str_replace('</span><table cellspacing="0" class="form-list">', $storeHtml . '</span><table cellspacing="0" class="form-list">', $headerHtml); // after 1.7
        }
        return $headerHtml;
    }
}