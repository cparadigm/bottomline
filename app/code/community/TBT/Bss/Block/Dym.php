<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

class TBT_Bss_Block_Dym  extends Mage_Core_Block_Template
{

    public function getSearchQuery()
    {
        return $this->getRequest()->getParam('q');
    }

    protected function _toHtml()
    {
        //@nelkaakse -a 16/11/10: If it's enabled
        if(!Mage::getStoreConfigFlag('bss/dym/is_enabled')) {
            return "";
        }
        $q = $this->getSearchQuery();
        if(empty($q) | $q == "") {
            Mage::helper('bss')->log("Empty queries are not allowed for the Did You Mean suggestion tool.");

            return "";
        }

        return parent::_toHtml();
    }

    public function getCleanSearchQuery()
    {
        $q = $this->getSearchQuery();
        $q = strip_tags($q);
        // Added \' if single quote exists so that the JS doesn't give an error.
        $q = str_replace("'", "\\"."'", $q);

        return $q;
    }

    /**
     * Fetches the full URL to the suggest phrase AJAX script
     * @param array $extra_params
     */
    public function getSuggestPhraseUrl($extra_params=array())
    {
        $sug_url = $this->getUrl("bss/dym/suggestPhrase", array(
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()));

        //@nelkaake: If the page is supposed to be HTTPS and the AJAX call is not HTTPS, add HTTPS
        // if it's HTTP and the url returned HTTPS, remove HTTPS
        if(  isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS']) !== 'off' && strpos(strtolower($sug_url), 'https') !== 0) {
            $sug_url = str_replace('http', 'https', $sug_url);
        } elseif(!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS'] && strpos(strtolower($sug_url), 'https') === 0) {
            $sug_url = str_replace('https', 'http', $sug_url);
        } else {
            // the url is fine and we can continue because it's using the correct encryption
        }

        return $sug_url;

    }
}

