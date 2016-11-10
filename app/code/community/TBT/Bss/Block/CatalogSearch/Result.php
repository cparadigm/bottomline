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

/**
 *
 * @category   TBT
 * @package    TBT_Bss
 * @author     WDCA Better Store Search Team <contact@wdca.ca>
 */
class TBT_Bss_Block_CatalogSearch_Result  extends Mage_CatalogSearch_Block_Result
{
    protected function _construct()
    {
        parent::_construct();
        $this->setHeaderText(Mage::helper('bss')->__("Product search results for '%s'", Mage::helper('catalogsearch')->getEscapedQueryText()));
        $this->setNoResultText(Mage::helper('bss')->__("Your search returns no product results."));

        return $this;
    }

    protected function _toHtml()
    {
        $html = '';
        // skip product search if disabled in configuration section
        if (Mage::getStoreConfigFlag('bss/special/catalog')) {
            $html = parent::_toHtml();

            //@nelkaake -a 16/11/10: Attempts to append the DYM code to the end of the rendered code of this block
            // if there were no results in the list and minimum query length is satisfied
            $minQueryLengthFailed = Mage::helper('catalogsearch')->isMinQueryLength();
            if(!$this->getResultCount() && !$minQueryLengthFailed && Mage::getStoreConfigFlag('bss/dym/use_rewrite')) {
                $injection = $this->getChildHtml('bss_dym');
                $html = $html . $injection;
            }

        }
        // if enabled in configuration section, search through CMS pages
        if (Mage::getStoreConfigFlag('bss/cms_search/cms')) {
            $injection = $this->getChildHtml('bss_cms_result');
            $html = $html . $injection;
        }

        return $html;
    }

}