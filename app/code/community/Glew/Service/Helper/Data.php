<?php

class Glew_Service_Helper_Data extends Mage_Core_Helper_Abstract
{
    private static $connRead;
    private static $connWrite;
    private static $filename = 'glew.log';
    private static $debug = true;
    private $_config;
    protected $_store = null;

    public function getBaseDir()
    {
        return Mage::getBaseDir().'/app/code/community/Glew/';
    }

    public function getDatabaseConnection()
    {
        return Mage::getSingleton('core/resource')->getConnection('glew_write');
    }

    public function getDatabaseReadConnection()
    {
        return Mage::getSingleton('core/resource')->getConnection('glew_read');
    }

    public function getConfig()
    {
        $config = array();
        $config['enabled'] = Mage::getStoreConfig('glew_settings/general/enabled');
        $config['security_token'] = Mage::getStoreConfig('glew_settings/general/security_token');

        $this->_config = $config;

        return $config;
    }

    public function formatDate($str)
    {
        if ($str) {
            if (stripos($str, ' ')) {
                $str = substr($str, 0, stripos($str, ' '));
            }
        }

        return $str;
    }

    public function toArray($value, $create = false)
    {
        if ($value !== false) {
            return is_array($value) ? $value : array($value);
        } else {
            return $create ? array() : $value;
        }
    }

    public function logException($ex, $msg)
    {
        Mage::log(print_r($ex, true), null, self::$filename);

        return false;
    }

    public function log($msg)
    {
        return Mage::log($msg, null, self::$filename);
    }

    public function getLog() {
        return Mage::getBaseDir('log') . DS . self::$filename;
    }

    public function getStore()
    {
        if ($this->_store == null) {
            $this->_store = Mage::app()->getStore();
        }

        return $this->_store;
    }

    public function paginate($array, $pageNumber, $pageSize)
    {
        $start = $pageNumber * $pageSize;

        return array_slice($array, $start, $pageSize);
    }
}
