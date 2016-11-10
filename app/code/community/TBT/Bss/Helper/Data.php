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

class TBT_Bss_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function log($m)
    {
        Mage::log($m, null, 'betterstoresearch.log');
        return $this;
    }

    public function extractTextFromPage($html, $limit = 0)
    {
        $search = array(
            '~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is',
            '@&lt;style.*?&gt;.*?&lt;/style&gt;@si'
        );

        $replace = array('','');
        $result  = trim(preg_replace($search,$replace,$html));
        $result  = preg_replace("#\s+#si", " ", trim(strip_tags($result)));

        // Convert all HTML entities to their applicable characters
        $result = html_entity_decode($result, ENT_QUOTES, "UTF-8");

        // if limit is set only return first $limit words from content
        if ($limit) {
            $words = array();
            $words = explode(" ", $result, $limit+1);

            $words[$limit] = "...";
            $result = implode(" ", $words);
        }

        return $result;
    }

    public function invalidateBssIndex()
    {
        $all_indexes = Mage::getModel('index/process')->getCollection()->addFieldToFilter('indexer_code', 'bss_fulltext');
        $index       = $all_indexes->getFirstItem();

        if ($index) {
            $index->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
        }

        return $this;
    }

    /**
     * @deprecated see TBT_Bss_Helper_Config::isEnabledPartialWordMatching
     * @return boolean [description]
     */
    public function isEnabledPartialWordMatching()
    {
        return Mage::helper('bss/config')->isEnabledPartialWordMatching();
    }

}
