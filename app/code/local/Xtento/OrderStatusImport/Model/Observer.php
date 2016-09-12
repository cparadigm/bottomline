<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-09-27T12:47:05+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Observer.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Observer extends Mage_Core_Model_Abstract
{
    protected static $hasRun;
    private $_log;
    private $_doNotify = true;
    private $_errorMessages = array();
    public $cronString = 'orderstatusimport';

    const CRON_ENABLED = 'orderstatusimport/import/cron_enabled';
    const CRON_STRING_PATH = 'crontab/jobs/order_status_import/schedule/cron_expr';
    const MODULE_ENABLED = 'orderstatusimport/general/enabled';
    const IMPORT_FTP_ENABLED = 'orderstatusimport/ftp_import/enabled';
    const IMPORT_LOCAL_ENABLED = 'orderstatusimport/local_import/enabled';

    const SOURCE_TYPE_FTP = 'FTP';
    const SOURCE_TYPE_LOCAL = 'LOCAL';

    const IMPORT_TYPE_XML = 'XML';
    const IMPORT_TYPE_CSV = 'CSV';
    const IMPORT_TYPE_CUSTOM = 'CUSTOM';

    /**
     * Import tracking numbers and other order updates
     */
    public function importOrderStatusJob($schedule = false, $manualImportFilename = false)
    {
        $startTime = microtime(true);
        if (!self::$hasRun) {
            self::$hasRun = 1;
        } else {
            return;
        }
        set_time_limit(0);

        $debugMessages = array('Starting import...');
        if (Mage::getStoreConfigFlag('orderstatusimport/general/test_mode')) {
            $testImport = true;
            $debugMessages[] = 'Test mode enabled. No real data will be imported. This is a tool to preview the import.';
        } else {
            $debugMessages[] = 'Debug mode enabled.';
            $testImport = false;
        }

        if ($schedule !== false) {
            // There's a cron schedule -> cronjob import
            $this->_isFrontendMode = false;
            $executionType = 'Cronjob Import';

            // Save the last cronjob execution
            Mage::getConfig()->saveConfig('orderstatusimport/general/last_cronjob_execution', sprintf("%s", date('c', Mage::getModel('core/date')->timestamp(time()))));

            // If test mode is enabled and this runs as a cronjob, do nothing and output an error
            if ($testImport && Mage::getStoreConfigFlag(self::CRON_ENABLED)) {
                $this->handleWarning('Warning: The import is running in test mode. The cronjob does not work if test mode is enabled. Be sure to disable test mode if you\'re done with testing the import.');
                $this->_notifyErrors();
                return;
            }
        } else {
            // Manual import
            $this->_isFrontendMode = true;
            $executionType = 'Manual Import';
            if ($testImport) {
                $executionType = 'Manual Import (TEST MODE)';
            }
        }

        if (!Mage::getStoreConfigFlag(self::MODULE_ENABLED) || !Mage::getStoreConfigFlag(self::CRON_STRING_PATH)) {
            if ($this->_isFrontendMode) {
                Mage::getSingleton('adminhtml/session')->addError("Fatal Error: This module is disabled. Please enable it first, see 'Module Enabled' below.");
            }
            return;
        }
        if (!Mage::getStoreConfigFlag(self::IMPORT_FTP_ENABLED) && !Mage::getStoreConfigFlag(self::IMPORT_LOCAL_ENABLED)) {
            if ($this->_isFrontendMode) {
                Mage::getSingleton('adminhtml/session')->addError("Fatal Error: Neither the FTP import nor the Local Directory import has been enabled. Make sure you set 'FTP Import enabled' or 'Local Import Enabled' to 'Yes'.");
            }
            return;
        }

        if ($manualImportFilename !== false) {
            // We're supposed to only process the one uploaded file.
            $this->_manualImportFilename = $manualImportFilename;
            $debugMessages[] = 'Processing only the uploaded file \'' . $manualImportFilename . '\'.';
            $fileUploadRun = true;
        } else {
            $this->_manualImportFilename = false;
            $fileUploadRun = false;
        }

        if ($this->_isFrontendMode === false && !Mage::getStoreConfigFlag(self::CRON_ENABLED)) {
            // Cronjob import is disabled. Stop here.
            return;
        }

        try {
            // Error / Info Logger
            $writer = new Zend_Log_Writer_Stream(Mage::getBaseDir('log') . DS . 'order_status_import.log');
            $this->_log = new Zend_Log($writer);
        } catch (Exception $e) {
            $this->handleWarning(sprintf("Logging could not be enabled. Error message: %s", $e->getMessage()));
        }

        try {
            // Files and data to process:
            $filesToProcess = array();

            if (!Mage::helper('orderstatusimport')->getAutoImportEnabled($this)) {
                // Is the import enabled?
                Mage::throwException(Mage::helper('orderstatusimport')->__(str_rot13(Xtento_OrderStatusImport_Model_System_Config_Backend_Import_Cron::AUTOIMPORT_MESSAGE)));
                return;
            }

            // Get files to process, FTP Import
            $ftpConnection = false;
            if (Mage::getStoreConfigFlag(self::IMPORT_FTP_ENABLED) && !$fileUploadRun) {
                if (Mage::getStoreConfigFlag('orderstatusimport/ftp_import/use_sftp')) {
                    // Use SFTP connection
                    $ftpConnection = Mage::getModel('orderstatusimport/connection_sftp')->initConnection($this);
                    $debugMessages[] = 'Loading files from SFTP server.';
                } else {
                    $ftpConnection = Mage::getModel('orderstatusimport/connection_ftp')->initConnection($this);
                    $debugMessages[] = 'Loading files from FTP server.';
                }
                if ($ftpConnection) {
                    $returnedFiles = $ftpConnection->getFilesToProcess();
                    if (!empty($returnedFiles)) {
                        $filesToProcess = array_merge($filesToProcess, $returnedFiles);
                    }
                    $debugMessages[] = count($returnedFiles) . ' file(s) matching the filename pattern have been fetched from the (S)FTP server.';
                }
            }

            // Get files to process, Local Directory Import
            $localConnection = false;
            if (Mage::getStoreConfigFlag(self::IMPORT_LOCAL_ENABLED)) {
                $localConnection = Mage::getModel('orderstatusimport/connection_local')->initConnection($this);
                if (!$fileUploadRun) $debugMessages[] = 'Loading files from local directory.';
                if ($localConnection) {
                    $returnedFiles = $localConnection->getFilesToProcess();
                    if (!empty($returnedFiles)) {
                        $filesToProcess = array_merge($filesToProcess, $returnedFiles);
                    }
                    if (!$fileUploadRun) $debugMessages[] = count($returnedFiles) . ' file(s) matching the filename pattern have been fetched from the local directory.';
                }
            }

            if (Mage::registry('cronString') !== 'false') {
                // Cron has been executed twice?
                exit;
            }

            if (empty($filesToProcess)) {
                if ($testImport || Mage::helper('orderstatusimport')->isDebugEnabled()) {
                    $this->_outputDebugMessages($debugMessages);
                }
                $this->_notifyErrors();
                if ($this->_isFrontendMode) {
                    Mage::getSingleton('adminhtml/session')->addNotice("0 import files have been found on the FTP/SFTP/Local import locations. Make sure the import locations are set up properly and files are ready to be downloaded. Try running a test import in the debug section (again).");
                }
                return;
            }

            // File Processing:
            $updatesInFilesToProcess = array(); // Orders in files to import - results from file processor
            $importMode = Mage::getStoreConfig('orderstatusimport/import/mode');

            if ($importMode == self::IMPORT_TYPE_XML) {
                $updatesInFilesToProcess = Mage::getModel('orderstatusimport/processor_xml')->getRowsToProcess($filesToProcess);
                $debugMessages[] = 'Using XML processor to parse files. (See Import Settings > Import Processor)';
            } else if ($importMode == self::IMPORT_TYPE_CSV) {
                // Shall we split the import file into smaller chunks?
                if (Mage::getStoreConfigFlag('orderstatusimport/processor_csv/chunk_files') && $localConnection) {
                    $filesToProcess = Mage::getModel('orderstatusimport/processor_csv')->chunkFiles($filesToProcess, $localConnection);
                }
                $updatesInFilesToProcess = Mage::getModel('orderstatusimport/processor_csv')->getRowsToProcess($filesToProcess);
                $debugMessages[] = 'Using CSV processor to parse files. (See Import Settings > Import Processor)';
            }

            if (empty($updatesInFilesToProcess)) {
                // Clean up
                if (!$testImport) {
                    if (Mage::getStoreConfigFlag(self::IMPORT_FTP_ENABLED) && $ftpConnection) {
                        $ftpConnection->archiveFiles($filesToProcess);
                    }
                    if (Mage::getStoreConfigFlag(self::IMPORT_LOCAL_ENABLED) && $localConnection) {
                        $localConnection->archiveFiles($filesToProcess);
                    }
                }
                // Debug messages
                if ($testImport || Mage::helper('orderstatusimport')->isDebugEnabled()) {
                    $this->_outputDebugMessages($debugMessages);
                }
                $this->_notifyErrors();
                if ($this->_isFrontendMode) {
                    Mage::getSingleton('adminhtml/session')->addNotice(sprintf("%d files have been parsed, however, they did not contain any valid order updates. Make sure the import processors are set up properly. Try running a test import in the debug section.", count($filesToProcess)));
                    $debugMessages[] = sprintf("Files parsed: <pre>%s</pre>", print_r($filesToProcess, true));
                }
                return; // No updates to import.
            } else {
                $debugMessages[] = sprintf("The following data has been parsed in the imported file(s): <pre>%s</pre>", print_r($updatesInFilesToProcess, true));
            }

            // Process the updates
            if ($testImport) {
                $debugMessages[] = 'Trying to (test-)import the updates...';
            } else {
                $debugMessages[] = 'Trying to import the updates...';
            }
            $totalRecordCount = 0;
            $importedOrderCount = 0;

            foreach ($updatesInFilesToProcess as $updateFile) {
                $path = $updateFile['FILE_INFORMATION']['path'];
                $filename = $updateFile['FILE_INFORMATION']['filename'];
                $type = $updateFile['FILE_INFORMATION']['type'];
                $hasSkuInfo = $updateFile['HAS_SKU_INFO'];

                $updatesToProcess = $updateFile['ORDERS'];

                foreach ($updatesToProcess as $orderNumber => $updateData) {
                    $totalRecordCount++;
                    try {
                        if (empty($orderNumber)) {
                            continue;
                        }

                        // Load order by increment id
                        /** @var $order Mage_Sales_Model_Order */
                        #$orderNumber = preg_replace("/[^0-9]/", "", $orderNumber);
                        $order = Mage::getModel('sales/order')->loadByIncrementId($orderNumber);

                        // If you want to load the order by its entity_id / order_id instead, use:
                        // $order = Mage::getModel('sales/order')->load($orderNumber);
                        // If you want to load the order by its INVOICE increment ID instead, use:
                        /*
                        $invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($orderNumber);
                        if (!$invoice->getId()) {
                            continue;
                        }
                        $order = $invoice->getOrder();
                        */

                        // Data coming from import file
                        $statusFromFile = $updateData['STATUS'];
                        $customData1 = $updateData['CUSTOM_DATA1'];
                        $customData2 = $updateData['CUSTOM_DATA2'];
                        $orderComment = $updateData['ORDER_COMMENT'];

                        // Custom "status" variable modifications: If you want to map status values from the imported file to actual Magento statuses, please do this here. Just use if's and change $status.
                        // Example: if ($statusFromFile == 'completedorder') { $statusFromFile = 'complete'; } // Now a valid Magento status

                        if (!$order->getId()) {
                            if (!$testImport && Mage::helper('orderstatusimport')->isDebugEnabled()) {
                                $this->logToFile("Order #" . $orderNumber . " specified in file '" . $path . DS . $filename . "' from '" . $type . "' could't be found in Magento.");
                            }
                            $debugMessages[] = "Order #" . $orderNumber . " specified in file '" . $path . DS . $filename . "' couldn't be found in Magento.";
                            continue;
                        } else {
                            if ($testImport) {
                                $debugMessages[] = "Order #" . $orderNumber . " was found in Magento and would have been imported.";
                            } else {
                                $debugMessages[] = "Order #" . $orderNumber . " was found in Magento and will now be imported.";
                            }
                        }

                        $importedOrderCount++;

                        if ($testImport) {
                            // We don't want to import any real data.
                            continue;
                        }

                        // Prepare tracking numbers to import
                        $tracksToImport = array();
                        if (isset($updateData['tracks']) && !empty($updateData['tracks'])) {
                            $tracksToImport = $updateData['tracks'];
                        }

                        // Prepare items to process
                        $itemsToProcess = array();
                        if (isset($updateData['items']) && !empty($updateData['items'])) {
                            $itemsToProcess = $updateData['items'];
                        }

                        // Set store and locale, so email templates and locales are used correctly
                        Mage::app()->setCurrentStore($order->getStoreId());
                        Mage::app()->getLocale()->emulate($order->getStoreId());

                        // Create Invoice
                        if (Mage::getStoreConfigFlag('orderstatusimport/import/do_invoice') && $order->canInvoice()) {
                            // Partial invoicing support:
                            $invoice = false;
                            $doInvoiceOrder = true;
                            if (Mage::getStoreConfigFlag('orderstatusimport/import/do_partial_invoices') && $hasSkuInfo) {
                                // Prepare items to invoice for prepareInvoices.. but only if there is SKU info in the import file.
                                $qtys = array();
                                foreach ($order->getAllItems() as $orderItem) {
                                    $orderItemSku = strtolower(trim($orderItem->getSku()));
                                    if (isset($itemsToProcess[$orderItemSku])) {
                                        if ($itemsToProcess[$orderItemSku]['QTY'] == '' || $itemsToProcess[$orderItemSku]['QTY'] < 0) {
                                            $qty = $orderItem->getQtyOrdered();
                                        } else {
                                            #$qty = round($itemsToProcess[$orderItemSku]['QTY']);
                                            $qtyToProcess = $itemsToProcess[$orderItemSku]['QTY'];
                                            $maxQty = $orderItem->getQtyToInvoice();
                                            if ($qtyToProcess > $maxQty) {
                                                $qty = round($maxQty);
                                                $itemsToProcess[$orderItemSku]['QTY'] -= $maxQty;
                                            } else {
                                                $qty = round($qtyToProcess);
                                            }
                                        }
                                        if ($qty > 0) {
                                            $qtys[$orderItem->getId()] = round($qty);
                                        } else {
                                            $qtys[$orderItem->getId()] = 0;
                                        }
                                    } else {
                                        $qtys[$orderItem->getId()] = 0;
                                    }
                                }
                                if (!empty($qtys)) {
                                    $invoice = $order->prepareInvoice($qtys);
                                } else {
                                    // We're supposed to import partial shipments, but no SKUs were found at all. Do not touch invoice.
                                    $doInvoiceOrder = false;
                                }
                            } else {
                                $invoice = $order->prepareInvoice();
                            }

                            if ($invoice && $doInvoiceOrder) {
                                if (Mage::getStoreConfigFlag('orderstatusimport/import/do_capture') && $invoice->canCapture()) {
                                    // Capture order online
                                    $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                                } else if (Mage::getStoreConfigFlag('orderstatusimport/import/set_paid')) {
                                    // Set invoice status to Paid
                                    $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                                }

                                $invoice->register();
                                if (Mage::getStoreConfigFlag('orderstatusimport/import/do_notify_invoice')) {
                                    $invoice->setEmailSent(true);
                                }
                                $invoice->getOrder()->setIsInProcess(true);

                                $transactionSave = Mage::getModel('core/resource_transaction')
                                    ->addObject($invoice)
                                    ->addObject($invoice->getOrder())
                                    ->save();

                                $debugMessages[] = "Order #" . $orderNumber . " has been invoiced.";

                                if (Mage::getStoreConfigFlag('orderstatusimport/import/do_notify_invoice')) {
                                    //$this->mailsToSend['invoices'][$invoice->getId()] = true;
                                    $invoice->sendEmail(true, '');
                                }
                                unset($invoice);
                            }
                        }

                        // Create Shipment
                        $doShipOrder = true;
                        if (!Mage::getStoreConfigFlag('orderstatusimport/import/add_shipments_without_trackingnumbers') && empty($tracksToImport)) {
                            $doShipOrder = false;
                        }
                        if (Mage::getStoreConfigFlag('orderstatusimport/import/do_ship') && $doShipOrder && $order->canShip()) {
                            // Partial shipment support:
                            $shipment = false;
                            if (Mage::getStoreConfigFlag('orderstatusimport/import/do_partial_shipments') && $hasSkuInfo) {
                                // Prepare items to ship for prepareShipment.. but only if there is SKU info in the import file.
                                $qtys = array();
                                foreach ($order->getAllItems() as $orderItem) {
                                    $orderItemSku = strtolower(trim($orderItem->getSku()));
                                    if (isset($itemsToProcess[$orderItemSku])) {
                                        if ($itemsToProcess[$orderItemSku]['QTY'] == '' || $itemsToProcess[$orderItemSku]['QTY'] < 0) {
                                            $qty = $orderItem->getQtyOrdered();
                                        } else {
                                            #$qty = round($itemsToProcess[$orderItemSku]['QTY']);
                                            $qtyToProcess = $itemsToProcess[$orderItemSku]['QTY'];
                                            $maxQty = $orderItem->getQtyToShip();
                                            if ($qtyToProcess > $maxQty) {
                                                $qty = round($maxQty);
                                                $itemsToProcess[$orderItemSku]['QTY'] -= $maxQty;
                                            } else {
                                                $qty = round($qtyToProcess);
                                            }
                                        }
                                        if ($qty > 0) {
                                            $qtys[$orderItem->getId()] = round($qty);
                                        }
                                    }
                                }
                                if (!empty($qtys)) {
                                    $shipment = $order->prepareShipment($qtys);
                                    // Ship whole order if no items could be found in $qtys
                                    if (!$shipment->getTotalQty()) {
                                        $shipment = $order->prepareShipment();
                                    }
                                } else {
                                    // We're supposed to import partial shipments, but no SKUs were found at all. Do not touch shipment.
                                    $doShipOrder = false;
                                }
                            } else {
                                $shipment = $order->prepareShipment();
                            }

                            if ($shipment && $doShipOrder) {
                                $shipment->register();
                                if (Mage::getStoreConfigFlag('orderstatusimport/import/do_notify_ship')) {
                                    $shipment->setEmailSent(true);
                                }
                                $shipment->getOrder()->setIsInProcess(true);

                                $trackCount = 0;
                                foreach ($tracksToImport as $trackingNumber => $trackData) {
                                    $trackCount++;
                                    if (!Mage::getStoreConfigFlag('orderstatusimport/import/add_multiple_trackingnumbers') && $trackCount > 1) {
                                        // Do not import more than one tracking number.
                                        continue;
                                    }
                                    $carrierCode = $trackData['CARRIER_CODE'];
                                    $carrierName = $trackData['CARRIER_NAME'];
                                    if (empty($carrierCode) && !empty($carrierName)) {
                                        $carrierCode = $carrierName;
                                    }
                                    /*if (empty($carrierName) && !empty($carrierCode)) {
                                        $carrierName = $carrierCode;
                                    }*/
                                    if (!empty($trackingNumber)) {
                                        $trackingNumber = str_replace("'", "", $trackingNumber);
                                        $track = Mage::getModel('sales/order_shipment_track')
                                            ->setCarrierCode($this->_determineCarrierCode($carrierCode))
                                            ->setTitle($this->_determineCarrierName($carrierName, $carrierCode));

                                        // Starting with Magento CE 1.6 / EE 1.10 Magento renamed the tracking number attribute to track_number.
                                        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.6.0.0', '>=')) {
                                            $track->setTrackNumber($trackingNumber);
                                        } else {
                                            $track->setNumber($trackingNumber);
                                        }

                                        $shipment->addTrack($track);
                                    }
                                }

                                $transactionSave = Mage::getModel('core/resource_transaction')
                                    ->addObject($shipment)
                                    ->addObject($shipment->getOrder())
                                    ->save();

                                $debugMessages[] = "Order #" . $orderNumber . " has been shipped.";

                                if (Mage::getStoreConfigFlag('orderstatusimport/import/do_notify_ship')) {
                                    //$this->mailsToSend['shipments'][$shipment->getId()] = true;
                                    $shipment->sendEmail(true, $orderComment);
                                }
                                unset($shipment);
                            }
                        }

                        // All items of that order have been shipped but there are more tracking numbers? Try to load the last shipment and still add the tracking number.
                        if (!$order->canShip() && !empty($tracksToImport)) {
                            if (Mage::getStoreConfigFlag('orderstatusimport/import/add_multiple_trackingnumbers')) {
                                // Add a second/third/whatever tracking number to the shipment - if possible.
                                /* @var $shipments Mage_Sales_Model_Mysql4_Order_Shipment_Collection */
                                $shipments = Mage::getResourceModel('sales/order_shipment_collection')
                                    ->setOrderFilter($order)
                                    ->addAttributeToSelect('entity_id')
                                    ->addAttributeToSort('entity_id', 'desc')
                                    ->setPage(1, 1);
                                $lastShipment = $shipments->getFirstItem();
                                if ($lastShipment->getId()) {
                                    $lastShipment = Mage::getModel('sales/order_shipment')->load($lastShipment->getId());

                                    foreach ($tracksToImport as $trackingNumber => $trackData) {
                                        $carrierCode = $trackData['CARRIER_CODE'];
                                        $carrierName = $trackData['CARRIER_NAME'];
                                        if (empty($carrierCode) && !empty($carrierName)) {
                                            $carrierCode = $carrierName;
                                        }
                                        /*if (empty($carrierName) && !empty($carrierCode)) {
                                            $carrierName = $carrierCode;
                                        }*/
                                        $trackAlreadyAdded = false;
                                        foreach ($lastShipment->getAllTracks() as $trackInfo) {
                                            if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.6.0.0', '>=')) {
                                                if ($trackInfo->getTrackNumber() == $trackingNumber) {
                                                    $trackAlreadyAdded = true;
                                                    break;
                                                }
                                            } else {
                                                if ($trackInfo->getNumber() == $trackingNumber) {
                                                    $trackAlreadyAdded = true;
                                                    break;
                                                }
                                            }
                                        }
                                        if (!$trackAlreadyAdded) {
                                            if (!empty($trackingNumber)) {
                                                // Determine carrier and add tracking number
                                                $trackingNumber = str_replace("'", "", $trackingNumber);
                                                $track = Mage::getModel('sales/order_shipment_track')
                                                    ->setCarrierCode($this->_determineCarrierCode($carrierCode))
                                                    ->setTitle($this->_determineCarrierName($carrierName, $carrierCode));

                                                // Starting with Magento CE 1.6 / EE 1.10 Magento renamed the tracking number attribute to track_number.
                                                if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.6.0.0', '>=')) {
                                                    $track->setTrackNumber($trackingNumber);
                                                } else {
                                                    $track->setNumber($trackingNumber);
                                                }

                                                $debugMessages[] = "Order #" . $orderNumber . ": Another tracking number was added for the last shipment (Multi-Tracking).";

                                                $lastShipment->addTrack($track)->save();

                                                if (Mage::getStoreConfigFlag('orderstatusimport/import/do_notify_ship')) {
                                                    // Re-send shipment email when another tracking number was added.
                                                    //$this->mailsToSend['shipments'][$lastShipment->getId()] = true;
                                                    $lastShipment->sendEmail(true, '');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $statusSet = false;
                        if (($order->canInvoice() && Mage::getStoreConfigFlag('orderstatusimport/import/do_invoice')) || ($order->canShip() && Mage::getStoreConfigFlag('orderstatusimport/import/do_ship'))) {
                            // Partially imported order. Let's see if we're supposed to change the order status after importing a partial order.
                            if (Mage::getStoreConfig('orderstatusimport/import/do_change_status_partial_order') !== 'no_change') {
                                // "Change status after import" has been set. This value overrides the file import status.
                                $statusToChangeTo = Mage::getStoreConfig('orderstatusimport/import/do_change_status_partial_order');
                                if ($order->getStatus() !== $statusToChangeTo) {
                                    #$order->setStatus($statusToChangeTo)->save();
                                    $statusSet = $this->_changeOrderStatus($order, $statusToChangeTo, $orderComment);
                                }
                            }
                        } else if (Mage::getStoreConfig('orderstatusimport/import/do_change_status') !== 'no_change') {
                            // "Change status after import" has been set. This value overrides the file import status.
                            $statusToChangeTo = Mage::getStoreConfig('orderstatusimport/import/do_change_status');
                            if ($order->getStatus() !== $statusToChangeTo) {
                                #$order->setStatus($statusToChangeTo)->save();
                                $statusSet = $this->_changeOrderStatus($order, $statusToChangeTo, $orderComment);
                            }
                        } else if (!empty($statusFromFile)) {
                            // Status coming from the imported file is not empty. Then let's set this status.
                            if (!isset($statuses)) {
                                $statuses = Mage::getSingleton('orderstatusimport/system_config_source_order_status')->toArray();
                            }
                            // Make sure the "new" "$status" is a valid Magento status before setting it:
                            if (!isset($statuses) || !in_array($statusFromFile, $statuses)) {
                                $this->_log->warn("Order status '" . $statusFromFile . "' specified in '" . $filename . "' from '" . $type . "' for order #" . $orderNumber . " is no valid Magento status. Status not changed.");
                            } else {
                                if ($order->getStatus() !== $statusFromFile) {
                                    #$order->setStatus($statusFromFile)->save();
                                    $statusSet = $this->_changeOrderStatus($order, $statusFromFile, $orderComment);
                                    // Alternative for Magento Enterprise Edition:
                                    /*
                                       $order->addStatusHistoryComment('', $statusFromFile)
                                         ->setIsVisibleOnFront(0)
                                         ->setIsCustomerNotified(0);
                                       $order->save();
                                     */
                                }
                            }
                        }

                        if (!$statusSet && !empty($orderComment)) {
                            $order->addStatusHistoryComment($orderComment)->save();
                        }

                        unset($order);
                        // Reset locale.
                        Mage::app()->getLocale()->revert();
                        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
                    } catch (Mage_Core_Exception $e) {
                        // Don't break execution, but log the order related error.
                        Mage::app()->getLocale()->revert();
                        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
                        $this->handleWarning("Exception catched for order #" . $orderNumber . " specified in '" . $path . DS . $filename . "' from '" . $type . "':\n" . $e->getMessage());
                        continue;
                    }
                }
                // Move processed file to archive or delete the file
                if (!$testImport || $fileUploadRun) {
                    if (Mage::getStoreConfigFlag(self::IMPORT_FTP_ENABLED) && $ftpConnection) {
                        $ftpConnection->archiveFiles(array($updateFile['FILE_INFORMATION']));
                    }
                    if (Mage::getStoreConfigFlag(self::IMPORT_LOCAL_ENABLED) && $localConnection) {
                        $localConnection->archiveFiles(array($updateFile['FILE_INFORMATION']));
                    }
                    // Remove files already archived from the general archiving process
                    foreach ($filesToProcess as $fileIndex => $fileToProcess) {
                        if ($fileToProcess['type'] == $updateFile['FILE_INFORMATION']['type'] &&
                            $fileToProcess['filename'] == $updateFile['FILE_INFORMATION']['filename']
                        ) {
                            unset($filesToProcess[$fileIndex]);
                        }
                    }
                }
            }

            /*
            * Send E-Mails after importing invoices/shipments/tracking numbers
            *
            * Disabled as not required anymore - emails sent directly after processing each order.
            */
            /*if (isset($this->mailsToSend['invoices']) && !empty($this->mailsToSend['invoices'])) {
                foreach ($this->mailsToSend['invoices'] as $invoiceId => $dummyValue) {
                    $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId);
                    $invoice->sendEmail(true, '');
                    unset($invoice);
                }
            }
            if (isset($this->mailsToSend['shipments']) && !empty($this->mailsToSend['shipments'])) {
                foreach ($this->mailsToSend['shipments'] as $shipmentId => $dummyValue) {
                    $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
                    $shipment->sendEmail(true, '');
                    unset($shipment);
                }
            }*/

            // After processing: Clean up, move all the not processed files to the archive or delete them
            if (!$testImport && !empty($filesToProcess)) {
                if (Mage::getStoreConfigFlag(self::IMPORT_FTP_ENABLED) && $ftpConnection) {
                    $ftpConnection->archiveFiles($filesToProcess);
                }
                if (Mage::getStoreConfigFlag(self::IMPORT_LOCAL_ENABLED) && $localConnection) {
                    $localConnection->archiveFiles($filesToProcess);
                }
            }

            $debugMessages[] = sprintf("Import process took %d seconds.", (microtime(true) - $startTime));

            if ($testImport || Mage::helper('orderstatusimport')->isDebugEnabled()) $this->_outputDebugMessages($debugMessages);

            if ($this->_isFrontendMode) {
                if ($testImport) {
                    Mage::getSingleton('adminhtml/session')->addNotice(sprintf("%d of %d records WOULD have been imported if this wasn't the test mode.", $importedOrderCount, $totalRecordCount));
                } else {
                    Mage::getSingleton('adminhtml/session')->addNotice(sprintf("%d of %d records have been imported. If some records haven't been imported, the supplied order number probably did not exist in your store.", $importedOrderCount, $totalRecordCount));
                }
            }

            Mage::getConfig()->saveConfig('orderstatusimport/general/last_execution', sprintf("%s (%s, %d of %d records imported)", date('c', Mage::getModel('core/date')->timestamp(time())), $executionType, $importedOrderCount, $totalRecordCount));

            $this->_notifyErrors();
            // Done! :)
        } catch (Mage_Core_Exception $e) {
            $this->_outputDebugMessages($debugMessages);
            $this->_handleException($e);
            return;
        } catch (Exception $e) {
            $this->_outputDebugMessages($debugMessages);
            $this->_handleException($e);
            return;
        }
    }

    private function _changeOrderStatus($order, $newOrderStatus, $orderComment)
    {
        if ($order->getStatus() == $newOrderStatus) {
            return false;
        }
        $this->_setOrderState($order, $newOrderStatus);
        $order->setStatus($newOrderStatus)->save();
        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.4.0.0', '>=')) {
            $order->addStatusHistoryComment(!empty($orderComment) ? $orderComment : '', $order->getStatus())->setIsCustomerNotified(0);
        } else {
            // 1.3 compatibility
            $order->addStatusToHistory($order->getStatus());
        }
        // Compatibility fix for Amasty_OrderStatus
        $statusModel = Mage::registry('amorderstatus_history_status');
        if (($statusModel && $statusModel->getNotifyByEmail()) || Mage::registry('advancedorderstatus_notifications')) {
            $order->sendOrderUpdateEmail();
        }
        // End
        $order->save();
        return true;
    }

    private function _setOrderState($order, $newOrderStatus)
    {
        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            if (!isset($this->_orderStates)) {
                $this->_orderStates = Mage::getModel('sales/order_config')->getStates();
            }
            foreach ($this->_orderStates as $state => $label) {
                foreach (Mage::getModel('sales/order_config')->getStateStatuses($state, false) as $status) {
                    if ($status == $newOrderStatus) {
                        $order->setData('state', $state);
                        return;
                    }
                }
            }
        }
    }

    private function _determineCarrierCode($carrierCode = '')
    {
        // In case the XTENTO Custom Carrier Trackers extension is installed, make sure no disabled carriers are used here.
        $disabledCarriers = explode(",", Mage::getStoreConfig('customtrackers/general/disabled_carriers'));
        if (!in_array($carrierCode, $disabledCarriers)) {
            // Try to find the carrier code and see if a title is assigned to it
            $carrierTitle = $this->_getCarrierTitle($carrierCode, true);
            if (!empty($carrierTitle)) {
                // Valid carrier code
                return $carrierCode;
            }
        }

        /*
         * Add your custom tracking method mapping here.
         *
         * Just copy one the if conditions and replace the values with your mapping.
         *
         * The returnedCarrierCode variable must hold a valid carrierCode. Examples are ups, usps, fedex, dhl
         *
         * If you're using the XTENTO Custom Carrier Trackers extension, you can also use your custom trackers. The number relates to the Custom Carrier Trackers configuration. Examples:
         * tracker1, tracker2, tracker3, ... tracker10
         */
        $returnedCarrierCode = 'custom';
        if (preg_match("/UPS/i", $carrierCode)) {
            $returnedCarrierCode = 'ups';
        }
        if (preg_match("/FedEx/i", $carrierCode) || preg_match("/Federal Express/i", $carrierCode)) {
            $returnedCarrierCode = 'fedex';
        }
        if (preg_match("/USPS/i", $carrierCode) || preg_match("/United States Postal Service/i", $carrierCode)) {
            $returnedCarrierCode = 'usps';
        }
        if (in_array($returnedCarrierCode, $disabledCarriers)) {
            $returnedCarrierCode = 'custom';
        }

        // No custom mapping was found
        if ($returnedCarrierCode == 'custom') {
            // Try to get the carrier code by the tracker description
            if (!isset($this->_allCarriers)) {
                $this->_allCarriers = Mage::getModel('shipping/config')->getAllCarriers();
            }
            foreach ($this->_allCarriers as $carrierCodeLoop => $carrierConfig) {
                if (in_array($carrierCodeLoop, $disabledCarriers)) {
                    continue;
                }
                $carrierLoopName = $carrierConfig->getConfigData('name');
                $carrierLoopTitle = $carrierConfig->getConfigData('title');
                if ($carrierConfig->isTrackingAvailable() && (@strpos(strtolower($carrierLoopTitle), strtolower($carrierCode)) !== false || strtolower($carrierLoopTitle) == strtolower($carrierCode) || @strpos(strtolower($carrierLoopName), strtolower($carrierCode)) !== false)) {
                    return $carrierCodeLoop;
                }
            }
        }

        return $returnedCarrierCode;
    }

    private function _determineCarrierName($carrierName, $carrierCode)
    {
        if (empty($carrierName)) {
            $carrierCode = $this->_determineCarrierCode($carrierCode);
            return $this->_getCarrierTitle($carrierCode);
        } else {
            return $carrierName;
        }
    }

    private function _getCarrierTitle($carrierCode, $returnEmpty = false)
    {
        $carrierTitle = Mage::getStoreConfig('carriers/' . $carrierCode . '/title');
        if (empty($carrierTitle)) {
            $carrierTitle = Mage::getStoreConfig('customtrackers/' . $carrierCode . '/title');
        }
        if (!$returnEmpty && empty($carrierTitle)) {
            return $carrierCode;
        }
        return $carrierTitle;
    }

    /*
     * Debug/Error handling functions
     */
    public function handleWarning($errorMessage)
    {
        array_push($this->_errorMessages, $errorMessage);
        return $this;
    }

    private function _handleException($e)
    {
        Mage::app()->getLocale()->revert();
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        array_push($this->_errorMessages, $e->getMessage());
        if ($e->getMessage() == 'SQLSTATE[40001]: Serialization failure: 1213 Deadlock found when trying to get lock; try restarting transaction' ||
            $e->getMessage() == 'SQLSTATE[HY000]: General error: 1205 Lock wait timeout exceeded; try restarting transaction'
        ) {
            $this->_doNotify = false;
        }
        $this->_notifyErrors();
        return $this;
    }

    private function _notifyErrors()
    {
        if (empty($this->_errorMessages)) {
            return false;
        }
        if ($this->_isFrontendMode) {
            foreach ($this->_errorMessages as $errorMessage) {
                Mage::getSingleton('adminhtml/session')->addWarning("Warning: \n" . $errorMessage);
            }
        }

        $errorMessages = implode("\n", $this->_errorMessages);
        if (Mage::helper('orderstatusimport')->isDebugEnabled() && Mage::helper('orderstatusimport')->getDebugEmail() !== '' && $this->_doNotify) {
            mail(Mage::helper('orderstatusimport')->getDebugEmail(), 'Magento Tracking Number Import Module @ ' . @$_SERVER['SERVER_NAME'], 'Tracking Number Import Error/Message: ' . $errorMessages);
        }
        Mage::getConfig()->saveConfig('orderstatusimport/general/last_exception', date('c', Mage::getModel('core/date')->timestamp(time())) . ": Fatal Error: \n" . $errorMessages);
        if (Mage::helper('orderstatusimport')->isDebugEnabled()) {
            $this->logToFile($errorMessages);
        }
    }

    private function _outputDebugMessages($debugMessages)
    {
        if ($this->_isFrontendMode) {
            if (strlen(implode("<br/>", $debugMessages)) > 900000) {
                $logFilename = 'import_' . uniqid() . '.log';
                file_put_contents(Mage::getBaseDir() . DS . 'var' . DS . 'log' . DS . $logFilename, implode("\n", $debugMessages));
                Mage::getSingleton('adminhtml/session')->addNotice('<button id="id_scrolldown" type="button" class="scalable " onclick="$(\'config_edit_form\').scrollTo();"><span>Scroll down to configuration</span></button><br/><br/>The import debug messages are to loo long to be shown here. They were instead saved in the ./var/log/' . $logFilename . ' file.');
            } else {
                Mage::getSingleton('adminhtml/session')->addNotice('<button id="id_scrolldown" type="button" class="scalable " onclick="$(\'config_edit_form\').scrollTo();"><span>Scroll down to configuration</span></button><br/><br/>' . implode("<br/>", $debugMessages));
            }
        }
    }

    private function logToFile($errorMessages)
    {
        if ($this->_log instanceof Zend_Log) {
            $this->_log->warn($errorMessages);
        }
    }

    public function controller_action_predispatch_adminhtml($event)
    {
        // Check if this module was made for the edition (CE/PE/EE) it's being run in
        $controller = $event->getControllerAction();
        if ($controller->getRequest()->getControllerName() == 'system_config' && $controller->getRequest()->getParam('section') == 'orderstatusimport') {
            if (!Mage::registry('edition_warning_shown')) {
                if (Xtento_OrderStatusImport_Helper_Data::EDITION !== 'CE' && Xtento_OrderStatusImport_Helper_Data::EDITION !== '') {
                    if (Mage::helper('xtcore/utils')->getIsPEorEE()) {
                        if (Xtento_OrderStatusImport_Helper_Data::EDITION !== 'EE') {
                            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtcore')->__('Attention: The installed Tracking Number Import Module version is not compatible with the Enterprise Edition of Magento. The compatibility of the currently installed extension version has only been confirmed with the Community Edition of Magento. Please go to <a href="https://www.xtento.com" target="_blank">www.xtento.com</a> to purchase or download the Enterprise Edition of this extension in our store if you\'ve already purchased it.'));
                        }
                    }
                }
                Mage::register('edition_warning_shown', true);
            }
        }
    }
}