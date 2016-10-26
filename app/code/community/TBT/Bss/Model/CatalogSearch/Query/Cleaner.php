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

class TBT_Bss_Model_CatalogSearch_Query_Cleaner extends Varien_Object
{
    /**
     *
     * Enter description here ...
     * @param unknown_type $queryText
     */
    public function getCleanedQuery($queryText)
    {
        $original_query = $queryText;

        if(Mage::getStoreConfigFlag('bss/special/hyphen')) {
            $queryText = str_replace('-', ' ', $queryText);
        }

        $queryText = strtolower($queryText);
        $queryText = $this->replaceBasicSpecialChars($queryText);
        $queryText = $this->replacePlural($queryText);
        $queryText = $this->replaceExtraPlural($queryText);

        if(empty($queryText)) {
            $queryText = $original_query;
        }

        return $queryText;

    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $queryText
     */
    public function replaceBasicSpecialChars($queryText)
    {
        //@nelkaake -a 16/11/10: Remove pluses.
        $queryText = str_replace('+', ' ', $queryText);

        return $queryText;

    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $queryText
     */
    public function replacePlural($queryText)
    {
        if (Mage::getStoreConfigFlag('bss/special/plural')) {
            $terms = explode(" ", trim($queryText));
            foreach($terms as &$t) {
                //@nelkaake Added on Saturday July 17, 2010:
                //@nelkaake -m 16/11/10: don't do plural match on words < 2 char
                if(strlen($t) <= 2) continue;

                if(substr($t, -1) == "s")    $t = substr_replace($t, "", -1);
            }
            $queryText = implode(" ", $terms);
        }
        return $queryText;

    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $queryText
     */
    public function replaceExtraPlural($queryText)
    {
        if (Mage::getStoreConfigFlag('bss/special/extra_plural')) {
            $terms = explode(" ", trim($queryText));
            foreach($terms as &$t) {
                //@nelkaake Added on Saturday July 17, 2010:
                //@nelkaake -m 16/11/10: don't do plural match on words < 2 char
                if(strlen($t) <= 2) continue;
                if(substr($t, -1) == "ey")    $t = substr_replace($t, "", -2);
                if(substr($t, -1) == "ry")    $t = substr_replace($t, "r", -2);
                if(substr($t, -1) == "ty")    $t = substr_replace($t, "t", -2);
                if(substr($t, -1) == "ly")    $t = substr_replace($t, "l", -2);
                if(substr($t, -1) == "ies")    $t = substr_replace($t, "", -3);
            }
            $queryText = implode(" ", $terms);
        }

        return $queryText;
    }


    protected $_termChanges = array();
    protected function _profileChange() {
        if(Mage::registry('bss_profiled_term_change')) {
            //$this->_termChanges
            //TODO
        }
    }

}