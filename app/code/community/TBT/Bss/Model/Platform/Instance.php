<?php
/**
 * This file will be encrypted.
 */
?>
<?php
try {
    include_once(Mage::getBaseDir('lib') . DS . 'Bss' . DS . 'BssClient.php');
} catch (Exception $e) {
    die("Better Store Search files seems to be corrupted. Try re-installing BSS.<br/>Please <a href=\"maito:support@betterstoresearch.com\">contact</a> our Support department for more help.");
}

class TBT_Bss_Model_Platform_Instance extends BSSClient
{
    const CONFIG_DEBUG_MODE  = false;

    public function __construct()
    {
        $moduleIdentifier = Mage::helper('bss/loyalty_checker')->getModuleId();
        $licenseKey       = $this->getLicenseKey()
            ? $this->getLicenseKey()
            : Mage::helper('bss/loyalty_checker')->getLicenseKey();

        $instance = parent::__construct($moduleIdentifier, $licenseKey);
        $instance->setStoreUrl(Mage::getBaseUrl());

        return $instance;
    }

    public function loyalty() {
        include_once(Mage::getBaseDir('lib') . DS . 'Bss' . DS . 'classes' . DS . 'Loyalty.php');
        return new BssLoyalty($this);
    }

    public function environment() {
        include_once(Mage::getBaseDir('lib') . DS . 'Bss' . DS . 'classes' . DS . 'Environment.php');
        return new BssEnvironment($this);
    }

    /**
     * Logging outgoing GET requests.  This is useful for performance testing as well as testing any unexpected
     * responses or connectivity issues.
     *
     */
    public function get($resource, $data = array())
    {
        // set store url on GET call
        if ($this->getStoreUrl()) {
            $data = array_merge($data, array('url' => $this->getStoreUrl()));
        }

        if (!self::CONFIG_DEBUG_MODE) {
            return parent::get($resource, $data);
        }

        $url = $this->getApiBaseUrl() . $resource;
        $restClient = $this->getClient();

        Mage::helper('bss')->log(sprintf("Debug: RESTClient Object: %s", print_r($restClient, true)));
        Mage::helper('bss')->log(sprintf("Debug: Querying API: %s", $url));

        $startTime = microtime(true);
        $result = parent::get($resource, $data);
        $endTime = microtime(true);

        Mage::helper('bss')->log(sprintf("Debug: Query complete (took %ss). Result: %s", round(($endTime - $startTime) / 1000, 3), print_r($result, true)));

        return $result;
    }

    /**
     * Logging outgoing POST requests.  This is useful for performance testing as well as testing any unexpected
     * responses or connectivity issues.
     *
     */
    public function post($resource, $data)
    {
        if (!self::CONFIG_DEBUG_MODE) {
            return parent::post($resource, $data);
        }

        $url = $this->getApiBaseUrl() . $resource;
        $json = json_encode($data, true);
        $restClient = $this->getClient();

        Mage::helper('bss')->log(sprintf("Debug: RESTClient Object: %s", print_r($restClient, true)));
        Mage::helper('bss')->log(sprintf("Debug: Posting to API: %s: JSON: %s", $url, $json));

        $startTime = microtime(true);
        $result = parent::post($resource, $data);
        $endTime = microtime(true);

        Mage::helper('bss')->log(sprintf("Debug: Posting complete (took %ss). Result: %s", round(($endTime - $startTime) / 1000, 3), print_r($result, true)));

        return $result;
    }
}
