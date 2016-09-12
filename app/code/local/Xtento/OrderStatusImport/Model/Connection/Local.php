<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-11-20T13:57:37+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Connection/Local.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Connection_Local extends Xtento_OrderStatusImport_Model_Connection_Abstract
{
    /*
     * Get files from a local directory
     */

    private function _initConfiguration()
    {
        $this->setData('base_path', trim(rtrim(Mage::getStoreConfig('orderstatusimport/local_import/path'), '/')) . '/');
        $this->setData('filename_pattern', Mage::getStoreConfig('orderstatusimport/local_import/filename_pattern'));
        if (Mage::getStoreConfig('orderstatusimport/local_import/archive_folder') == '') {
            $this->setData('archive_folder', '');
        } else {
            $this->setData('archive_folder', trim(rtrim(Mage::getStoreConfig('orderstatusimport/local_import/archive_folder'), '/')) . '/');
            if (!$this->getCaller()->_isFrontendMode) {
                // Cronjob import - try to fix the archive folder path.
                $this->_fixBasePath($this->getData('archive_folder'), 'archive_folder');
            }
        }
        $this->setData('delete_files', Mage::getStoreConfigFlag('orderstatusimport/local_import/delete_files'));

        if ($this->getData('manual_import_filename') !== false) {
            $this->setData('file_upload', true);
            $this->setData('uploaded_filename', $this->getData('manual_import_filename'));
        } else {
            $this->setData('file_upload', false);
        }

        if ($this->getData('base_path') == "" || $this->getData('filename_pattern') == "") {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Invalid local import configuration: Local Base Path or Filename Pattern is empty.'));
            return false;
        }

        // Check for forbidden folders
        $forbiddenFolders = array(Mage::getBaseDir('base'), Mage::getBaseDir('base') . DS . 'downloader');
        foreach ($forbiddenFolders as $forbiddenFolder) {
            if (realpath($this->getData('base_path')) == $forbiddenFolder) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('orderstatusimport')->__('It is not allowed to run the local directory import from the directory you have specified. Please change the local import directory to be located in the ./var/ folder for example. Do not use the Magento root directory for example.'));
                return false;
            }
        }

        if (!is_dir($this->getData('base_path'))) {
            if (!$this->getCaller()->_isFrontendMode) {
                // Cronjob import - try to fix the base path.
                if (!$this->_fixBasePath($this->getData('base_path'), 'base_path')) {
                    $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Local import: Base Directory \'%s\' does not exist.', $this->getData('base_path')));
                    return false;
                }
            } else {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Local import: Base Directory \'%s\' does not exist.', $this->getData('base_path')));
                return false;
            }
        }
        return true;
    }

    public function initConnection($caller)
    {
        $this->setData('caller', $caller);
        $this->setData('manual_import_filename', $caller->_manualImportFilename);

        // Init configuration
        if (!$this->_initConfiguration()) {
            return false;
        }

        $this->preRun();

        $this->_connection = @opendir($this->getData('base_path'));
        if (!$this->_connection) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Local import: Could not open local import directory. Please make sure that we have rights to read in the directory.'));
            return false;
        }
        return $this;
    }

    public function getFilesToProcess()
    {
        $filesToProcess = array();

        while (false !== ($filename = readdir($this->_connection))) {
            if ($filename != "." && $filename != ".." && !is_dir($this->getData('base_path') . DS . $filename)) {
                if ($this->getData('file_upload') == true) {
                    if ($filename !== $this->getData('uploaded_filename') && !preg_match('/' . preg_quote($this->getData('uploaded_filename')) . '\.chunk/', $filename)) {
                        // This is a file upload.. we're supposed to process only this file and it's chunk files
                        continue;
                    }
                }
                if ($this->getData('file_upload') == false && !preg_match($this->getData('filename_pattern'), $filename) && !preg_match('/\.chunk\./', $filename)) {
                    continue;
                }
                $fileHandle = fopen($this->getData('base_path') . DS . $filename, "r");
                if ($fileHandle) {
                    $buffer = '';
                    while (!feof($fileHandle)) {
                        $buffer .= fgets($fileHandle, 4096);
                    }
                    if (!empty($buffer)) {
                        $filesToProcess[] = array('type' => Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_LOCAL, 'path' => $this->getData('base_path'), 'filename' => $filename, 'data' => $buffer);
                    } else {
                        $this->archiveFiles(array(array('type' => Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_LOCAL, 'path' => $this->getData('base_path'), 'filename' => $filename)));
                    }
                } else {
                    Mage::throwException(Mage::helper('orderstatusimport')->__('Local import: Could not open and read the file "%s" in the import directory.', $filename));
                }
            }
        }

        $this->afterRun($filesToProcess);
        return $filesToProcess;
    }

    public function archiveFiles($filesToProcess, $forceDelete = false)
    {
        if ($forceDelete) {
            foreach ($filesToProcess as $file) {
                if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_LOCAL) {
                    continue;
                }
                if (!@unlink($file['path'] . $file['filename'])) {
                    $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['path'] . $file['filename'] . '" from the local import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                }
            }
        } else if ($this->getData('archive_folder') !== "") {
            if (!is_dir($this->getData('archive_folder'))) {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Local Archive Directory does not exist. Please make sure the directory exists and that we have rights to read/write in the directory.'));
            } else {
                foreach ($filesToProcess as $file) {
                    if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_LOCAL) {
                        continue;
                    }
                    if ($this->getData('file_upload') == true) {
                        if ($file['filename'] !== $this->getData('uploaded_filename') && !preg_match('/' . preg_quote($this->getData('uploaded_filename')) . '\.chunk/', $file['filename'])) {
                            // This is a file upload.. we're supposed to process only this file and it's chunk files
                            continue;
                        }
                    }
                    if (!@rename($file['path'] . $file['filename'], $this->getData('archive_folder') . $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not move file "' . $file['path'] . $file['filename'] . '" to the local archive directory located at "' . $this->getData('archive_folder') . '". Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
            }
        } else if ($this->getData('delete_files') == true) {
            foreach ($filesToProcess as $file) {
                if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_LOCAL) {
                    continue;
                }
                if ($this->getData('file_upload') == true) {
                    if ($file['filename'] !== $this->getData('uploaded_filename') && !preg_match('/' . preg_quote($this->getData('uploaded_filename')) . '\.chunk/', $file['filename'])) {
                        // This is a file upload.. we're supposed to process only this file and it's chunk files
                        continue;
                    }
                }
                if (!@unlink($file['path'] . $file['filename'])) {
                    $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['path'] . $file['filename'] . '" from the local import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                }
            }
        } else {
            if ($this->getData('file_upload') == true) {
                foreach ($filesToProcess as $file) {
                    if ($file['filename'] == $this->getData('uploaded_filename')) {
                        // This is a file upload.. we're supposed to delete only this file.
                        if (!@unlink($file['path'] . $file['filename'])) {
                            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['path'] . $file['filename'] . '" from the local import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                        }
                    }
                }
            }
        }
    }

    private function _fixBasePath($originalPath, $type = 'base_path')
    {
        /*
        * Cronjob import.. let's try to fix the import directory and replace the dot with the actual Magento root directory.
        * Why? Because if the cronjob is executed using the PHP binary a different working directory (when using a dot (.) in a directory path) could be used.
        * But Magento is able to return the right base path, so let's use it instead of the dot.
        */
        if (substr($originalPath, 0, 2) == './') {
            $this->setData($type, Mage::getBaseDir('base') . '/' . substr($originalPath, 2));
        }
        if (!is_dir($this->getData($type))) {
            return false;
        } else {
            return true;
        }
    }
}