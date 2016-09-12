<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-11-20T13:57:30+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Connection/Ftp.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Connection_Ftp extends Xtento_OrderStatusImport_Model_Connection_Abstract
{
    /*
     * Download files from a FTP server
     */

    private function _initConfiguration()
    {
        $this->setData('server', Mage::getStoreConfig('orderstatusimport/ftp_import/server'));
        $this->setData('port', Mage::getStoreConfig('orderstatusimport/ftp_import/port'));
        $this->setData('use_ssl', Mage::getStoreConfigFlag('orderstatusimport/ftp_import/ssl'), false);
        $this->setData('use_pasv', Mage::getStoreConfigFlag('orderstatusimport/ftp_import/pasv_mode'), false);
        $this->setData('username', Mage::getStoreConfig('orderstatusimport/ftp_import/username'));
        $this->setData('password', Mage::helper('core')->decrypt(Mage::getStoreConfig('orderstatusimport/ftp_import/password')));
        $this->setData('timeout', Mage::getStoreConfig('orderstatusimport/ftp_import/timeout'));
        $this->setData('base_path', trim(rtrim(Mage::getStoreConfig('orderstatusimport/ftp_import/path'), '/')) . '/');
        $this->setData('filename_pattern', Mage::getStoreConfig('orderstatusimport/ftp_import/filename_pattern'));
        if (Mage::getStoreConfig('orderstatusimport/ftp_import/archive_folder') == '') {
            $this->setData('archive_folder', '');
        } else {
            $this->setData('archive_folder', trim('/' . trim(Mage::getStoreConfig('orderstatusimport/ftp_import/archive_folder'), '/')) . '/');
        }
        $this->setData('delete_files', Mage::getStoreConfigFlag('orderstatusimport/ftp_import/delete_files'), false);

        if ($this->getData('server') == "" ||
            $this->getData('port') == "" ||
            $this->getData('username') == "" ||
            $this->getData('base_path') == "" ||
            $this->getData('filename_pattern') == ""
        ) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Invalid FTP import configuration: FTP Server, FTP Port, FTP Username, Base Path or Filename Pattern is empty.'));
            return false;
        }
        return true;
    }

    public function initConnection($caller)
    {
        $this->setCaller($caller);

        // Init configuration
        if (!$this->_initConfiguration()) {
            return false;
        }

        $this->preRun();

        if ($this->getData('use_ssl')) {
            if (function_exists('ftp_ssl_connect')) {
                $this->_connection = @ftp_ssl_connect($this->getData('server'), $this->getData('port'), $this->getData('timeout'));
            } else {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('No FTP-SSL functions found. Please compile PHP with SSL support.'));
                return false;
            }
        } else {
            if (function_exists('ftp_connect')) {
                $this->_connection = @ftp_connect($this->getData('server'), $this->getData('port'), $this->getData('timeout'));
            } else {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('No FTP functions found. Please compile PHP with FTP support.'));
                return false;
            }
        }

        if (!$this->_connection) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not connect to FTP server. Please make sure that there is no firewall blocking the outgoing connection to the FTP server and that the timeout is set to a high enough value. If this error keeps occurring, please get in touch with your server hoster / server administrator AND with the server hoster / server administrator of the remote FTP server. A firewall is probably blocking ingoing/outgoing FTP connections.'));
            return false;
        }

        if (!@ftp_login($this->_connection, $this->getData('username'), $this->getData('password'))) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Wrong login for FTP server.'));
            return false;
        }

        if ($this->getData('use_pasv')) {
            // Enable passive mode
            if (!ftp_pasv($this->_connection, true)) {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not enable passive mode for FTP connection. Trying to proceed anyways.'));
            }
        }

        if (!@ftp_chdir($this->_connection, $this->getData('base_path'))) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not change directory on FTP server to import directory. Please make sure the directory exists and that we have rights to read in the directory.'));
            return false;
        }

        return $this;
    }

    public function getFilesToProcess()
    {
        $filesToProcess = array();

        $filelist = ftp_nlist($this->_connection, "");
        if (!$filelist) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not get file listing for import directory. You should try enabling Passive Mode in the modules FTP configuration.'));
            return false;
        }
        foreach ($filelist as $filename) {
            if (!preg_match($this->getData('filename_pattern'), $filename)) {
                continue;
            }
            if (@ftp_chdir($this->_connection, $filename)) {
                // This is a directory.. do not try to download it.
                ftp_chdir($this->_connection, '..');
                continue;
            }
            $tempHandle = fopen('php://temp', 'r+');
            if (@ftp_fget($this->_connection, $tempHandle, $filename, FTP_BINARY, 0)) {
                rewind($tempHandle);
                $buffer = '';
                while (!feof($tempHandle)) {
                    $buffer .= fgets($tempHandle, 4096);
                }
                if (!empty($buffer)) {
                    $filesToProcess[] = array('type' => Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP, 'path' => $this->getData('base_path'), 'filename' => $filename, 'data' => $buffer);
                } else {
                    $this->archiveFiles(array(array('type' => Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP, 'path' => $this->getData('base_path'), 'filename' => $filename)), false, false);
                }
            } else {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not download file "%s" from FTP server. Please make sure we\'ve got rights to read the file. You can also try enabling Passive Mode in the configuration section of the extension.', $filename));
            }
        }

        $this->afterRun($filesToProcess);
        return $filesToProcess;
    }

    public function archiveFiles($filesToProcess, $forceDelete = false, $chDir = true)
    {
        if ($this->_connection) {
            if ($forceDelete) {
                foreach ($filesToProcess as $file) {
                    if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP) {
                        continue;
                    }
                    if (!@ftp_delete($this->_connection, $file['path'] . '/' . $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['filename'] . '" from the FTP import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
            } else if ($this->getData('archive_folder') !== "") {
                if ($chDir) {
                    if (!@ftp_chdir($this->_connection, $this->getData('archive_folder'))) {
                        Mage::throwException(Mage::helper('orderstatusimport')->__('Could not change directory on FTP server to archive directory. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
                foreach ($filesToProcess as $file) {
                    if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP) {
                        continue;
                    }
                    if (!@ftp_rename($this->_connection, $file['path'] . '/' . $file['filename'], $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not move file "' . $file['filename'] . '" to the FTP archive directory located at "' . $this->getData('archive_folder') . '". Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
            } else if ($this->getData('delete_files') == true) {
                foreach ($filesToProcess as $file) {
                    if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP) {
                        continue;
                    }
                    if (!@ftp_delete($this->_connection, $file['path'] . '/' . $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['filename'] . '" from the FTP import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
            }
            //@ftp_quit($this->_connection);
        }
    }
}