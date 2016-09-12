<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-11-20T13:02:35+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Connection/Custom.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Connection_Custom extends Varien_Object
{
    /*
     * This function gets executed before the FTP / Local connection is established.
     *
     * You can use this to download "virtual" files from a webservice into the local import directory for example.
     */
    public function preRun($config)
    {
        // Configuration
        /*
         * Sample code that would run for every not completed order and fetches data from an API to put it into the local import directory.
         *

        $apiUrl = 'http://mis.apiurl.com/inforequest.asp';

        $orderCollection = Mage::getResourceModel('sales/order_collection')
            ->addAttributeToSelect('increment_id')
            ->addAttributeToFilter('status', array('nin' => array('complete')));

        foreach ($orderCollection as $order) {
            // Code
            $request = <<<EOT
<?xml version="1.0" encoding="iso-8859-1"?>
<INFOREQUEST>
	<TYPE>TRACKING</TYPE>
	<MERCHANTID>XXXX</MERCHANTID>
	<PASSWORD>XXXXXXX</PASSWORD>
	<ORDERNO>{$order->getIncrementId()}</ORDERNO>
</INFOREQUEST>
EOT;
            $curlClient = curl_init();
            curl_setopt($curlClient, CURLOPT_URL, $apiUrl);
            curl_setopt($curlClient, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlClient, CURLOPT_POST, 1);
            curl_setopt($curlClient, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curlClient, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlClient, CURLOPT_SSL_VERIFYHOST, 0);
            $result = curl_exec($curlClient);
            curl_close($curlClient);

            if ($result && !empty($result)) {
                file_put_contents($config->getData('base_path') . $order->getIncrementId() . '.xml', $result);
            }
        }*/

        /* API Example */
        /*
        $client = new Zend_Soap_Client("http://www.api.com/request.php?wsdl"); // Alternatively use SoapClient

        $orderCollection = Mage::getResourceModel('sales/order_collection')
            ->addAttributeToSelect('increment_id')
            ->addAttributeToFilter('status', array('nin' => array('complete')));

        foreach ($orderCollection as $order) {
            $result = $client->get_order_status(array('user' => Mage::getStoreConfig('orderstatusimport/general/api_username'), 'pass' => Mage::helper('core')->decrypt(Mage::getStoreConfig('orderstatusimport/general/api_password')), 'order_id' => $order->getIncrementId()));

            if ($result && !empty($result)) {
                file_put_contents($config->getData('base_path') . $order->getIncrementId() . '.xml', $result);
            }
        }
        */
    }

    /*
     * This function gets executed after the FTP / Local connection has fetched files.
     *
     * You can use this pre-process/parse/change files after downloading them for example.
     */
    public function afterRun($config, $files)
    {
        // $config->getData('base_path')
    }
}