<?php

/**
 * Manage Currency
 *
 * @category   TBT
 * @package    TBT_Rewards
 * @author     Holger Brandt IT Solutions <info@brandt-solutions.de>
 */
class TBT_Bss_Block_Widget_Loyalty extends Mage_Adminhtml_Block_Template
{
    public function _toHtml()
    {
        $html = <<<FEED
            <iframe src="{$this->_getLoyaltyUrl()}" marginwidth="0" marginheight="0"
                    align="middle" frameborder="0"
                    scrolling="no" style="width: 500px; float: left; height: 22px;">
            </iframe>
FEED;

        return $html;
    }

    protected function _getLoyaltyUrl()
    {
        $url = $this->_getBaseLoyaltyUrl();

        $url_data = array();

        $url_data["a"] = "bss";
        $url_data["v"] = (string) Mage::getConfig()->getNode('modules/TBT_Bss/version');
        $url_data["license"] =  Mage::helper('bss/loyalty_checker')->getLicenseKey();

        $url_data["m"] =  Mage::getVersion();
        $url_data["p"] =  urlencode($this->getBaseUrl());
        $url_data["ap"] =  urlencode($this->getAction()->getFullActionName());

        $url_data_json = json_encode($url_data);

        $salt = "welovewdca12345!!";

        $url_data_json_hex = bin2hex($url_data_json . $salt);

        $url = $url . "?data=" . $url_data_json_hex;

        return $url;
    }

    protected function _getBaseLoyaltyUrl()
    {

        $url = "https://www.wdca.ca/m/";

        //@nelkaake: If the page is supposed to be HTTPS and the AJAX call is not HTTPS, add HTTPS
        // if it's HTTP and the url returned HTTPS, remove HTTPS
        if(  isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && strpos(strtolower($url), 'https') !== 0) {
            $url = str_replace('http', 'https', $url);
        } elseif(!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS'] && strpos(strtolower($url), 'https') === 0) {
            $url = str_replace('https', 'http', $url);
        } else {
            // the url is fine and we can continue because it's using the correct encryption
        }

        return $url;
    }
}
