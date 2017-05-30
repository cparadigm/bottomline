<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */
namespace VladimirPopov\WebForms\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Zend\Http\PhpEnvironment\Request;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DKEY = 'WF1DM';
    const SKEY = 'WFSRV';

    protected $storeManager;

    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager
    )
    {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    protected function getDomain($url)
    {
        $url = str_replace(array('http://', 'https://', '/'), '', $url);
        $tmp = explode('.', $url);
        $cnt = count($tmp);

        if(empty($tmp[$cnt - 2]) || empty($tmp[$cnt - 1])) return $url;

        $suffix = $tmp[$cnt - 2] . '.' . $tmp[$cnt - 1];

        $exceptions = array(
            'com.au', 'com.br', 'com.bz', 'com.ve', 'com.gp',
            'com.ge', 'com.eg', 'com.es', 'com.ye', 'com.kz',
            'com.cm', 'net.cm', 'com.cy', 'com.co', 'com.km',
            'com.lv', 'com.my', 'com.mt', 'com.pl', 'com.ro',
            'com.sa', 'com.sg', 'com.tr', 'com.ua', 'com.hr',
            'com.ee', 'ltd.uk', 'me.uk', 'net.uk', 'org.uk',
            'plc.uk', 'co.uk', 'co.nz', 'co.za', 'co.il',
            'co.jp', 'ne.jp', 'net.au', 'com.ar'
        );

        if (in_array($suffix, $exceptions))
            return $tmp[$cnt - 3] . '.' . $tmp[$cnt - 2] . '.' . $tmp[$cnt - 1];

        return $suffix;
    }

    public function verify($domain, $checkstr)
    {

        if ("wf" . substr(sha1(self::DKEY . $domain), 0, 18) == substr($checkstr, 0, 20)) {
            return true;
        }
        if ("wf" . substr(sha1(self::SKEY . $this->getServer('SERVER_ADDR')), 0, 10) == substr($checkstr, 0, 12)) {
            return true;
        }

        if ("wf" . substr(sha1(self::SKEY . gethostbyname($this->getServer())), 0, 10) == substr($checkstr, 0, 12)) {
            return true;
        }

        if ("wf" . substr(sha1(self::SKEY . gethostbyname($domain)), 0, 10) == substr($checkstr, 0, 12)) {
            return true;
        }

        $base = $this->getDomain(parse_url($this->storeManager->getStore(0)->getConfig('web/unsecure/base_url'), PHP_URL_HOST));
        if ("wf" . substr(sha1(self::SKEY . gethostbyname($base)), 0, 10) == substr($checkstr, 0, 12)) {
            return true;
        }

        if (substr(sha1(self::SKEY . $base), 0, 8) == substr($checkstr, 12, 8))
            return true;

        if ($this->verifyIpMask(array($this->getServer('SERVER_ADDR'), $this->getServer(), $domain, $base), $checkstr)) {
            return true;
        }
        return false;
    }

    private function verifyIpMask($data, $checkstr)
    {
        if (!is_array($data)) {
            $data = array($data);
        }
        foreach ($data as $name) {
            $ipdata = explode('.', gethostbyname($name));
            if (isset($ipdata[3])) $ipdata[3] = '*';
            $mask = implode('.', $ipdata);
            if ("wf" . substr(sha1(self::SKEY . $mask), 0, 10) == substr($checkstr, 0, 12)) {
                return true;
            }
            if (isset($ipdata[2])) $ipdata[2] = '*';
            $mask = implode('.', $ipdata);
            if ("wf" . substr(sha1(self::SKEY . $mask), 0, 10) == substr($checkstr, 0, 12)) {
                return true;
            }
        }
        return false;
    }

    public function isProduction()
    {
        $serial = $this->scopeConfig->getValue('webforms/license/serial', ScopeInterface::SCOPE_STORE);
        if ($this->_request->getParam('website')) {
            $serial = $this->storeManager->getWebsite($this->_request->getParam('website'))->getConfig('webforms/license/serial');
        }
        if ($this->_request->getParam('store')) {
            $serial = $this->storeManager->getStore($this->_request->getParam('store'))->getConfig('webforms/license/serial');
        }

        $checkstr = strtolower(str_replace(array(" ", "-"), "", $serial));

        // check for local environment
        if($this->isLocal()) return true;

        $domain = $this->getDomain($this->getServer());
        $domain2 = $this->getDomain($this->scopeConfig->getValue('web/unsecure/base_url', ScopeInterface::SCOPE_STORE));
        if ($this->_request->getParam('website')) {
            $domain2 = $this->getDomain($this->storeManager->getWebsite($this->_request->getParam('website'))->getConfig('web/unsecure/base_url'));
        }
        if ($this->_request->getParam('store')) {
            $domain2 = $this->getDomain($this->storeManager->getStore($this->_request->getParam('store'))->getConfig('web/unsecure/base_url'));
        }

        return $this->verify($domain, $checkstr) || $this->verify($domain2, $checkstr);
    }

    public function isLocal(){
        $domain = $this->getDomain($this->getServer());

        return substr($domain, -6) == '.local' ||
        substr($domain, -4) == '.dev' ||
        $this->getServer() == 'localhost' ||
        substr($this->getServer(), -7) == '.xip.io';
    }

    public function getServer($param = 'SERVER_NAME'){
        $request    = new Request;
        return $request->getServer($param);
    }

    public function getNote()
    {
        if ($this->scopeConfig->getValue('webforms/license/serial', ScopeInterface::SCOPE_STORE)) {
            return __('WebForms Professional Edition license number is not valid for store domain.');
        }
        return __('License serial number for WebForms Professional Edition is missing.');
    }
}