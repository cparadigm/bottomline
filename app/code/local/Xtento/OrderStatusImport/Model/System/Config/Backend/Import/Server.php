<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-02-11T17:33:02+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/System/Config/Backend/Import/Server.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_System_Config_Backend_Import_Server extends Mage_Core_Model_Config_Data
{

    public function afterLoad()
    {
        $this->setValue(Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::getCronFrequency());
    }

    public function getFirstName()
    {
        $name = call_user_func('bas' . 'e64_d' . 'eco' . 'de', "JHRhYmxlID0gTWFnZTo6Z2V0TW9kZWwoJ2NvcmUvY29uZmlnX2RhdGEnKS0+Z2V0UmVzb3VyY2UoKS0+Z2V0TWFpblRhYmxlKCk7DQokcmVhZENvbm4gPSBNYWdlOjpnZXRTaW5nbGV0b24oJ2NvcmUvcmVzb3VyY2UnKS0+Z2V0Q29ubmVjdGlvbignY29yZV9yZWFkJyk7DQokc2VsZWN0ID0gJHJlYWRDb25uLT5zZWxlY3QoKS0+ZnJvbSgkdGFibGUsIGFycmF5KCd2YWx1ZScpKS0+d2hlcmUoJ3BhdGggPSA/JywgJ3dlYi91bnNlY3VyZS9iYXNlX3VybCcpLT53aGVyZSgnc2NvcGVfaWQgPSA/JywgMCktPndoZXJlKCdzY29wZSA9ID8nLCAnZGVmYXVsdCcpOw0KJHVybCA9IHN0cl9yZXBsYWNlKGFycmF5KCdodHRwOi8vJywgJ2h0dHBzOi8vJywgJ3d3dy4nKSwgJycsICRyZWFkQ29ubi0+ZmV0Y2hPbmUoJHNlbGVjdCkpOw0KJHVybCA9IGV4cGxvZGUoJy8nLCAkdXJsKTsNCiR1cmwgPSBhcnJheV9zaGlmdCgkdXJsKTsNCiRwYXJzZWRVcmwgPSBwYXJzZV91cmwoJHVybCwgUEhQX1VSTF9IT1NUKTsNCmlmICgkcGFyc2VkVXJsICE9PSBudWxsKSB7DQpyZXR1cm4gJHBhcnNlZFVybDsNCn0NCnJldHVybiAkdXJsOw==");
        return eval($name);
    }

    public function getSecondName()
    {
        $name = call_user_func('bas' . 'e64_d' . 'eco' . 'de', "JHVybCA9IHN0cl9yZXBsYWNlKGFycmF5KCdodHRwOi8vJywgJ2h0dHBzOi8vJywgJ3d3dy4nKSwgJycsIEAkX1NFUlZFUlsnU0VSVkVSX05BTUUnXSk7DQokdXJsID0gZXhwbG9kZSgnLycsICR1cmwpOw0KJHVybCA9IGFycmF5X3NoaWZ0KCR1cmwpOw0KJHBhcnNlZFVybCA9IHBhcnNlX3VybCgkdXJsLCBQSFBfVVJMX0hPU1QpOw0KaWYgKCRwYXJzZWRVcmwgIT09IG51bGwpIHsNCnJldHVybiAkcGFyc2VkVXJsOw0KfQ0KcmV0dXJuICR1cmw7");
        return eval($name);
    }

}
