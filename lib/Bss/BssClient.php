<?php

// Define constants for multi-server compatibility if they have not already been defined.
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if(!defined('PS')) define('PS', PATH_SEPARATOR);
if(!defined('BP')) define('BP', dirname(dirname(__FILE__)));

// Include REST library files:
// safety check to avoid conflicts if PestJSON is already included (by ST for example)
if (!@include_once(dirname(__FILE__). DS .'pest'. DS .'PestJSON.php')) {
    include_once(dirname(__FILE__). DS .'pest'. DS .'PestJSON.php');
}
include_once(dirname(__FILE__). DS .'ApiException.php');

class BssClient
{
    /**
     * store url on which the license is used.
     * @var string
     **/
    protected $_storeUrl = null;

    protected $_client = null;

    /**
     * the module identifier that uniquely identifies the license on our API
     * @var string
     **/
    protected $_moduleIdentifier = null;

    /**
     * the module's license key
     * @var string
     */
    protected $_licenseKey = null;

    /**
     * our API domain
     * @var string
     */
    protected $_apiDomain = 'http://api.betterstoresearch.com/';

    /**
     * our API end-point
     * @var string
     */
    protected $_apiEndpoint = 'index.php/v1/';

    /**
     * the API url. this has the following form:
     * _apiDomain + _apiEndpoint + _moduleIdentifier + 'license' + _licenseKey
     * ex: http://api.betterstoresearch.com/index.php/v1/tbtbssent/license/SJvyOLRANf7FZ5J7ymZ7RZ7lrOtnkFi3eErTg0jTegp5aZ1zbQ
     * @var string
     */
    protected $_apiUrl = null;

    /**
     * Constructor hit whenever a new instance of BSS/CMS is created.
     *
     * @param string $moduleIdentifier Module identifier (tbtbss, tbtbssent, stbettercms, stbettercmsent)
     * @param string $licenseKey       License key for the module
     */
    public function __construct($moduleIdentifier, $licenseKey)
    {
        $this->_moduleIdentifier = $moduleIdentifier;
        $this->_licenseKey       = $licenseKey;

        return $this;
    }

    public function getClient()
    {
        if ($this->_client) {
            return $this->_client;
        }

        $this->_client = new PestJSON($this->getApiBaseUrl());
        /* Defaulting timeout to 10s.  The Pest library does not default the timeout.
         * CURLOPT_TIMEOUT_MS may not be recognized by older versions of PHP
         */
        $this->_client->curl_opts[/*CURLOPT_TIMEOUT_MS*/ 156] = 10000;

        return $this->_client;
    }

    public function getApiBaseUrl()
    {
        if (!$this->_apiUrl) {
            $url = $this->_apiDomain . $this->_apiEndpoint;
            $url .= $this->_moduleIdentifier . '/' . 'license' . '/' . $this->_licenseKey;
            $this->_apiUrl = $url;
        }

        return $this->_apiUrl;
    }

    /**
     * Setter for the store url, on which the license is used.
     * @param string $storeUrl store url on which the extension is used.
     */
    public function setStoreUrl($storeUrl)
    {
        $this->_storeUrl = $storeUrl;
        return $this;
    }

    /**
     * Getter for store url.
     * @return string $domain Domain on which the extension is used.
     */
    public function getStoreUrl()
    {
        return $this->_storeUrl;
    }

    public function getLicenseKey()
    {
        return $this->_licenseKey;
    }

    public function setLicenseKey($licenseKey)
    {
        $this->_licenseKey = $licenseKey;
        return $this;
    }

    /**
     * Used to read a resource. If data is passed in Pest generates a URL-encoded query string
     * to be sent with with the request.
     *
     * @param  string                  $resource The resource we're requesting
     * @param  array                   $data     Contains data that needs to be sent with the query
     * @throws BssApiException
     * @return array/json/object                 Returns the response body, defaults to array
     */
    public function get($resource, $data = array())
    {
        $path = $resource;

        try {
            $client   = $this->getClient();
            $response = $client->get($path, $data);
        } catch(Exception $e) {
            throw $this->_parseException($e, $this->getClient());
        }

        return $response;
    }

    /**
     * Handles object creation.
     *
     * @param  string                   $resource  The type of object we wish to create
     * @param  array                    $data      Object creation data
     * @throws BssApiException
     * @return array/json/object                   Returns the response body, defaults to array
     */
    public function post($resource, $data)
    {
        $path = $resource;

        try {
            $client   = $this->getClient();
            $response = $client->post($path, $data);
        } catch(Exception $e) {
            //Repackage the exception as BssApiException, without a repackage PEST exceptions thrown here
            throw $this->_parseException($e, $this->getClient());
        }

        return $response;
    }

    /**
     * Repackages encountered exceptions as BssApiExceptions.
     *
     * @param  [type] $e    [description]
     * @param  [type] $pest [description]
     * @return [type]       [description]
     */
    protected function _parseException($e, $pest)
    {
        $msg = $e->getMessage();
        try {
            $code = $pest->lastStatus();
        } catch (Exception $e) {}

        $code = isset($code) ? $code : 0;
        $msg .= " Please contact support@betterstoresearch.com if problem persists.";
        $exception =  new BssApiException(array("message"=> $msg, "code"=> $code));

        return $exception;
    }

}
