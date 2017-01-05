<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, Holger Brandt IT Solutions not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by Holger Brandt IT Solutions, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time Holger Brandt IT Solutions spent during the support process.
 * Holger Brandt IT Solutions does not guarantee compatibility with any other framework extension. Holger Brandt IT Solutions  is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * info@brandt-solutions.de, so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/
class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_ManProdModifiers extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract
{

    public function appendRelevenceScore(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection, &$rel_likes)
    {
        $manual_modifiers = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('bss_weight')
            ->addFieldToFilter('bss_weight', array('notnull' => 1))
            ->addFieldToFilter('bss_weight', array('neq' => 0))
            ->addFieldToFilter('bss_weight', array('neq' => 1))
            ;

        //@nelkaake Added on Thursday July 8, 2010: A failafe in case the bss_weight attribute is not yet recognized.
        if ($manual_modifiers->getSize() > 100000) {
            Mage::helper('bss')->log("WARNING: The size of the weighted product list is over 10,000 products.  This may exhaust server memory or time limits, so manual product weightings has temporarily been disabled. Full manual modifier product size was: ". $manual_modifiers->getSize());
            $manual_modifiers = array();
        }

        foreach ($manual_modifiers as $mp) {
            //Mage::helper('bss')->log("Product id={$mp->getSku()} will be modified with {$mp->getBssWeight()} weighting");
            $weight_m = (float)($mp->getBssWeight());
            if (!empty($weight_m)) {
                $rel_likes[] = $search_query_collection->getConnection()->quoteInto("(`e`.entity_id = ?)*{$weight_m}", $mp->getId());
            }
        }

        return $this;
    }

}