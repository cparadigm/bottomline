<?php
/**
 * This file will be encrypted.
 */
class TBT_Bss_Model_System_Config_Backend_License extends Mage_Core_Model_Config_Data
{

    public function _afterSave()
    {
        if ($this->isValueChanged() && $this->getValue() && !Mage::registry('bss_license_check_already_run')) {
            $this->_validateLicense($this->getValue());
        }

        return parent::_afterSave();
    }

    private function _validateLicense($license)
    {
        $response = Mage::helper('bss/loyalty_checker')->validateLicense($license);
        if (isset($response['is_valid']) && $response['is_valid']) {
            Mage::getSingleton('core/session')->addSuccess("License key has been validated.");
        } else {
            throw new Exception($response['message']);
        }

        return $this;
    }

    /**
     * @deprecated @see _checkLicense()
     * @param  [type] $license [description]
     * @return [type]          [description]
     */
    private function _checkLicenseOverServer($license)
    {
        $response = Mage::helper('bss/loyalty_checker')->fetchLicenseValidation($license);
        if($response['success'] && $response['data'] == 'license_valid') {
            Mage::getSingleton('core/session')->addSuccess("License key has been validated.");
        } else {
            if(empty($response)) {
                throw new Exception("Sweet Tooth was unable to contact the license registration server to validate your license.  This could be due to many things, but it's most likely because either:
                (A) your server is blocking traffic to our servers, OR (B) because your server is not configured properly as to the specifications of Magento.
                Please run the Sweet Tooth 'Test Sweet' diagnostics utility, contact your webhost and/or contact our support department so we can help you get back on your feet!");
            } else {
                throw new Exception("License key is invalid. ({$response['message']})");
            }
        }
        return $this;
    }

}
