<?php

class Novus_Ecommhub_Model_Config_ApiKey extends Mage_Core_Model_Config_Data
{
    /**
     * Xml config path to value of ecommhubfield1fromgroup1 field from system.xml
     *
     */
    const XML_PATH_ECOMMHUB_GROUP1_VALUES = 'ecommhubsection1/ecommhubgroup1/ecommhubfield1fromgroup1';
    
    public function getSomeValueFromSystemConfigFile()
    {
        return Mage::getStoreConfig(self::XML_PATH_ECOMMHUB_GROUP1_VALUES);
    }

}
