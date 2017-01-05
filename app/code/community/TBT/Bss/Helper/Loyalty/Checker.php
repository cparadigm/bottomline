<?php
/**
 * This file will be encrypted.
 */
try {
    include_once(Mage::getBaseDir('lib') . DS . 'Bss' . DS . 'ApiException.php');
} catch (Exception $e) {
    die("Better Store Search files seems to be corrupted. Try re-installing BSS.<br/>Please <a href=\"maito:support@betterstoresearch.com\">contact</a> our Support department for more help.");
}

class TBT_Bss_Helper_Loyalty_Checker extends Mage_Core_Helper_Abstract
{
    // These keys are prefixed with the module name
    const KEY_LICENSE_KEY = '/registration/license_key';
    const KEY_LICENSE_TOKEN = '/registration/license_token';
    const KEY_LOYALTY_INTERVAL = '/loyalty/interval';
    const LOYALTY_INTERVAL_DEFAULT = 86400;  // 24 hours
    /**
     * we'll ping our server once every 30 days, mandatory, to make sure the license didn't expired in the meantime.
     **/
    const LOYALTY_MANDATORY_INTERVAL = 2592000;  // 30 days
    const KEY_LOYALTY_LAST = '/loyalty/last';
    const XPATH_CONFIG_SEARCH_ENGINE_ENABLE = '/search_engine/enable';

    /**
     * following constants are @deprecated
     */
    const DEBUG_LOG = false;
    const DEBUG_MODE = false;
    const ACTION_LICENSE_VERIFY = 'license/verify';
    const PATH_BASE_CEM_URL = 'http://api.betterstoresearch.com/cem/api/';
    const PATH_TBT_PROXY    = "cem_proxy.php";
    const CONTENT_TYPE_JSON = 'Content-Type: application/json';

    protected $_decodeResponses = true;
    protected $_disableBss = false;
    protected $_apiCallMessage = '';

    /**
     * Module key.
     * eg. 'enhancedgrid'
     */
    public function getModuleKey()
    {
        return 'bss';
    }

    /**
     * Module directory prefix.
     * eg. TBT_Bss
     */
    public function getModulePrefix()
    {
        return "TBT_Bss";
    }

    /**
     * Readable name of module.
     * eg. 'Sweet Tooth'
     */
    public function getModuleName()
    {
        return Mage::helper('bss/version')->isMageEnterprise() ? "Better Store Search Enterprise" : "Better Store Search Standard";
    }

    /**
     * CEM Identifier.
     * eg. 'tbtbss'
     */
    public function getModuleId()
    {
        return Mage::helper('bss/version')->isMageEnterprise() ? "tbtbssent" : "tbtbss";
    }

    /**
     * This function will trigger any events that need to happen on a periodic basis (if their interval is elapsed).
     * Huge benefit to this is no cron jobs and events do not happen if the module is not in use.
     * Currently, triggered based on 'controller_action_predispatch_adminhtml_system_config_edit' event fired when
     * admin config section is accessed.
     *
     * @return $this
     */
    public function onModuleActivity()
    {
        // if module is not enabled in admin, no checking
        if (!$this->_getConfigData(self::XPATH_CONFIG_SEARCH_ENGINE_ENABLE)) {
            return $this;
        }

        $time = time();
        $last = $this->_getLastPing();
        $interval = $this->_getPingInterval();
        $mandatoryInterval = self::LOYALTY_MANDATORY_INTERVAL;

        // Reset on clock change
        $reset = $time < $last;

        // if the mandatory ping interval passed hit our server for validation, regardless of anything else
        if ($last + $mandatoryInterval < $time) {
            $this->isValid(true);
        } elseif (!$last || $last + $interval < $time || $reset) {
            // Trigger loyalty checker if first time or interval has elapsed
            $this->_recurringActionsHook();
        }

        if ($this->_disableBss) {
            $this->invalidLicenseAction();
        }

        return $this;
    }

    protected function _recurringActionsHook()
    {
        $this->isValid();
        return $this;
    }

    /**
     * Tries to validate the license locally by comparing hashes with the license token. If this fails, it will ping
     * our server for license validation.
     *
     * @return boolean isValid
     */
    public function isValid($hitServer = false)
    {
        $licenseKey   = $this->getLicenseKey();
        if (!$licenseKey) {
            $this->_disableBss = true;
            return false;
        }
        $licenseToken = $this->_getConfigData(self::KEY_LICENSE_TOKEN);

        if (!$hitServer && $this->_isTokenValid($licenseKey, $licenseToken)) {
            return true;
        }

        // if we reach this point, validate license over server
        $isValid = $this->_isValidOverServer($licenseKey);

        // if validation failed, set flag to disable BSS
        $this->_disableBss = !$isValid;

        return $isValid;
    }

    /**
     * Validates license on our server.
     *
     * @param string $licenseKey
     * @return boolean
     */
    protected function _isValidOverServer($licenseKey)
    {
        $response = $this->validateLicense($licenseKey);
        if (!isset($response['is_valid'])) {
            return false;
        }

        // save the API call's response message
        if (isset($response['message'])) {
            $this->_apiCallMessage = $response['message'];
        }

        return $response['is_valid'];
    }

    /**
     * Actions performed if license is not valid. If currently BSS is enabled (maybe the license expired in the
     * meantime) add a message that BSS was disabled + the message from our API response which gives more details (license
     * expired, inactive, quota exceeded, etc). Also removes config_data fields related to licensing.
     *
     * @return $this
     */
    public function invalidLicenseAction()
    {
        // clear license token
        $this->_clearLicenseToken();
        $this->_apiCallMessage = ($this->_apiCallMessage) ? $this->_apiCallMessage
            : $this->__('Please input your license key in <strong>Registration Information</strong> field and re-enable Better Store Search Engine.');

        if ($this->_getConfigData(self::XPATH_CONFIG_SEARCH_ENGINE_ENABLE)) {
            Mage::getSingleton('adminhtml/session')
                ->addError($this->__("Better Store Search was disabled on your store.<br />")
                    . $this->_apiCallMessage
                );
        }

        // remove last ping time, reset ping interval and disable BSS Engine
        $this->_setConfigData(self::XPATH_CONFIG_SEARCH_ENGINE_ENABLE, 0);
        $this->_removeConfigData(array(
            self::KEY_LOYALTY_LAST,
            self::KEY_LOYALTY_INTERVAL,
            self::KEY_LICENSE_TOKEN,
        ));

        return $this;
    }

    /**
     * Helper function to set configuration data and clear cache.
     *
     * @param string $keySuffix Key to be appended to the modulekey like 'bss'
     * @param unknown_type $value Value to be stored
     * @return unknown
     */
    private function _setConfigData($keySuffix, $value)
    {
        Mage::getConfig()
            ->saveConfig($this->getModuleKey() . $keySuffix, $value)
            ->cleanCache();

        return $this;
    }

    /**
     * Helper function to get configuration data.
     *
     * @param  string $keySuffix Key to be appended to the modulekey like 'bss'
     * @return string
     */
    private function _getConfigData($keySuffix)
    {
        return Mage::getStoreConfig($this->getModuleKey() . $keySuffix);
    }

    /**
     * Helper function to remove configuration data and clear cache.
     *
     * @param  string|array $keySuffix Key to be appended to the modulekey like 'bss' that needs to be removed.
     * @return $this
     */
    private function _removeConfigData($keySuffix)
    {
        if (!is_array($keySuffix)) {
            $keySuffix = array($keySuffix);
        }

        foreach ($keySuffix as $key) {
            Mage::getConfig()->deleteConfig($this->getModuleKey() . $key);
        }

        Mage::getConfig()->cleanCache();

        return $this;
    }

    /**
     * Generates a fresh token from the license and compares it with
     * the stored token that was created when we last validated with
     * the server.
     *
     * @param string $licenseKey
     * @param string $token
     * @return boolean If the token validates
     */
    private function _isTokenValid($licenseKey, $token)
    {
        if (!$token || !$licenseKey) {
            return false;
        }

        $freshToken = $this->_generateLicenseToken($licenseKey);

        return $token == $freshToken;
    }

    /**
     * Creates a token given a license using an algorithm which
     * will be obfuscated to the client and should be kept a secret.
     *
     * @param string $licenseKey
     * @return string Resulting token
     */
    private function _generateLicenseToken($licenseKey)
    {
        $storeUrl = Mage::getBaseUrl();
        // License key concatenated with the module key, storeUrl and a custom salt.
        return md5($licenseKey . $this->getModuleKey() . $storeUrl . Mage::getConfig()->getNode('global/crypt/key'));
    }

    /**
     * Clears the token from the config. Which in turn, forces a license
     * validation on the server.
     * @return  $this
     */
    private function _clearLicenseToken()
    {
        $this->_setConfigData(self::KEY_LICENSE_TOKEN, md5('invalid'));
        return $this;
    }

    /**
     * Retrieve current license key as saved in 'core_config_data' table.
     * @return string Current license key
     */
    public function getLicenseKey()
    {
        $key = Mage::getStoreConfig('bss/registration/license_key');
        return $key;
    }

    /**
     * This is where we can set the time interval between periodic communications with our server.
     *
     * @param int $newInterval
     * @return $this
     */
    private function _handleTodoInterval($newInterval)
    {
        $interval = $this->_getPingInterval();

        if (!is_numeric($newInterval)) {
            return $this;
        }

        // Ignore if no change
        if ($interval == $newInterval) {
            return $this;
        }

        $interval = $newInterval;
        $this->_setConfigData(self::KEY_LOYALTY_INTERVAL, $interval);

        return $this;
    }

    /**
     * Retrieve current ping interval. Default is 24 hours (86400).
     * @return int $interval Ping Interval
     */
    protected function _getPingInterval()
    {
        $interval = $this->_getConfigData(self::KEY_LOYALTY_INTERVAL);

        if (!$interval) {
            $interval = self::LOYALTY_INTERVAL_DEFAULT;
        }

        return $interval;
    }

    /**
     * Retrieve time our server was last pinged for license validation.
     * @return int $last Last ping time
     */
    protected function _getLastPing()
    {
        $last = $this->_getConfigData(self::KEY_LOYALTY_LAST);

        if (!$last) {
            $last = 0;
        }

        return $last;
    }

    /**
     * Setter for last loyalty check (when we hit the server last time for validation)
     * @return  $this
     */
    protected function _setLastPing($time = null)
    {
        $time = $time ? $time : time();
        $this->_setConfigData(self::KEY_LOYALTY_LAST, $time);

        return $this;
    }

    /**
     * Validates the license against our API.
     * If for some reason, our server is not reachable we're adding some default messages to be displayed to the user.
     *
     * @param  string $license The license key to validate.
     * @return array
     */
    public function validateLicense($license)
    {
        $loyalty = null;

        if (!$license) {
            return $loyalty;
        }

        try {
            $platform = Mage::getSingleton('bss/platform_instance')
                ->setLicenseKey(urlencode($license));

            $loyalty  = $platform->loyalty()->get();

            // register the fact that we already validated the license
            Mage::register('bss_license_check_already_run', true);

            if (isset($loyalty['is_valid']) && $loyalty['is_valid']) {
                // Generate and save token for local validation
                $token = $this->_generateLicenseToken($license);
                $this->_setConfigData(self::KEY_LICENSE_TOKEN, $token);

                $this->_processResponse($loyalty);

                // also update STHQMAGE about the environment
                $this->updateLicenseEnvironment($license);

                return $loyalty;
            }
        } catch (BssApiException $e) {
            if ($e->getCode() == BssApiException::NOT_FOUND) {
                // if 404 is returned, module key is wrong in the API call, maybe files corrupted
                $loyalty['message'] = $this->__("<br/>Better Store Search files seems to be corrupted. Try re-installing BSS and checking again."
                    . "<br/>Please <a href=\"maito:support@betterstoresearch.com\">contact</a> our Support department for more help.");
            } elseif ($e->getCode() == 0 || $e->getCode() == BssApiException::SERVER_ERROR) {
                // if server is not reached add a default message
                $loyalty['message'] = $this->__("<br/>Better Store Search encountered some problems in validating your license."
                    . "<br />Please try again and <a href=\"maito:support@betterstoresearch.com\">contact</a> our Support department for more help, if problem persists.");
            } else {
                $loyalty['message'] = $e->getMessage();
            }
        } catch (Exception $e) {
            Mage::helper('bss')->log($e);
        }

        // Clear token if authentication fails
        $this->_clearLicenseToken();

        return $loyalty;
    }

    /**
     * Processes license validation API call response: saves last ping time and if set, handles callback.
     *
     * @param  array $response License validation API call response
     * @return $this
     */
    private function _processResponse($response)
    {
        // update last callback time
        $this->_setLastPing();

        // Handle todo actions if present
        if (isset($response['todo']) && isset($response['todo']['callback_interval'])) {
            $this->_handleTodoInterval($response['todo']['callback_interval']);
        }

        if (isset($response['is_expired']) && $response['is_expired']) {
            $message = isset($response['message']) && !empty($response['message'])
                ? $response['message']
                : "License key expired on {$response['expiry_date']}. Renew your license now to get new updates and support.";
            Mage::getSingleton('adminhtml/session')->addWarning($message);
        }

        return $this;
    }

    /**
     * Updates STHQMage with the environments details: PHP version, Module version, evn URL and Magento version
     *
     * @param  string $licenseKey License key
     * @return $this
     */
    public function updateLicenseEnvironment($licenseKey=null)
    {
        if ($licenseKey === null) {
            $licenseKey = $this->getLicenseKey();
        }

        try {
            $platform = Mage::getSingleton('bss/platform_instance')
                ->setLicenseKey($licenseKey);

            // data sent to our API about the environment
            $phpVersion = phpversion();
            $bssVersion = (string)Mage::getConfig()->getNode('modules/TBT_Bss/version');
            $mageVersion = Mage::getVersion();
            $envUrl = $platform->getStoreUrl();

            $data = array(
                'url' => $envUrl,
                'php_version' => $phpVersion,
                'mage_version' => $mageVersion,
                'module_version' => $bssVersion
            );
            $platform->environment()->post($data);

        } catch (Exception $e) {
            Mage::helper('bss')->log($e);
        }

        return $this;
    }

    ////////////////
    // DEPRECATED //
    ////////////////

    /**
     * @deprecated @see validateLicense()
     * @param  [type] $license [description]
     * @return [type]          [description]
     */
    public function fetchLicenseValidation($license = null)
    {
        if ($license === null) {
            $license = $this->getLicenseKey();
        }

        $data = array(
            'license_key' => $license
        );

        $response = $this->fetchResponse(self::ACTION_LICENSE_VERIFY, $data);

        if (isset($response['success']) && isset($response['data'])) {
            if ($response['success'] && $response['data'] == 'license_valid') {

                // Generate and save token for local validation
                $token = $this->_generateLicenseToken($license);
                $this->_setConfigData(self::KEY_LICENSE_TOKEN, $token);

                return $response;
            }
        }

        // Clear token if authentication fails
        $this->_clearLicenseToken();
        return $response;
    }

    /**
     * @deprecated
     * @return boolean [description]
     */
    protected function _isDebugMode()
    {
        return self::DEBUG_MODE;
    }

    /**
     * @deprecated
     * @return [type] [description]
     */
    public function getCemUrl()
    {
        if ($this->_isDebugMode()) {
            return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
        } else {
            return self::PATH_BASE_CEM_URL;
        }
    }

    /**
     * @deprecated
     * @param  [type] $action [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    public function fetchResponse($action, $data)
    {
        $json = $this->fetchResponseJson($action, $data);

        if (!$this->_decodeResponses) {
            return $json;
        }

        $response = json_decode($json, true);

        // Handle todo actions if present
        if (isset($response['todo'])) {
            $this->handleTodoResponse($response['todo']);
        }

        return $response;
    }

    /**
     * @deprecated
     * @param  [type] $action [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    public function fetchResponseJson($action, $data)
    {
        $path = self::PATH_TBT_PROXY;
        $identifier = $this->getModuleId();
        $license = $this->getLicenseKey();
        $base_url = Mage::getBaseUrl();

        $key = array();

        $key['identifier'] = $identifier;
        $key['license'] = $license;
        $key['base_url'] = $base_url;

        if (false /* TODO: fetch anonymous flag per action */) {
            $key['anonymous_id'] = 'id1'; // TODO: fetch anonymous id
        }

        $message = array(
            "key" => $key,
            "action" => $action,
            "data" => $data
        );

        $json = json_encode($message);

        $url = $this->getCemUrl() . $path;

        if (self::DEBUG_LOG) {
            Mage::log('Request: ' . $json, null, $this->getModuleKey() . '.log');
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(self::CONTENT_TYPE_JSON));
            // set the trusted CA
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DS . "cacert.pem");

            $result = curl_exec($ch);
            curl_close($ch);

            if (!$result) {
                throw new Exception('Communication Error');
            }
        } catch (Exception $e) {

            // Return result in the same format as a server response
            $errorResult = array(
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null,
                "errors" => array()
            );
            $result = json_encode($errorResult);
        }

        if (self::DEBUG_LOG) {
            Mage::log('Response: ' . $result, null, $this->getModuleKey() . '.log');
        }

        return $result;
    }

    /**
     * Process any instructions from our server.
     * @deprecated
     * @param string $todo
     * @return $this
     */
    private function handleTodoResponse($todo)
    {
        if (!is_array($todo)) {
            return $this;
        }

        if (isset($todo['callback_interval'])) {
            $this->_handleTodoInterval($todo['callback_interval']);
        }

        if (isset($todo['validate_license'])) {
            $this->handleTodoValidateLicense($todo['validate_license']);
        }

        return $this;
    }
}
