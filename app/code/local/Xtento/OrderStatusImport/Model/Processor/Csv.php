<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-10-03T16:15:49+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Processor/Csv.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Processor_Csv
{
    protected $config = array();

    private function _initConfiguration()
    {
        if (!$this->config) {
            # Load configuration:
            $this->config = array(
                'IMPORT_SKIP_HEADER' => Mage::getStoreConfigFlag('orderstatusimport/processor_csv/skip_header'),
                'IMPORT_DELIMITER' => Mage::getStoreConfig('orderstatusimport/processor_csv/delimiter'),
                'IMPORT_ENCLOSURE' => Mage::getStoreConfig('orderstatusimport/processor_csv/enclosure'),
                'IMPORT_SHIPPED_ONE_FIELD' => Mage::getStoreConfigFlag('orderstatusimport/processor_csv/shipped_one_field'),
            );

            # Get mapping model
            $this->mappingModel = Mage::getModel('orderstatusimport/processor_mapping_fields');
            $this->mappingModel->setDataPath('orderstatusimport/processor_csv/import_mapping');

            # Load mapping
            $this->mapping = $this->mappingModel->getMappingConfig();

            if ($this->mapping->getOrderNumber() == null) {
                Mage::throwException('Please configure the CSV processor in the configuration section of the Tracking Number Import Module. The order number index field may not be empty and must be mapped.');
            }
            if ($this->config['IMPORT_DELIMITER'] == '') {
                Mage::throwException('Please configure the CSV processor in the configuration section of the Tracking Number Import Module. The Field Delimiter may not be empty.');
            }
            if ($this->config['IMPORT_ENCLOSURE'] == '') {
                $this->config['IMPORT_ENCLOSURE'] = '"';
            }
            if (strtolower($this->config['IMPORT_DELIMITER']) == 'tab' || $this->config['IMPORT_DELIMITER'] == '\t' || $this->config['IMPORT_DELIMITER'] == chr(9)) {
                $this->config['IMPORT_DELIMITER'] = "\t";
            }
            if (strtolower($this->config['IMPORT_DELIMITER']) == 'flf') {
                $this->config['IMPORT_FIXED_LENGTH_FORMAT'] = true;
            } else {
                $this->config['IMPORT_FIXED_LENGTH_FORMAT'] = false;
            }
        }
    }

    public function getRowsToProcess($filesToProcess)
    {
        @ini_set('auto_detect_line_endings', 1);

        # Logger for this processor
        //$writer = new Zend_Log_Writer_Stream(Mage::getBaseDir('log') . DS . 'order_status_import_processor_csv.log');
        //$log = new Zend_Log($writer);

        # Updates to process, later the result
        $updatesInFilesToProcess = array();

        $this->_initConfiguration();

        foreach ($filesToProcess as $importFile) {
            $data = $importFile['data'];
            $filename = $importFile['filename'];
            $type = $importFile['type'];
            unset($importFile['data']);

            // Remove UTF8 BOM
            $bom = pack('H*', 'EFBBBF');
            $data = preg_replace("/^$bom/", '', $data);

            $updatesToProcess = array();
            $rowCounter = 0;

            if ($this->config['IMPORT_FIXED_LENGTH_FORMAT']) {
                // Fixed length format
                foreach (explode("\n", $data) as $line) {
                    $rowCounter++;
                    $this->rowData = $line;
                    if ($rowCounter == 1) {
                        // Skip the header
                        if ($this->config['IMPORT_SKIP_HEADER'] == true) {
                            continue;
                        }
                    }

                    $orderNumber = $this->getFieldData('order_number');
                    if (empty($orderNumber)) {
                        continue;
                    }

                    $carrierCode = $this->getFieldData('carrier_code');
                    $carrierName = $this->getFieldData('carrier_name');
                    $trackingNumber = $this->getFieldData('tracking_number');
                    $status = $this->getFieldData('order_status');
                    $orderComment = $this->getFieldData('order_status_history_comment');
                    $skuToShip = strtolower($this->getFieldData('sku'));
                    $qtyToShip = $this->getFieldData('qty');
                    $customData1 = $this->getFieldData('custom1');
                    $customData2 = $this->getFieldData('custom2');

                    if (!isset($updatesToProcess[$orderNumber])) {
                        $updatesToProcess[$orderNumber] = array(
                            "STATUS" => $status,
                            "ORDER_COMMENT" => $orderComment,
                            "CUSTOM_DATA1" => $customData1,
                            "CUSTOM_DATA2" => $customData2,
                        );
                        $updatesToProcess[$orderNumber]['tracks'] = array();
                        if ($trackingNumber !== '') {
                            $updatesToProcess[$orderNumber]['tracks'][$trackingNumber] = array(
                                "TRACKINGNUMBER" => $trackingNumber,
                                "CARRIER_CODE" => $carrierCode,
                                "CARRIER_NAME" => $carrierName,
                            );
                        }
                        $updatesToProcess[$orderNumber]['items'] = array();
                        if ($this->config['IMPORT_SHIPPED_ONE_FIELD'] == true) {
                            // We're supposed to import the SKU and Qtys all from one field. Each combination separated by a ; and sku/qty separated by :
                            $skuAndQtys = explode(";", $skuToShip);
                            foreach ($skuAndQtys as $skuAndQty) {
                                list ($sku, $qty) = explode(":", $skuAndQty);
                                $sku = strtolower($sku);
                                if ($sku !== '') {
                                    $updatesToProcess[$orderNumber]['items'][$sku] = array(
                                        "SKU" => $sku,
                                        "QTY" => $qty,
                                    );
                                }
                            }
                        } else {
                            // One row per SKU and QTY
                            if ($skuToShip !== '') {
                                if (isset($updatesToProcess[$orderNumber]['items'][$skuToShip])) {
                                    $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                        "SKU" => $skuToShip,
                                        "QTY" => $updatesToProcess[$orderNumber]['items'][$skuToShip]['QTY'] + $qtyToShip,
                                    );
                                } else {
                                    $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                        "SKU" => $skuToShip,
                                        "QTY" => $qtyToShip,
                                    );
                                }
                            }
                        }
                    } else {
                        // Add multiple tracking numbers and items to ship to $updates
                        if ($trackingNumber !== '') {
                            $updatesToProcess[$orderNumber]['tracks'][$trackingNumber] = array(
                                "TRACKINGNUMBER" => $trackingNumber,
                                "CARRIER_CODE" => $carrierCode,
                                "CARRIER_NAME" => $carrierName,
                            );
                        }
                        if ($skuToShip !== '') {
                            if (isset($updatesToProcess[$orderNumber]['items'][$skuToShip])) {
                                $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                    "SKU" => $skuToShip,
                                    "QTY" => $updatesToProcess[$orderNumber]['items'][$skuToShip]['QTY'] + $qtyToShip,
                                );
                            } else {
                                $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                    "SKU" => $skuToShip,
                                    "QTY" => $qtyToShip,
                                );
                            }
                        }
                    }
                }
            } else {
                // Traditional CSV format
                $fileHandle = fopen('php://memory', 'rw');
                fwrite($fileHandle, $data);
                rewind($fileHandle);

                $this->headerRow = array();
                while (($rowData = fgetcsv($fileHandle, 0, $this->config['IMPORT_DELIMITER'], $this->config['IMPORT_ENCLOSURE'])) !== false) {
                    $this->rowData = $rowData;

                    $rowCounter++;
                    if ($rowCounter == 1) {
                        // Skip the header line but parse it for field names.
                        $numberOfFields = count($rowData);
                        for ($i = 0; $i < $numberOfFields; $i++) {
                            $this->headerRow[$rowData[$i]] = $i;
                        }
                        if ($this->config['IMPORT_SKIP_HEADER'] == true) {
                            continue;
                        }
                    }

                    $orderNumber = $this->getFieldData('order_number');
                    if (empty($orderNumber)) {
                        continue;
                    }

                    $carrierCode = $this->getFieldData('carrier_code');
                    $carrierName = $this->getFieldData('carrier_name');
                    $trackingNumber = $this->getFieldData('tracking_number');
                    $status = $this->getFieldData('order_status');
                    $orderComment = $this->getFieldData('order_status_history_comment');
                    $skuToShip = strtolower($this->getFieldData('sku'));
                    $qtyToShip = $this->getFieldData('qty');
                    $customData1 = $this->getFieldData('custom1');
                    $customData2 = $this->getFieldData('custom2');

                    if (!isset($updatesToProcess[$orderNumber])) {
                        $updatesToProcess[$orderNumber] = array(
                            "STATUS" => $status,
                            "ORDER_COMMENT" => $orderComment,
                            "CUSTOM_DATA1" => $customData1,
                            "CUSTOM_DATA2" => $customData2,
                        );
                        $updatesToProcess[$orderNumber]['tracks'] = array();
                        if ($trackingNumber !== '') {
                            $trackingNumber = str_replace(array("/", ",", "|"), ";", $trackingNumber);
                            $trackingNumbers = explode(";", $trackingNumber); // Multiple tracking numbers in one field
                            foreach ($trackingNumbers as $trackingNumber) {
                                $updatesToProcess[$orderNumber]['tracks'][$trackingNumber] = array(
                                    "TRACKINGNUMBER" => $trackingNumber,
                                    "CARRIER_CODE" => $carrierCode,
                                    "CARRIER_NAME" => $carrierName,
                                );
                            }
                        }
                        $updatesToProcess[$orderNumber]['items'] = array();
                        if ($this->config['IMPORT_SHIPPED_ONE_FIELD'] == true) {
                            // We're supposed to import the SKU and Qtys all from one field. Each combination separated by a ; and sku/qty separated by :
                            $skuAndQtys = explode(";", $skuToShip);
                            foreach ($skuAndQtys as $skuAndQty) {
                                list ($sku, $qty) = explode(":", $skuAndQty);
                                $sku = strtolower($sku);
                                if ($sku !== '') {
                                    $updatesToProcess[$orderNumber]['items'][$sku] = array(
                                        "SKU" => $sku,
                                        "QTY" => $qty,
                                    );
                                }
                            }
                        } else {
                            // One row per SKU and QTY
                            if ($skuToShip !== '') {
                                if (isset($updatesToProcess[$orderNumber]['items'][$skuToShip])) {
                                    $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                        "SKU" => $skuToShip,
                                        "QTY" => $updatesToProcess[$orderNumber]['items'][$skuToShip]['QTY'] + $qtyToShip,
                                    );
                                } else {
                                    $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                        "SKU" => $skuToShip,
                                        "QTY" => $qtyToShip,
                                    );
                                }
                            }
                        }
                    } else {
                        // Add multiple tracking numbers and items to ship to $updates
                        if ($trackingNumber !== '') {
                            $trackingNumber = str_replace(array("/", ",", "|"), ";", $trackingNumber);
                            $trackingNumbers = explode(";", $trackingNumber); // Multiple tracking numbers in one field
                            foreach ($trackingNumbers as $trackingNumber) {
                                $updatesToProcess[$orderNumber]['tracks'][$trackingNumber] = array(
                                    "TRACKINGNUMBER" => $trackingNumber,
                                    "CARRIER_CODE" => $carrierCode,
                                    "CARRIER_NAME" => $carrierName,
                                );
                            }
                        }
                        if ($skuToShip !== '') {
                            if (isset($updatesToProcess[$orderNumber]['items'][$skuToShip])) {
                                $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                    "SKU" => $skuToShip,
                                    "QTY" => $updatesToProcess[$orderNumber]['items'][$skuToShip]['QTY'] + $qtyToShip,
                                );
                            } else {
                                $updatesToProcess[$orderNumber]['items'][$skuToShip] = array(
                                    "SKU" => $skuToShip,
                                    "QTY" => $qtyToShip,
                                );
                            }
                        }
                    }
                }
            }

            // Output the header row in a nicer string
            $hasHeaderRow = ($this->config['IMPORT_SKIP_HEADER']) ? "Yes" : "No";
            $headerRowTemp = $this->headerRow;
            array_walk($headerRowTemp, create_function('&$i,$k', '$i=" \"$k\"=\"$i\"";'));
            // File processed
            $updatesInFilesToProcess[] = array(
                "FILE_INFORMATION" => $importFile,
                "HAS_SKU_INFO" => ($this->mapping->getSku() !== null) ? true : false,
                "HEADER_ROW" => "Skip header row: " . $hasHeaderRow . " | Header row:" . implode($headerRowTemp, ""),
                "ORDERS" => $updatesToProcess
            );
        }

        @ini_set('auto_detect_line_endings', 0);

        //ini_set('xdebug.var_display_max_depth', 10);
        //Zend_Debug::dump($updatesToProcess);
        //die();

        return $updatesInFilesToProcess;
    }

    public function getFieldPos($field)
    {
        if (!is_numeric($this->mapping->getData($field)) && isset($this->headerRow[$this->mapping->getData($field)])) {
            return $this->headerRow[$this->mapping->getData($field)];
        } else {
            return $this->mapping->getData($field);
        }
    }

    public function getFieldData($field)
    {
        $data = '';
        if ($this->config['IMPORT_FIXED_LENGTH_FORMAT']) {
            $fieldPosition = explode("-", $this->getFieldPos($field));
            if (isset($fieldPosition[1])) {
                $data = trim(substr($this->rowData, $fieldPosition[0] - 1, $fieldPosition[1] - $fieldPosition[0]));
            }
        } else {
            if (isset($this->rowData[$this->getFieldPos($field)])) {
                $data = $this->rowData[$this->getFieldPos($field)];
            }
        }
        if ($data == '') {
            // Try to get the default value at least.. otherwise ''
            $data = $this->mappingModel->getDefaultValue($field);
        }
        return trim($data);
    }

    /*
     * Split large CSV files into smaller files (chunks)
     *
     * Works for local directory imports only
     */
    public function chunkFiles($filesToProcess, $localConnection)
    {
        $this->_initConfiguration();
        @ini_set('auto_detect_line_endings', 1);

        /* Configuration */
        $linesPerFile = 50;

        foreach ($filesToProcess as $fileIndex => $importFile) {
            $data = $importFile['data'];
            $type = $importFile['type'];
            $filename = $importFile['filename'];
            $targetPath = $importFile['path'];

            if ($type !== Xtento_OrderStatusImport_Model_Observer::SOURCE_TYPE_LOCAL) {
                continue;
            }

            if (preg_match('/\.chunk\./', $filename)) {
                // This is a chunk file. Don't process it again.
                continue;
            }

            $i = 0;
            $chunkCount = 1;
            $buffer = '';

            // Open file handle for parsing all lines into an array
            $fileHandle = fopen('php://memory', 'rw');
            fwrite($fileHandle, $data);
            rewind($fileHandle);

            $allRows = array();
            $loopCounter = 0;
            while (($rowData = fgetcsv($fileHandle, 0, $this->config['IMPORT_DELIMITER'], $this->config['IMPORT_ENCLOSURE'])) !== false) {
                $loopCounter++;
                $allRows[$loopCounter] = $rowData;
            }
            fclose($fileHandle);

            // Open handle for CSV processing once again..
            $fileHandle = fopen('php://memory', 'rw');
            fwrite($fileHandle, $data);
            rewind($fileHandle);

            $this->headerRow = array(); // Array containing header mapping
            $headerRow = ''; // Plain string used in the chunk files
            $lastOrderNumber = false;
            $lineCounter = 0;

            while (($rowData = fgetcsv($fileHandle, 0, $this->config['IMPORT_DELIMITER'], $this->config['IMPORT_ENCLOSURE'])) !== false) {
                $this->rowData = $rowData;

                // Get CSV line
                $tempHandle = fopen("php://temp", 'r+');
                fputcsv($tempHandle, $rowData, $this->config['IMPORT_DELIMITER'], $this->config['IMPORT_ENCLOSURE']);
                rewind($tempHandle);
                $csvLine = fgets($tempHandle);
                fclose($tempHandle);
                $buffer .= $csvLine;

                $lineCounter++;
                $i++;

                if ($lineCounter == 1) {
                    // Skip the header line but parse it for field names.
                    $numberOfFields = count($rowData);
                    for ($iX = 0; $iX < $numberOfFields; $iX++) {
                        $this->headerRow[$rowData[$iX]] = $iX;
                    }
                }
                if ($lineCounter == 1 && $this->config['IMPORT_SKIP_HEADER'] == true) {
                    // This is the header line
                    $headerRow = $buffer;
                }

                $outputFile = true;
                $currentOrderNumber = $this->getFieldData('order_number');
                $nextOrderNumber = false;
                // Temporary set rowData to next row to get next order number.. then reset
                if (isset($allRows[$lineCounter + 1])) {
                    $this->rowData = $allRows[$lineCounter + 1];
                    $nextOrderNumber = $this->getFieldData('order_number');
                    $this->rowData = $rowData;
                }

                if ($lastOrderNumber == $currentOrderNumber || $nextOrderNumber == $currentOrderNumber) {
                    $outputFile = false;
                }
                if ($i >= $linesPerFile && $outputFile) {
                    $chunkName = $filename . ".chunk." . $chunkCount;

                    $createChunkFile = true;
                    foreach ($filesToProcess as $importFile) {
                        if ($importFile['filename'] == $chunkName) {
                            $createChunkFile = false;
                        }
                    }
                    if ($createChunkFile) {
                        if (!$fhandle = @fopen($targetPath . $chunkName, 'w')) {
                            Mage::throwException("File chunking: Cannot open chunk file for writing " . $targetPath . $chunkName . ". Make sure the local directory folder has write permissions. Aborting import");
                        }

                        if ($chunkCount > 1 && $headerRow !== '') {
                            $buffer = $headerRow . $buffer;
                        }

                        if (!@fwrite($fhandle, $buffer)) {
                            Mage::throwException("File chunking: Cannot write chunk file " . $targetPath . $chunkName . ". Make sure the local directory folder has write permissions. Aborting import");
                        }
                        fclose($fhandle);

                        $filesToProcess[] = array('type' => $importFile['type'], 'path' => $targetPath, 'filename' => $chunkName, 'data' => $buffer);
                    }
                    $chunkCount++;
                    $buffer = '';
                    $i = 0;

                    if (isset($filesToProcess[$fileIndex])) {
                        // Do not process original file, delete it.
                        if ($localConnection) {
                            $localConnection->archiveFiles(array($filesToProcess[$fileIndex]), true); // Force delete
                        }
                        unset($filesToProcess[$fileIndex]);
                    }
                }
                $lastOrderNumber = $this->getFieldData('order_number');
            }
            if (!empty($buffer) && $chunkCount > 1) {
                // Last chunk
                $chunkName = $filename . ".chunk." . $chunkCount;

                $createChunkFile = true;
                foreach ($filesToProcess as $importFile) {
                    if ($importFile['filename'] == $chunkName) {
                        $createChunkFile = false;
                    }
                }
                if ($createChunkFile) {
                    if (!$fhandle = @fopen($targetPath . $chunkName, 'w')) {
                        Mage::throwException("File chunking: Cannot open chunk file for writing " . $targetPath . $chunkName . ". Make sure the local directory folder has write permissions. Aborting import");
                    }

                    if ($chunkCount > 1 && $headerRow !== '') {
                        $buffer = $headerRow . $buffer;
                    }

                    if (!@fwrite($fhandle, $buffer)) {
                        Mage::throwException("File chunking: Cannot write chunk file " . $targetPath . $chunkName . ". Make sure the local directory folder has write permissions. Aborting import");
                    }
                    fclose($fhandle);

                    $filesToProcess[] = array('type' => $importFile['type'], 'path' => $targetPath, 'filename' => $chunkName, 'data' => $buffer);
                }
            }
            fclose($fileHandle);
        }

        //var_dump($filesToProcess);
        //die();

        @ini_set('auto_detect_line_endings', 0);

        return $filesToProcess;
    }
}
