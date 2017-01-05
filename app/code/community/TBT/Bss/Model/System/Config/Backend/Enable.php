<?php
/**
 * This file will be encrypted.
 */
class TBT_Bss_Model_System_Config_Backend_Enable extends Mage_Core_Model_Config_Data
{

    /**
     * If customer tries to enable BSS Search Engine in config and no license key is entered, don't enable BSS.
     *
     * @return $this
     */
    protected function _afterSave()
    {
        $groups = $this->getGroups();

        $storeCode   = $this->getStoreCode();
        $websiteCode = $this->getWebsiteCode();
        $path        = "bss/system_config_backend_license";

        if ($storeCode) {
            return Mage::app()->getStore($storeCode)->getConfig($path);
        } elseif ($websiteCode) {
            return Mage::app()->getWebsite($websiteCode)->getConfig($path);
        } else {
            $licenseKey = $groups['registration']['fields']['license_key']['value'];
        }

        // if a license key is not set, restrict enabling BSS Engine
        if (!$licenseKey) {
            throw new Exception("Please input your license key in order and enable Better Store Search Engine.");
        }

        return parent::_afterSave();
    }
}
