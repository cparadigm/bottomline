<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is
 * not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code.
 * By adding to, editing, or in any way modifying this code, the Licensee
 * terminates any agreement of support offered by WDCA, outlined in the
 * provided Sweet Tooth License.
 * Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent
 * during the support process.
 * WDCA does not guarantee compatibility with any other framework extension.
 * WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension.
 * If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy
 * immediately.
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
class TBT_Bss_Block_CatalogSearch_Profiler extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        if (!$this->_beforeToHtml()
            || !Mage::getStoreConfigFlag('bss/dev/profiler')
            || !Mage::helper('core')->isDevAllowed()) {
            return '';
        }



        #$out = '<div style="position:fixed;bottom:5px;right:5px;opacity:.1;background:white" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.1">';
        #$out = '<div style="opacity:.1" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.1">';
        $out = "<a href=\"javascript:void(0)\" onclick=\"$('bss_search_profiler_section').style.display=$('bss_search_profiler_section').style.display==''?'none':''\">[BSS Profiler]</a>";
        $out .= '<div id="bss_search_profiler_section" style="background:white; display:block">';
        //$out .= '<pre>Memory usage: real: '.memory_get_usage(true).', emalloc: '.memory_get_usage().'</pre>';

        $rank_table_out = $this->getRankingTableHtml();
        $out .= $rank_table_out;

        $out .= '</div>';

        //@nelkaake -a 17/02/11: If the ranking table is empty, what's the point of displaying the rest of this?
        if(empty($rank_table_out)) return "";

        return $out;
    }

    /**
     * Returns the HTML for the ranking table profiler
     */
    protected function getRankingTableHtml() {
        $out = "";

        $search_results = Mage::registry('bss_profiled_search_results');
        if(!$search_results) {
            Mage::helper('bss')->log("ERROR: The BSS profiler could not be shown because somehow the bss_profiled_search_results registry value was not set.");
            return '';
        }

        if(sizeof($search_results) > 0) {
            $rel_cols = array_keys($search_results[0]);
        } else {
            $rel_cols = 0;
        }


        $out .= '<table border="1" cellspacing="0" cellpadding="2" style="width:auto">';
        $out .= '<tr>    <th></th>    <th>PID</th>    <th>SKU</th>    <th>Product Name</th>';
        if (Mage::helper('bss/config')->isCategoryMatchEnabled()) {
            $out .= '   <th>Category Names</th>';
        }
        if (Mage::helper('bss/config')->isTagMatchEnabled()) {
            $out .= '   <th>Tags</th>';
        }
        $out .= '    <th>Basic Rank</th>';

        foreach($rel_cols as $col) {
            if(strpos($col, "rel_") === 0) {
                $out .= "<th>".str_replace("rel_", "", $col)."</th>";
            }
        }

        $out .= "<th>TOTAL RANK</th>";

        $out .="</tr>";
        foreach ($search_results as $index=>$search_result) {
            $out .= '<tr>'
                .'<td align="left">'.($index+1).'</td>'
                .'<td>'.$search_result['entity_id'].'</td>'
                .'<td align="right">'.$search_result['sku'].'</td>'
                .'<td align="right">'.$search_result['name'].'</td>';

            if (Mage::helper('bss/config')->isCategoryMatchEnabled() && isset($search_result['cat_name'])) {
                $out .= '<td align="right">'.$search_result['cat_name'].'</td>';
            }
            if (Mage::helper('bss/config')->isTagMatchEnabled()) {
                $out .= '<td align="right">'.$search_result['tags'].'</td>';
            }

            $search_result["rel_basic"] = (float)$search_result['relevance'];
            foreach($rel_cols as $col) {
                if(strpos($col, "rel_") === 0) {
                    $search_result['rel_basic'] -= $search_result[$col];
                }
            }
            $out .= '<td align="right">+'.$search_result['rel_basic'].'</td>';


            foreach($rel_cols as $col) {
                if(strpos($col, "rel_") === 0) {
                    $out .= '<td align="right">+'.$search_result[$col].'</td>';
                }
            }

            $out .= '<td align="right">'.$search_result['relevance'].'</td>';
            $out .= '</tr>';
        }
        $out .= '</table>';

        return $out;
    }
}
