<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-04-29T13:16:36+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/System/Config/Backend/Import/File.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_System_Config_Backend_Import_File extends Mage_Core_Model_Config_Data
{

    public function _afterSave()
    {
        $LocalBasePath = trim(rtrim(Mage::getStoreConfig('orderstatusimport/local_import/path'), '/')) . '/';
        $uploadedFile = $_FILES["groups"]["tmp_name"]["general"]["fields"]["import_file"]["value"];
        $actualFilename = basename($_FILES["groups"]["name"]["general"]["fields"]["import_file"]["value"]);

        if (empty($uploadedFile)) {
            return;
        }

        if (!Mage::getStoreConfigFlag(Xtento_OrderStatusImport_Model_Observer::MODULE_ENABLED) || !Mage::getStoreConfigFlag(Xtento_OrderStatusImport_Model_Observer::CRON_STRING_PATH)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('Import job failed to run. Make sure the module is enabled and that you\'re using a valid license key.'));
            return;
        }

        if (!Mage::getStoreConfigFlag(Xtento_OrderStatusImport_Model_Observer::IMPORT_LOCAL_ENABLED)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('Local directory import is disabled. Please turn it on and create a directory on the Magento server which will only hold the temporary upload files. Example: /var/trackingimport/ in the Magento root directory.'));
            return;
        }

        if (!is_dir($LocalBasePath)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('Local import directory does not exist. Import failed.'));
            return;
        }

        // Check for not allowed file extensions
        $fileInfo = pathinfo($actualFilename);
        if (isset($fileInfo['extension'])) {
            $forbiddenExtensions = array('php', 'phtml', 'htaccess');
            if (in_array(strtolower($fileInfo['extension']), $forbiddenExtensions)) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('It is not allowed to upload files having the .' . $fileInfo['extension'] . ' filename extension. Please rename your file and use another file extension.'));
                return;
            }
        }

        // Check for forbidden folders
        $forbiddenFolders = array(Mage::getBaseDir('base'), Mage::getBaseDir('base') . DS . 'downloader');
        foreach ($forbiddenFolders as $forbiddenFolder) {
            if (realpath($LocalBasePath) == $forbiddenFolder) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('It is not allowed to upload files into the local import directory you have specified. Please change the local import directory to be located in the ./var/ folder for example.'));
                return;
            }
        }

        if (!@copy($uploadedFile, $LocalBasePath . $actualFilename)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('Could not move uploaded file to import directory. Make sure the import directory is writeable (chmod).'));
            return;
        }

        Mage::getModel('orderstatusimport/observer')->importOrderStatusJob(false, $actualFilename);
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('orderstatusimport')->__('Import job executed.'));
    }

}
