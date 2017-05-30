<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

class Feed extends \Magento\AdminNotification\Model\Feed
{
    public function getFeedUrl()
    {
        $httpPath = $this->_backendConfig->isSetFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://';
        if ($this->_feedUrl === null) {
            $this->_feedUrl = $httpPath . 'mageme.com/feeds/webforms/m2.rss';
        }
        return $this->_feedUrl;
    }

    public function observe()
    {
        $this->checkUpdate();
    }
}