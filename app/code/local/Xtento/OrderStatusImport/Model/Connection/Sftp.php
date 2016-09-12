<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-07-05T11:47:00+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Connection/Sftp.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Connection_Sftp extends Xtento_OrderStatusImport_Model_Connection_Abstract
{
    /*
     * Download files from a SFTP server
     */

    private function _initConfiguration()
    {
        $this->setData('server', Mage::getStoreConfig('orderstatusimport/ftp_import/server'));
        $this->setData('port', Mage::getStoreConfig('orderstatusimport/ftp_import/port'));
        $this->setData('username', Mage::getStoreConfig('orderstatusimport/ftp_import/username'));
        $this->setData('password', Mage::helper('core')->decrypt(Mage::getStoreConfig('orderstatusimport/ftp_import/password')));
        $this->setData('timeout', Mage::getStoreConfig('orderstatusimport/ftp_import/timeout'));
        $this->setData('base_path', trim(rtrim(Mage::getStoreConfig('orderstatusimport/ftp_import/path'), '/')) . '/');
        $this->setData('filename_pattern', Mage::getStoreConfig('orderstatusimport/ftp_import/filename_pattern'));
        if (Mage::getStoreConfig('orderstatusimport/ftp_import/archive_folder') == '') {
            $this->setData('archive_folder', '');
        } else {
            $this->setData('archive_folder', trim(rtrim(Mage::getStoreConfig('orderstatusimport/ftp_import/archive_folder'), '/')) . '/');
        }
        $this->setData('delete_files', Mage::getStoreConfigFlag('orderstatusimport/ftp_import/delete_files'), false);

        if ($this->getData('server') == "" ||
            $this->getData('port') == "" ||
            $this->getData('username') == "" ||
            $this->getData('base_path') == "" ||
            $this->getData('filename_pattern') == ""
        ) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Invalid SFTP import configuration: FTP Server, FTP Port, FTP Username, Base Path or Filename Pattern is empty.'));
            return false;
        }
        return true;
    }

    public function initConnection($caller)
    {
        set_include_path(get_include_path() . PATH_SEPARATOR . Mage::getBaseDir('lib').DS.'phpseclib');
        if (!@class_exists('Math_BigInteger')) require_once('phpseclib/Math/BigInteger.php');
        if (!@class_exists('Net_SFTP')) require_once('phpseclib/Net/SFTP.php');
        if (!@class_exists('Crypt_RSA')) require_once('phpseclib/Crypt/RSA.php');

        $this->setData('caller', $caller);

        // Init configuration
        if (!$this->_initConfiguration()) {
            return false;
        }

        $this->preRun();

        if (class_exists('Net_SFTP')) {
            $this->_connection = new Net_SFTP($this->getData('server'), $this->getData('port'), $this->getData('timeout'));
        } else {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('No SFTP functions found. The Net_SFTP class is missing.'));
            return false;
        }

        if (!$this->_connection) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not connect to SFTP server. Please make sure that there is no firewall blocking the outgoing connection to the SFTP server and that the timeout is set to a high enough value. If this error keeps occurring, please get in touch with your server hoster / server administrator AND with the server hoster / server administrator of the remote SFTP server. A firewall is probably blocking ingoing/outgoing SFTP connections.'));
            return false;
        }

        // Pub/Private key support - make sure to use adjust the loadKey function with the right key format: http://phpseclib.sourceforge.net/documentation/misc_crypt.html WARNING: Magentos version of phpseclib actually only implements CRYPT_RSA_PRIVATE_FORMAT_PKCS1.
        /*$pk = new Crypt_RSA();
        $pk->setPassword($this->getData('password'));
        #$private_key = file_get_contents('c:\\TEMP\\keys\\coreftp_rsa_no_pw.privkey'); // Or load the private key from a file
        $private_key = <<<KEY
-----BEGIN DSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: DES-EDE3-CBC,F82184195914B351

...................................
-----END DSA PRIVATE KEY-----
KEY;

        if ($pk->loadKey($private_key, CRYPT_RSA_PRIVATE_FORMAT_PKCS1) === false) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not load private key supplied. Make sure it is the PKCS1 format (openSSH) and that the supplied password is right.'));
            return false;
        }*/

        if (!@$this->_connection->login($this->getData('username'), $this->getData('password'))) {
        #if (!@$this->_connection->login($this->getData('username'), $pk)) { // If using key authentication
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Connection to SFTP server failed (make sure no firewall is blocking the connection). This error could also be caused by a wrong login for the SFTP server.'));
            return false;
        }

        if (!@$this->_connection->chdir($this->getData('base_path'))) {
            $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not change directory on SFTP server to import directory. Please make sure the directory exists and that we have rights to read in the directory.'));
            return false;
        }

        return $this;
    }

    public function getFilesToProcess()
    {
        $filesToProcess = array();

        $filelist = @$this->_connection->rawlist();
        //var_dump($filelist, $this->_connection->getSFTPErrors()); die();
        foreach ($filelist as $filename => $fileinfo) {
            //var_dump($filename, $fileinfo);
            if (!preg_match($this->getData('filename_pattern'), $filename)) {
                continue;
            }
            /*if (@$this->_connection->chdir($filename)) {
                // This is a directory.. do not try to download it.
                @$this->_connection->chdir('..');
                continue;
            }*/
            if (!isset($fileinfo['size'])) {
                continue; // This is a directory.
            }
            $fs_filename = $filename;
            if ($buffer = @$this->_connection->get($fs_filename)) {
                if (!empty($buffer)) {
                    $filesToProcess[] = array('type' => Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP, 'path' => $this->getData('base_path'), 'filename' => $filename, 'data' => $buffer);
                } else {
                    $this->archiveFiles(array(array('type' => Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP, 'path' => $this->getData('base_path'), 'filename' => $filename)), false, false);
                }
            } else {
                $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not download file "%s" from SFTP server. Please make sure we\'ve got rights to read the file.', $fs_filename));
            }
        }
        //die();

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
                    if (!@$this->_connection->delete($file['path'] . '/' . $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['filename'] . '" from the SFTP import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
            } else if ($this->getData('archive_folder') !== "") {
                if ($chDir) {
                    if (!@$this->_connection->chdir($this->getData('archive_folder'))) {
                        Mage::throwException(Mage::helper('orderstatusimport')->__('Could not change directory on SFTP server to archive directory. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
                foreach ($filesToProcess as $file) {
                    if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP) {
                        continue;
                    }
                    if (!@$this->_connection->rename($file['path'] . '/' . $file['filename'], $this->getData('archive_folder') . '/' . $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not move file "' . $file['filename'] . '" to the SFTP archive directory located at "' . $this->getData('archive_folder') . '". Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }

            } else if ($this->getData('delete_files') == true) {
                foreach ($filesToProcess as $file) {
                    if ($file['type'] !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_FTP) {
                        continue;
                    }
                    if (!@$this->_connection->delete($file['path'] . '/' . $file['filename'])) {
                        $this->getCaller()->handleWarning(Mage::helper('orderstatusimport')->__('Could not delete file "' . $file['filename'] . '" from the SFTP import directory after processing it. Please make sure the directory exists and that we have rights to read/write in the directory.'));
                    }
                }
            }
            //@$this->_connection->disconnect();
        }
    }
}