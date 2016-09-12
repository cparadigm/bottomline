<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-02-24T15:25:18+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Block/System/Config/Frontend/Mapping/Csv.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Block_System_Config_Frontend_Mapping_Csv extends Xtento_OrderStatusImport_Block_System_Config_Frontend_Mapping_Abstract
{
    protected $MAPPING_ID = 'csv';
    protected $DATA_PATH = 'orderstatusimport/processor_csv/import_mapping';
    protected $MAPPING_MODEL = 'orderstatusimport/processor_mapping_fields';
    protected $VALUE_FIELD_NAME = 'Field Name / Index';
}
